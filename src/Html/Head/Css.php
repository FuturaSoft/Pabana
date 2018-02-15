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

/**
 * Script class
 *
 * Add css to Html
 */
class Css
{
    /**
     * @var     array List of defined css
     * @since   1.0.0
     */
    private static $_arsCssList = array();

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0.0
     * @return  string Html code to initialize css
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Append file
     *
     * Append a css localized in public/css folder to css list
     *
     * @since   1.0.0
     * @param   string $sHref Css file name.
     * @return  $this
     */
    public function appendFile($sHref, $sMedia = null)
    {
        self::$_arsCssList[] = array('/css/' . $sHref, $sMedia);
        return $this;
    }

    /**
     * Append library
     *
     * Append a css localized in public/lib/library_name/css/ folder to css list
     *
     * @since   1.0.0
     * @param   string $sLibrary Library name.
     * @param   string $sHref Css file name.
     * @return  $this
     */
    public function appendLibrary($sLibrary, $sHref, $sMedia = null)
    {
        self::$_arsCssList[] = array('/lib/' . $sLibrary . '/css/' . $sHref, $sMedia);
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
        self::$_arsCssList = array();
        return $this;
    }

    /**
     * Prepend file
     *
     * Prepend a css localized in public/css folder to css list
     *
     * @since   1.0.0
     * @param   string $sHref Css file name.
     * @return  $this
     */
    public function prependFile($sHref, $sMedia = null)
    {
        $arsCss = array('/css/' . $sHref, $sMedia);
        array_unshift(self::$_arsCssList, $arsCss);
        return $this;
    }

    /**
     * Prepend library
     *
     * Prepend a css localized in public/lib/library_name/css/ folder to css list
     *
     * @since   1.0.0
     * @param   string $sLibrary Library name.
     * @param   string $sHref Css file name.
     * @return  $this
     */
    public function prependLibrary($sLibrary, $sHref, $sMedia = null)
    {
        self::$_arsCssList[] = array('/lib/' . $sLibrary . '/css/' . $sHref, $sMedia);
        return $this;
    }

    /**
     * Render
     *
     * Return HTML code for initialize all css file in css list
     *
     * @since   1.0.0
     * @return  string Html code to initialize css file
     */
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
