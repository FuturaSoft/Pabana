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
namespace Pabana\Html;

class Script
{
    private static $_arsScriptList = array();

    public function __toString()
    {
        return $this->render();
    }

    public function append($sHref)
    {
        self::$_arsScriptList[] = array($sHref);
        return $this;
    }

    public function appendFile($sHref)
    {
        self::$_arsScriptList[] = array('/js/' . $sHref);
        return $this;
    }

    public function appendLibrary($sLibrary, $sHref)
    {
        self::$_arsScriptList[] = array('/lib/' . $sLibrary . '/js/' . $sHref);
        return $this;
    }

    public function clean()
    {
        self::$_arsScriptList = array();
        return $this;
    }

    public function prepend($sHref)
    {
        $arsScript = array($sHref);
        array_unshift(self::$_arsScriptList, $arsScript);
        return $this;
    }

    public function prependFile($sHref)
    {
        $arsScript = array('/js/' . $sHref);
        array_unshift(self::$_arsScriptList, $arsScript);
        return $this;
    }

    public function prependLibrary($sLibrary, $sHref)
    {
        $arsScript = array('/lib/' . $sLibrary . '/js/' . $sHref);
        array_unshift(self::$_arsScriptList, $arsScript);
        return $this;
    }

    public function render()
    {
        $sHtml = '';
        foreach (self::$_arsScriptList as $arsScript) {
            $sHtml .= '<script src="' . $arsScript[0] . '" type="text/javascript"></script>' . PHP_EOL;
        }
        return $sHtml;
    }
}
