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
     * @since   1.0
     * @param   string $value String who you want detect encoding.
     * @return  string Encoding use for string in argument.
     */
    public function detect($value)
    {
        return mb_detect_encoding($value);
    }

    /**
     * Convert encoding
     *
     * Convert encoding in a string
     *
     * @since   1.0
     * @param   string|array $value Array of string or string that you want convert encoding.
     * @param   string $inCharset Charset of $mValue before convert (if value = 'auto' so charset will be detected).
     * @param   string $outCharset Charset of $mValue after convert(if value = 'auto' so application charset will be use).
     * @param   bool $translit Enable translit (by default true)
     * @param   bool $ignore Enable ignore (by default true)
     * @return  string|array String or array according to $mValue.
     */
    public function convert($value, $inCharset = 'auto', $outCharset = 'auto', $translit = true, $ignore = true)
    {
        $return = $value;
        if (is_string($value)) {
            if ($inCharset == 'auto') {
                $inCharset = $this->detect($value);
            }
            if ($outCharset == 'auto') {
                $outCharset = Configuration::read('application.encoding');
            }
            if ($translit === true) {
                $outCharset .= '//TRANSLIT';
            } elseif ($ignore === true) {
                $outCharset .= '//IGNORE';
            }
            $return = @iconv($inCharset, $outCharset, $value);
            if ($return === false) {
                $return = $value;
            }
        } elseif (is_array($value)) {
            $return = array();
            foreach ($value as $arrayKey => $arrayValue) {
                $arrayKeyConvert = $this->convert($arrayKey, $inCharset, $outCharset, $translit, $ignore);
                if ($return === false) {
                    $arrayKeyConvert = $arrayKey;
                }
                $arrayValueConvert = $this->convert($arrayValue, $inCharset, $outCharset, $translit, $ignore);
                if ($return === false) {
                    $arrayValueConvert = $arrayValue;
                }
                $return[$arrayKeyConvert] = $arrayValueConvert;
            }
        }
        return $return;
    }
}
