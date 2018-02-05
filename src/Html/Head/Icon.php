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

class Icon
{
    private static $_arsIconList = array();

    public function __toString()
    {
        return $this->render();
    }

    public function append($sHref)
    {
        self::$_arsIconList[] = array('/img/' . $sHref);
        return $this;
    }

    public function clean()
    {
        self::$_arsIconList = array();
        return $this;
    }

    public function prepend($sHref)
    {
        $arsIcon = array('/img/' . $sHref);
        array_unshift(self::$_arsIconList, $arsIcon);
        return $this;
    }

    public function render()
    {
        $sHtml = '';
        foreach (self::$_arsIconList as $arsIcon) {
            $sHtml .= '<link href="' . $arsIcon[0] . '" rel="icon">' . PHP_EOL;
        }
        return $sHtml;
    }
}
