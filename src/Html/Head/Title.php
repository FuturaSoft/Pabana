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

class Title
{
    private static $_arsTitle = array();

    public function __toString()
    {
        return $this->render();
    }

    public function append($sTitle)
    {
        self::$_arsTitle[] = $sTitle;
        return $this;
    }

    public function clean()
    {
        self::$_arsTitle = array();
        return $this;
    }

    public function prepend($sTitle)
    {
        array_unshift(self::$_arsTitle, $sTitle);
        return $this;
    }

    public function render()
    {
        if (!empty(self::$_arsTitle)) {
            return '<title>' . implode('', self::$_arsTitle) . '</title>' . PHP_EOL;
        } else {
            return false;
        }
    }

    public function set($sTitle)
    {
        $this->clean();
        return $this->append($sTitle);
    }
}
