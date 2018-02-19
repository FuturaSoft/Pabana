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

/**
 * Route collection class
 *
 * List of defined route
 */
class RouteCollection
{
    /**
     * @var    array Liste of route
     * @since   1.0.0
     */
    private static $arsRouteCollection;

    /**
     * @var    string Fallback action (by default "index")
     * @since   1.0.0
     */
    private static $sFallbackAction = 'index';

    /**
     * @var    string Fallback controller (by default "Error")
     * @since   1.0.0
     */
    private static $sFallbackController = 'Error';

    /**
     * @var    array List of separator of url (by default '/', '?', '&', '=')
     * @since   1.0.0
     */
    private static $arcSeparator = array('/', '?', '&', '=');

    /**
     * @var    char Default separator ('/')
     * @since   1.0.0
     */
    private static $cDefaultSeparator = '/';

    /**
     * Create a route to collection
     *
     * @since   1.0.0
     * @param   string $sRoute Route.
     * @param   array $arsOption Option (controller, action and param).
     */
    public static function add($sRoute, $arsOption)
    {
        self::$arsRouteCollection[] = new Route($sRoute, $arsOption);
    }

    /**
     * Get fallback action
     *
     * @since   1.0.0
     * @return  string Fallback action.
     */
    public static function getFallbackAction()
    {
        return self::$sFallbackAction;
    }

    /**
     * Get fallback controller
     *
     * @since   1.0.0
     * @return  string Fallback controller.
     */
    public static function getFallbackController()
    {
        return self::$sFallbackController;
    }

    /**
     * Get list of route defined in collection
     *
     * @since   1.0.0
     * @return  array List of route defined in collection.
     */
    public static function getAll()
    {
        return self::$arsRouteCollection;
    }

    /**
     * Get default separator
     *
     * @since   1.0.0
     * @return  char Default separator.
     */
    public static function getDefaultSeparator()
    {
        return self::$cDefaultSeparator;
    }

    /**
     * Get separator list
     *
     * @since   1.0.0
     * @return  array List of separator.
     */
    public static function getSeparator()
    {
        return self::$arcSeparator;
    }

    /**
     * Set fallback
     *
     * @since   1.0.0
     * @param   string $sController Fallback controller.
     * @param   string $sAction Fallback action.
     * @return  void
     */
    public static function setFallback($sController, $sAction)
    {
        self::$sFallbackAction = $sAction;
        self::$sFallbackController = $sController;
    }

    /**
     * Set default separator
     *
     * @since   1.0.0
     * @param   char $cSeparator Separator.
     * @param   bool $bMerge Merge default separtor to separator list.
     * @return  void
     */
    public static function setDefaultSeparator($cSeparator, $bMerge = true)
    {
        self::$cDefaultSeparator = $cSeparator;
        self::setSeparator($cSeparator, $bMerge);
    }

    /**
     * Set separator
     *
     * @since   1.0.0
     * @param   array|string $mSeparator Separator char or array of char.
     * @param   bool $bMerge Merge $mSeparator with separator list.
     * @return  void
     */
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