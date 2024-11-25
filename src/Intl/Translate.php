<?php
/**
 * Pabana : PHP Framework (https://pabana.futurasoft.fr)
 * Copyright (c) FuturaSoft (https://futurasoft.fr)
 *
 * Licensed under BSD-3-Clause License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) FuturaSoft (https://futurasoft.fr)
 * @link        https://pabana.futurasoft.fr Pabana Project
 * @license     https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 * @since       1.2.0
 * @version     1.3.0
 */
namespace Pabana\Intl;

use Pabana\Core\Configuration;

/**
 * Translate class
 *
 * Manage translation
 */
class Translate
{
    private static $language_code;
    private static $translateList = [];
    private static $file;

    /**
     * Get defined language
     *
     * @since   1.2.0
     * @version 1.2.0
     * 
     * @return  string  Define language by code
     */
    public static function getLanguage($bFallback = false)
    {
        if ($bFallback === true) {
            return Configuration::read('intl.lang_fallback', 'en');
        }
        if (!empty(self::$language_code)) {
            return self::$language_code;
        } else {
            return Configuration::read('intl.lang_fallback', 'en');
        }
    }

    /**
     * Set language by code
     *
     * @since   1.2.0
     * @version 1.2.0
     * 
     * @param   string  $language   Define language by code
     */
    public static function setLanguage($language)
    {
        $langPath = APP_ROOT . DS . Configuration::read('intl.path') . DS . $language;
        if (file_exists($langPath)) {
            self::$language_code = $language;
        } else {
            self::$language_code = Configuration::read('intl.lang_fallback', 'en');
        }
        if (!empty(self::$file)) {
            self::load(self::$file);
        }
    }

    /**
     * Load language configuration file
     *
     * @since   1.2.0
     * @version 1.3.0
     * 
     * @param   string  $file   Language configuration file
     * 
     * @return  boolean Return state of translation file loading
     */
    public static function load($file)
    {
        self::$file = $file;
        $translatePath = APP_ROOT . DS . Configuration::read('intl.path') . DS . self::getLanguage() . DS . self::$file;
        if (file_exists($translatePath)) {
            self::$translateList = array_merge(self::$translateList, (include $translatePath));
            return true;
        } else {
            $translatePath = APP_ROOT . DS . Configuration::read('intl.path') . DS . self::getLanguage(true) . DS . self::$file;
            if (file_exists($translatePath)) {
                self::$translateList = (include $translatePath);
                return true;
            }
        }
        self::$file = '';
        return false;
    }

    /**
     * Define key/value of translate
     *
     * @since   1.3.0
     * @version 1.3.0
     * 
     * @param   string  $key    Key of translation
     * @param   string  $value  Value of translation
     * 
     * @return  void
     */
    public static function set($key, $value)
    {
        self::$translateList[$key] = $value;
    }

    /**
     * Do translation
     *
     * @since   1.2.0
     * @version 1.3.0
     * 
     * @param   string  $key        Key of translation
     * @param   array   $options    Array of options
     * 
     * @return  string  Translate string or key if not found
     */
    public static function translate($key, $options = [])
    {
        if (isset(self::$translateList[$key])) {
            $returnString = self::$translateList[$key];
            if (!empty($options)) {
                foreach ($options as $optionKey => $optionValue) {
                    $returnString = str_replace(':' . $optionKey, $optionValue, $returnString);
                }
            }
            return $returnString;
        }
        return $key;
    }
}
