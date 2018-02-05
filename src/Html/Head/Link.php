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

class Link
{
    private static $_arsLinkList = array();

    public function __toString()
    {
        return $this->render();
    }

    public function append($sHref, $sRel = null, $sType = null, $sMedia = null)
    {
        self::$_arsLinkList[] = array($sHref, $sRel, $sType, $sMedia);
        return $this;
    }

    public function clean()
    {
        self::$_arsLinkList = array();
        return $this;
    }

    public function prepend($sHref, $sRel = null, $sType = null, $sMedia = null)
    {
        $arsLink = array($sHref, $sRel, $sType, $sMedia);
        array_unshift(self::$_arsLinkList, $arsLink);
        return $this;
    }

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
