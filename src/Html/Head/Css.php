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

class Css
{
    private static $_arsCssList = array();

    public function __toString()
    {
        return $this->render();
    }

    public function appendFile($sHref, $sMedia = null)
    {
        self::$_arsCssList[] = array('/css/' . $sHref, $sMedia);
        return $this;
    }

    public function appendLibrary($sLibrary, $sHref, $sMedia = null)
    {
        self::$_arsCssList[] = array('/lib/' . $sLibrary . '/css/' . $sHref, $sMedia);
        return $this;
    }

    public function clean()
    {
        self::$_arsCssList = array();
        return $this;
    }

    public function prependFile($sHref, $sMedia = null)
    {
        $arsCss = array('/css/' . $sHref, $sMedia);
        array_unshift(self::$_arsCssList, $arsCss);
        return $this;
    }

    public function prependLibrary($sLibrary, $sHref, $sMedia = null)
    {
        self::$_arsCssList[] = array('/lib/' . $sLibrary . '/css/' . $sHref, $sMedia);
        return $this;
    }

    public function render()
    {
        $sHtml = '';
        foreach (self::$_arsCssList as $arsCss) {
            $sHtml .= '<link href="' . $arsCss[0] . '" rel="stylesheet" type="text/css"';
            if (!empty($arsCss[1])) {
                $sHtml .= ' media="' . $arsCss[1] . '"';
            }
            $sHtml .= '>' . PHP_EOL;
        }
        return $sHtml;
    }
}
