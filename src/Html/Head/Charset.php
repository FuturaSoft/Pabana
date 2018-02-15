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
namespace Pabana\Html\Head;

use Pabana\Html\Doctype;

/**
 * Charset class
 *
 * Define charset use
 */
class Charset
{
    /**
     * @var     string Charset (by default UTF-8).
     * @since   1.0.0
     */
    private static $_sCharset = 'UTF-8';

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0.0
     * @return  string Html code for Charset
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Clean
     *
     * Reinitialize Charset value to UTF-8
     *
     * @since   1.0.0
     * @return  void
     */
    public function clean()
    {
        self::$_sCharset = 'UTF-8';
    }

    /**
     * Render
     *
     * Return HTML code for Charset
     *
     * @since   1.0.0
     * @return  string Html code for Charset
     */
    public function render()
    {
        $oDoctype = new Doctype();
        $arsCharset = array(
            'UTF-8' => 'utf-8',
            'UTF-16' => 'utf-16',
            'ISO-8859-1' => 'iso-8859-1',
            'ISO-8859-5' => 'iso-8859-5',
            'ISO-8859-15' => 'iso-8859-15',
            'CP1251' => 'windows-1251',
            'CP1252' => 'windows-1252',
            'KOI8-R' => 'koi8-r',
            'BIG5' => 'big5',
            'GB2312' => 'gb2312',
            'BIG5-HKSCS' => 'big5-hkscs',
            'SHIFT_JIS' => 'shift_jis',
            'EUC-JP' => 'euc-jp',
            'MACROMAN' => 'x-mac-roman'
        );
        $arsKeyCharset = array_keys($arsCharset);
        if (!in_array(self::$_sCharset, $arsKeyCharset)) {
            $sErrorMessage = 'Charset ' . self::$_sCharset . ' isn\'t defined';
            throw new \Exception($sErrorMessage);
        } else {
            if ($oDoctype->get() == 'HTML5') {
                return '<meta charset="' . $arsCharset[self::$_sCharset] . '">' . PHP_EOL;
            } else {
                if (substr($oDoctype->get(), 0, 1) == 'X') {
                    return '<meta http-equiv="Content-Type" content="text/html; charset=' . $arsCharset[self::$_sCharset] . '" />' . PHP_EOL;
                } else {
                    return '<meta http-equiv="Content-Type" content="text/html; charset=' . $arsCharset[self::$_sCharset] . '">' . PHP_EOL;
                }
            }
        }
    }

    /**
     * Set charset
     *
     * Set charset
     *
     * @since   1.0.0
     * @param   string $sCharset Charset
     * @return  $this
     */
    public function set($sCharset)
    {
        self::$_sCharset = $sCharset;
        return $this;
    }
}
