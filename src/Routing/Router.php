<?php
/**
 * Pabana : PHP Framework (https://pabana.futurasoft.fr)
 * Copyright (c) FuturaSoft (https://futurasoft.fr)
 *
 * Licensed under BSD-3-Clause License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) FuturaSoft (https://futurasoft.fr)
 * @link          https://pabana.futurasoft.fr Pabana Project
 * @since         1.0.0
 * @license       https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */
namespace Pabana\Routing;

use Pabana\Core\Configuration;
use Pabana\Network\Http\Request;
use Pabana\Routing\Route;
use Pabana\Routing\RouteCollection;

class Router
{
    private static $sController;
    private static $sAction;
    private static $arsParameter;

    private static function autoResolve($arsUrlValueList)
    {
        $sController = 'Index';
        $sAction = 'index';
        $arsParamList = array();
        foreach ($arsUrlValueList as $nIndexValue => $sUrlValue) {
            if ($nIndexValue == 0) {
                $sController = $sUrlValue;
            } elseif ($nIndexValue == 1) {
                $sAction = $sUrlValue;
            } else {
                if (isset($arsUrlValueList[$nIndexValue]) && $nIndexValue % 2 == 0) {
                    $sParamName = $arsUrlValueList[$nIndexValue];
                    $sParamValue = null;
                    if (isset($arsUrlValueList[$nIndexValue + 1])) {
                        $sParamValue = $arsUrlValueList[$nIndexValue + 1];
                    }
                    $arsParamList[$sParamName] = $sParamValue;
                }
            }
        }
        // Set controller
        self::setController($sController);
        // Set action to route action
        self::setAction($sAction);
        // Set param list
        self::setParameter($arsParamList);
    }

    private static function checkRoute($arcUrlSeparator, $arsUrlValue)
    {
        $aroRouteList = RouteCollection::getAll();
        if (empty($aroRouteList)) {
            return false;
        }
        foreach ($aroRouteList as $oRoute) {
            // Get route
            $sRoute = $oRoute->getRoute();
            // Remove last char of route if it's a separator
            $sRoute = self::removeLastSeparator($sRoute);
            // Get list of separator for this route
            $arcRouteSeparator = self::listSeparator($sRoute);
            // If URL and route have same number of separator in same position
            if ($arcUrlSeparator == $arcRouteSeparator) {
                // Get list of value of this route
                $arsRouteValueList = self::listValue($sRoute);
                // Valid boolean
                $bGoodRoute = true;
                // Array to store param
                $arsParamList = array();
                // Check each value of url to compare with route
                foreach ($arsRouteValueList as $nParamIndex => $sRouteValue) {
                    if (isset($arsUrlValue[$nParamIndex])) {
                        // Get first char of
                        $cFirstCharValue = substr($sRouteValue, 0, 1);
                        if ($cFirstCharValue == ':') {
                            $arsParamList[substr($sRouteValue, 1)] = $arsUrlValue[$nParamIndex];
                        } elseif ($sRouteValue != $arsUrlValue[$nParamIndex] && $cFirstCharValue != '*') {
                            $bGoodRoute = false;
                        }
                    } else {
                        $bGoodRoute = false;
                    }
                }
                // If valid boolean is true
                if ($bGoodRoute) {
                    // Set controller to route controller
                    self::setController($oRoute->getController());
                    // Set action to route action
                    self::setAction($oRoute->getAction());
                    // Add route param to param list
                    if (is_array($oRoute->getParamList())) {
                        $arsParamList = $arsParamList + $oRoute->getParamList();
                    }
                    // Set param list
                    self::setParameter($arsParamList);
                    // End of check route
                    return true;
                }
            }
        }
        // No route is found
        return false;
    }

    private static function checkController()
    {
        $bSetFallback = false;
        $sControllerNamespace = Configuration::read('application.namespace') . '\Controller\\' . self::getController();
        if (class_exists($sControllerNamespace) === false) {
            $bSetFallback = true;
        }
        if (method_exists($sControllerNamespace, self::getAction()) === false) {
            $bSetFallback = true;
        }
        if ($bSetFallback === true) {
            // Set controller
            $sFallbackController = RouteCollection::getFallbackController();
            self::setController($sFallbackController);
            // Set action to route action
            $sFallbackAction = RouteCollection::getFallbackAction();
            self::setAction($sFallbackAction);
            // Set action to route action
            $arsFallbackParameter = array('code' => 404);
            self::setParameter($arsFallbackParameter);
        }
    }

    public static function getAction()
    {
        return self::$sAction;
    }

    public static function getController()
    {
        return self::$sController;
    }

    public static function getParameter()
    {
        return self::$arsParameter;
    }

    private static function listSeparator($sUrl)
    {
        $armSeparatorPosition = array();
        foreach (RouteCollection::getSeparator() as $cSeparator) {
            $sUrlSearch = $sUrl;
            while ($nSeparatorPosition = strrpos($sUrlSearch, $cSeparator)) {
                $armSeparatorPosition[$nSeparatorPosition] = $cSeparator;
                $sUrlSearch = substr($sUrlSearch, 0, $nSeparatorPosition);
            }
        }
        ksort($armSeparatorPosition);
        return array_values($armSeparatorPosition);
    }

    private static function listValue($sUrl)
    {
        $sRegexSeparator = implode('', RouteCollection::getSeparator());
        $sRegexSeparator = preg_quote($sRegexSeparator);
        $arsListValue = preg_split("{[" . $sRegexSeparator . "]}", $sUrl);
        array_shift($arsListValue);
        return $arsListValue;
    }

    private static function removeLastSeparator($sUrl)
    {
        $cLastChar = substr($sUrl, -1);
        if (in_array($cLastChar, RouteCollection::getSeparator())) {
            $sUrl = substr($sUrl, 0, -1);
        }
        return $sUrl;
    }

    public static function resolve()
    {
        // Get current URL
        $oRequest = new Request();
        $sUrl = $oRequest->url();
        // Set default separator
        if (Configuration::check('routing.default_separator') === true) {
            $cDefaultSeparator = Configuration::read('routing.default_separator');
            RouteCollection::setDefaultSeparator($cDefaultSeparator);
        }
        // Remove last char of URL if it's a separator
        $sUrl = self::removeLastSeparator($sUrl);
        // Get list of separator for this URL
        $arcUrlSeparator = self::listSeparator($sUrl);
        // Get list of value of this URL
        $arsUrlValue = self::listValue($sUrl);
        // Check route
        $bCheckResult = self::checkRoute($arcUrlSeparator, $arsUrlValue);
        if ($bCheckResult === false && Configuration::read('routing.auto') === true) {
            self::autoResolve($arsUrlValue);
        }
        self::checkController();
        self::setParameterInGlobal();
    }

    private static function setAction($sAction)
    {
        self::$sAction = $sAction;
    }

    private static function setController($sController)
    {
        self::$sController = ucfirst($sController);
    }

    private static function setParameter($arsParameter)
    {
        self::$arsParameter = $arsParameter;
    }

    private static function setParameterInGlobal()
    {
        $_GET = self::$arsParameter + $_GET;
    }
}
