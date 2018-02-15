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
 * Link class
 *
 * Add link to Html
 */
class Link
{
    /**
     * @var     array List of defined link
     * @since   1.0.0
     */
    private static $_arsLinkList = array();

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0.0
     * @return  string Html code to initialize link
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Append
     *
     * Append a link
     *
     * @since   1.0.0
     * @param   string $sHref Link path.
     * @param   string $sRel Rel attribute.
     * @param   string $sType Type attribute.
     * @param   string $sMedia Media attribute.
     * @return  $this
     */
    public function append($sHref, $sRel = null, $sType = null, $sMedia = null)
    {
        self::$_arsLinkList[] = array($sHref, $sRel, $sType, $sMedia);
        return $this;
    }

    /**
     * Clean
     *
     * Clean list of link
     *
     * @since   1.0.0
     * @return  $this
     */
    public function clean()
    {
        self::$_arsLinkList = array();
        return $this;
    }

    /**
     * Prepend
     *
     * Prepend a link
     *
     * @since   1.0.0
     * @param   string $sHref Link path.
     * @param   string $sRel Rel attribute.
     * @param   string $sType Type attribute.
     * @param   string $sMedia Media attribute.
     * @return  $this
     */
    public function prepend($sHref, $sRel = null, $sType = null, $sMedia = null)
    {
        $arsLink = array($sHref, $sRel, $sType, $sMedia);
        array_unshift(self::$_arsLinkList, $arsLink);
        return $this;
    }

    /**
     * Render
     *
     * Return HTML code for initialize all link in link list
     *
     * @since   1.0.0
     * @return  string Html code to initialize link
     */
    public function render()
    {
        $sHtml = '';
        foreach (self::$_arsLinkList as $arsLink) {
            $sHtml .= '<link href="' . $arsLink[0] . '"';
            if (!empty($arsLink[1])) {
                $sHtml .= ' rel="' . $arsLink[1] . '"';
            }
            if (!empty($arsLink[2])) {
                $sHtml .= ' type="' . $arsLink[2] . '"';
            }
            if (!empty($arsLink[3])) {
                $sHtml .= ' media="' . $arsLink[3] . '"';
            }
            $sHtml .= '>' . PHP_EOL;
        }
        return $sHtml;
    }
}