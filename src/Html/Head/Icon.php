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
 * Icon class
 *
 * Add icon to Html
 */
class Icon
{
    /**
     * @var     array List of defined icon
     * @since   1.0.0
     */
    private static $_arsIconList = array();

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0.0
     * @return  string Html code to initialize icon
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Append file
     *
     * Append a icon localized in public/img/ folder to icon list
     *
     * @since   1.0.0
     * @param   string $sHref Icon name.
     * @return  $this
     */
    public function append($sHref)
    {
        self::$_arsIconList[] = array('/img/' . $sHref);
        return $this;
    }

    /**
     * Clean
     *
     * Clean list of icon
     *
     * @since   1.0.0
     * @return  $this
     */
    public function clean()
    {
        self::$_arsIconList = array();
        return $this;
    }

    /**
     * Prepend file
     *
     * Prepend a icon localized in public/img/ folder to icon list
     *
     * @since   1.0.0
     * @param   string $sHref Icon name.
     * @return  $this
     */
    public function prepend($sHref)
    {
        $arsIcon = array('/img/' . $sHref);
        array_unshift(self::$_arsIconList, $arsIcon);
        return $this;
    }

    /**
     * Render
     *
     * Return HTML code for initialize all icon in icon list
     *
     * @since   1.0.0
     * @return  string Html code to initialize icon
     */
    public function render()
    {
        $sHtml = '';
        foreach (self::$_arsIconList as $arsIcon) {
            $sHtml .= '<link href="' . $arsIcon[0] . '" rel="icon">' . PHP_EOL;
        }
        return $sHtml;
    }
}
