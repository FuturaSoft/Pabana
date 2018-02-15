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

/**
 * Script class
 *
 * Add script to Html
 */
class Script
{
    /**
     * @var     array List of defined script
     * @since   1.0.0
     */
    private static $_arsScriptList = array();

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0.0
     * @return  string Html code to initialize scripts
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Append
     *
     * Append a script to script list with it complet path
     *
     * @since   1.0.0
     * @param   string $sHref Complet path of script.
     * @return  $this
     */
    public function append($sHref)
    {
        self::$_arsScriptList[] = array($sHref);
        return $this;
    }

    /**
     * Append file
     *
     * Append a script localized in public/js folder to script list
     *
     * @since   1.0.0
     * @param   string $sHref Script name.
     * @return  $this
     */
    public function appendFile($sHref)
    {
        self::$_arsScriptList[] = array('/js/' . $sHref);
        return $this;
    }

    /**
     * Append library
     *
     * Append a script localized in public/lib/library_name/js/ folder to script list
     *
     * @since   1.0.0
     * @param   string $sLibrary Library name.
     * @param   string $sHref Script name.
     * @return  $this
     */
    public function appendLibrary($sLibrary, $sHref)
    {
        self::$_arsScriptList[] = array('/lib/' . $sLibrary . '/js/' . $sHref);
        return $this;
    }

    /**
     * Clean
     *
     * Clean list of script
     *
     * @since   1.0.0
     * @return  $this
     */
    public function clean()
    {
        self::$_arsScriptList = array();
        return $this;
    }

    /**
     * Prepend
     *
     * Prepend a script to script list with it complet path
     *
     * @since   1.0.0
     * @param   string $sHref Complet path of script.
     * @return  $this
     */
    public function prepend($sHref)
    {
        $arsScript = array($sHref);
        array_unshift(self::$_arsScriptList, $arsScript);
        return $this;
    }

    /**
     * Prepend file
     *
     * Prepend a script localized in public/js folder to script list
     *
     * @since   1.0.0
     * @param   string $sHref Script name.
     * @return  $this
     */
    public function prependFile($sHref)
    {
        $arsScript = array('/js/' . $sHref);
        array_unshift(self::$_arsScriptList, $arsScript);
        return $this;
    }

    /**
     * Prepend library
     *
     * Prepend a script localized in public/lib/library_name/js/ folder to script list
     *
     * @since   1.0.0
     * @param   string $sLibrary Library name.
     * @param   string $sHref Script name.
     * @return  $this
     */
    public function prependLibrary($sLibrary, $sHref)
    {
        $arsScript = array('/lib/' . $sLibrary . '/js/' . $sHref);
        array_unshift(self::$_arsScriptList, $arsScript);
        return $this;
    }

    /**
     * Render
     *
     * Return HTML code for initialize all script in script list
     *
     * @since   1.0.0
     * @return  string Html code to initialize scripts
     */
    public function render()
    {
        $sHtml = '';
        foreach (self::$_arsScriptList as $arsScript) {
            $sHtml .= '<script src="' . $arsScript[0] . '" type="text/javascript"></script>' . PHP_EOL;
        }
        return $sHtml;
    }
}
