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
    private static string $language_code = '';
    private static array $translateList = [];
    private static string $file = '';

    /**
     * Get language
     *
     * Get defined language
     *
     * @since   1.2
     * @param   bool $bFallback If true return fallback language
     * @return  string  Language code
     */
    public static function getLanguage(bool $bFallback = false): string
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
     * @return  void
     */
    public static function setLanguage(string $language): void
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
     * @return  bool True if file is loaded else false.
     */
    public static function load(string $file): bool
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
     * Translate a key to its value in current language
     *
     * @since   1.2
     * @param   string $keyOrValue Key to translate or default value if not found.
     * @param   array $optionList Array of placeholders to replace.
     * @return  string Translated string or original value if not found.
     */
    public static function translate(string $keyOrValue, array $optionList = []): string
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
