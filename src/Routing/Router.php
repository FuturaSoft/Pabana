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
 * @since         1.0
 * @license       https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */
namespace Pabana\Routing;

use Pabana\Core\Configuration;
use Pabana\Network\Http\Request;
use Pabana\Routing\Route;
use Pabana\Routing\RouteCollection;

/**
 * Router class
 *
 * Define controller and action from url and route collection
 */
class Router
{
    /**
     * @var     string Controller defined by Router
     * @since   1.0
     */
    private static $sController;

    /**
     * @var     string Action defined by Router
     * @since   1.0
     */
    private static $sAction;

    /**
     * @var     array Parameters defined by Router
     * @since   1.0
     */
    private static $arsParameter;

    /**
     * Auto resolve a Route from URL by separator
     *
     * @since   1.0
     * @param   string $arsUrlValueList Liste of url part.
     * @return  void
     */
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

    /**
     * Check if Route exist in RouterCollection who can match to current url
     *
     * @since   1.0
     * @param   array $arcUrlSeparator List of separator of URL.
     * @param   array $arsUrlValue List of part of URL.
     * @return  bool True if Route match, else false
     */
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

    /**
     * Check if controller and action exist, else change to fallback controller and action
     *
     * @since   1.0
     * @return  void
     */
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

    /**
     * Get action defined by Router.
     *
     * @since   1.0
     * @return  string Action defined by Router.
     */
    public static function getAction()
    {
        return self::$sAction;
    }

    /**
     * Get controller defined by Router.
     *
     * @since   1.0
     * @return  string Controller defined by Router.
     */
    public static function getController()
    {
        return self::$sController;
    }

    /**
     * Get parameter defined by Router.
     *
     * @since   1.0
     * @return  array Parameter defined by Router.
     */
    public static function getParameter()
    {
        return self::$arsParameter;
    }

    /**
     * List of separator defined in URL.
     *
     * @since   1.0
     * @param   string $sUrl Current URL.
     * @return  array List of separator defined in URL.
     */
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

    /**
     * List of value defined in URL.
     *
     * @since   1.0
     * @param   string $sUrl Current URL.
     * @return  array List of value defined in URL.
     */
    private static function listValue($sUrl)
    {
        $sRegexSeparator = implode('', RouteCollection::getSeparator());
        $sRegexSeparator = preg_quote($sRegexSeparator);
        $arsListValue = preg_split("{[" . $sRegexSeparator . "]}", $sUrl);
        array_shift($arsListValue);
        return $arsListValue;
    }

    /**
     * Remove last separtor in URL.
     *
     * @since   1.0
     * @param   string $sUrl Current URL.
     * @return  string $Url without last separator.
     */
    private static function removeLastSeparator($sUrl)
    {
        $cLastChar = substr($sUrl, -1);
        if (in_array($cLastChar, RouteCollection::getSeparator())) {
            $sUrl = substr($sUrl, 0, -1);
        }
        return $sUrl;
    }

    /**
     * Call differnant action in Router to resolve road from URL.
     *
     * @since   1.0
     * @return  void
     */
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

    /**
     * Set action defined by Router from URL.
     *
     * @since   1.0
     * @param   string $sAction Action.
     * @return  void
     */
    private static function setAction($sAction)
    {
        self::$sAction = $sAction;
    }

    /**
     * Set controller defined by Router from URL.
     *
     * @since   1.0
     * @param   string $sController Controller.
     * @return  void
     */
    private static function setController($sController)
    {
        self::$sController = ucfirst($sController);
    }

    /**
     * Set parameter defined by Router from URL.
     *
     * @since   1.0
     * @param   string $arsParameter Parameter.
     * @return  void
     */
    private static function setParameter($arsParameter)
    {
        self::$arsParameter = $arsParameter;
    }

    /**
     * Transfert GET parameter to $_GET global array.
     *
     * @since   1.0
     * @return  void
     */
    private static function setParameterInGlobal()
    {
        $_GET = self::$arsParameter + $_GET;
    }
}
