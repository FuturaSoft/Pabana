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
namespace Pabana\Intl;

/**
 * Encoding class
 *
 * Detect and manipulate encoding
 */
class Encoding
{
    /**
     * Detect encoding
     *
     * Detect encoding use for a string
     *
     * @since   1.0.0
     * @param   string $sValue String who you want detect encoding.
     * @return  string Encoding use for string in argument.
     */
    public function detect($sValue)
    {
        return mb_detect_encoding($sValue);
    }

    /**
     * Convert encoding
     *
     * Convert encoding in a string
     *
     * @since   1.0.0
     * @param   string|array $mValue Array of string or string that you want convert encoding.
     * @param   string $sInCharset Charset of $mValue before convert (if value = 'auto' so charset will be detected).
     * @param   string $sOutCharset Charset of $mValue after convert(if value = 'auto' so application charset will be use).
     * @param   bool $bTranslit Enable translit (by default true)
     * @param   bool $bIgnore Enable ignore (by default true)
     * @return  string|array String or array according to $mValue.
     */
    public function convert($mValue, $sInCharset = 'auto', $sOutCharset = 'auto', $bTranslit = true, $bIgnore = true)
    {
        $mReturn = $mValue;
        if (is_string($mValue)) {
            if ($sInCharset == 'auto') {
                $sInCharset = $this->detect($mValue);
            }
            if ($sOutCharset == 'auto') {
                $sOutCharset = Configuration::read('application.encoding');
            }
            if ($bTranslit === true) {
                $sOutCharset .= '//TRANSLIT';
            } elseif ($bIgnore === true) {
                $sOutCharset .= '//IGNORE';
            }
            $mReturn = @iconv($sInCharset, $sOutCharset, $mValue);
            if ($mReturn === false) {
                $mReturn = $mValue;
            }
        } elseif (is_array($mValue)) {
            $mReturn = array();
            foreach ($mValue as $mArrayKey => $mArrayValue) {
                $mArrayKeyConvert = $this->convert($mArrayKey, $sInCharset, $sOutCharset, $bTranslit, $bIgnore);
                if ($mReturn === false) {
                    $mArrayKeyConvert = $mArrayKey;
                }
                $mArrayValueConvert = $this->convert($mArrayValue, $sInCharset, $sOutCharset, $bTranslit, $bIgnore);
                if ($mReturn === false) {
                    $mArrayValueConvert = $mArrayValue;
                }
                $mReturn[$mArrayKeyConvert] = $mArrayValueConvert;
            }
        }
        return $mReturn;
    }
}