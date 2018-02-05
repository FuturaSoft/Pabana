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

class Encoding
{
    public function detect($sValue)
    {
        return mb_detect_encoding($sValue);
    }

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
        } elseif (is_array($mValue)) {
            $mReturn = array();
            foreach ($mValue as $mArrayKey => $mArrayValue) {
                $mArrayKey = $this->convert($mArrayKey, $sInCharset, $sOutCharset, $bTranslit, $bIgnore);
                $mArrayValue = $this->convert($mArrayValue, $sInCharset, $sOutCharset, $bTranslit, $bIgnore);
                $mReturn[$mArrayKey] = $mArrayValue;
            }
        }
        return $mReturn;
    }
}
