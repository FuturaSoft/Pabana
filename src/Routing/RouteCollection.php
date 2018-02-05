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

use Pabana\Routing\Route;

class RouteCollection
{
    private static $arsRouteCollection;
    private static $sFallbackAction = 'index';
    private static $sFallbackController = 'Error';
    private static $arcSeparator = array('/', '?', '&', '=');
    private static $cDefaultSeparator = '/';

    public static function add($sRoute, $arsOption)
    {
        self::$arsRouteCollection[] = new Route($sRoute, $arsOption);
    }

    public static function getFallbackAction()
    {
        return self::$sFallbackAction;
    }

    public static function getFallbackController()
    {
        return self::$sFallbackController;
    }

    public static function getAll()
    {
        return self::$arsRouteCollection;
    }

    public static function getDefaultSeparator()
    {
        return self::$cDefaultSeparator;
    }

    public static function getSeparator()
    {
        return self::$arcSeparator;
    }

    public static function setFallback($sController, $sAction)
    {
        self::$sFallbackAction = $sAction;
        self::$sFallbackController = $sController;
    }

    public static function setDefaultSeparator($cSeparator, $bMerge = true)
    {
        self::$cDefaultSeparator = $cSeparator;
        self::setSeparator($cSeparator, $bMerge);
    }

    public static function setSeparator($mSeparator, $bMerge = true)
    {
        if (is_array($mSeparator)) {
            if ($bMerge === true) {
                self::$arcSeparator = $mSeparator + self::$arcSeparator;
            } else {
                self::$arcSeparator = $mSeparator;
            }
        } else {
            if ($bMerge === true) {
                if (in_array($mSeparator, self::$arcSeparator) === false) {
                    self::$arcSeparator[] = $mSeparator;
                }
            } else {
                self::$arcSeparator = array($mSeparator);
            }
        }
    }
}
