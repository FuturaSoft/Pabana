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
 * @since         1.2
 * @license       https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
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
    private static $translateList;
    private static $file;

    /**
     * Get language
     *
     * Get defined language
     *
     * @since   1.2
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
     * Set language
     *
     * Set language by code
     *
     * @since   1.2
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
     * Load language configuration file
     *
     * @since   1.2
     * @param   string  $file   Language configuration file
     */
    public static function load($file)
    {
        self::$file = $file;
        $translatePath = APP_ROOT . DS . Configuration::read('intl.path') . DS . self::getLanguage() . DS . self::$file;
        if (file_exists($translatePath)) {
            self::$translateList = (include $translatePath);
            return true;
        }
        $translatePath = APP_ROOT . DS . Configuration::read('intl.path') . DS . self::getLanguage(true) . DS . self::$file;
        if (file_exists($translatePath)) {
            self::$translateList = (include $translatePath);
            return true;
        }
        self::$file = '';
        return false;
    }

    /**
     * Do translate
     *
     * Get defined language
     *
     * @since   1.2
     * @return  string  Define language by code
     */
    public static function translate($keyOrValue, $optionList = [])
    {
        if (isset(self::$translateList[$keyOrValue])) {
            $returnString = self::$translateList[$keyOrValue];
            if (!empty($optionList)) {
                foreach ($optionList as $key => $value) {
                    $returnString = str_replace(':' . $key, $value, $returnString);
                }
            }
            return $returnString;
        }
        return $keyOrValue;
    }
}
