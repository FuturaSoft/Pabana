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
 * Title class
 *
 * Define title use
 */
class Title
{
    /**
     * @var     array Title of page.
     * @since   1.0.0
     */
    private static $_arsTitle = array();

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0.0
     * @return  string Html code for Title
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Append
     *
     * Append string to title value
     *
     * @since   1.0.0
     * @param   string $sTitle Title of page
     * @return  $this
     */
    public function append($sTitle)
    {
        self::$_arsTitle[] = $sTitle;
        return $this;
    }

    /**
     * Clean
     *
     * Clean all element define in title
     *
     * @since   1.0.0
     * @return  $this
     */
    public function clean()
    {
        self::$_arsTitle = array();
        return $this;
    }

    /**
     * Prepend
     *
     * Prepend string to title value
     *
     * @since   1.0.0
     * @param   string $sTitle Title of page
     * @return  $this
     */
    public function prepend($sTitle)
    {
        array_unshift(self::$_arsTitle, $sTitle);
        return $this;
    }

    /**
     * Render
     *
     * Return HTML code for Title
     *
     * @since   1.0.0
     * @return  string|boolean Html code for Title or false is empty
     */
    public function render()
    {
        if (!empty(self::$_arsTitle)) {
            return '<title>' . implode('', self::$_arsTitle) . '</title>' . PHP_EOL;
        } else {
            return false;
        }
    }

    /**
     * Set
     *
     * Set title tag value
     *
     * @since   1.0.0
     * @param   string $sTitle Title of page
     * @return  $this
     */
    public function set($sTitle)
    {
        $this->clean();
        return $this->append($sTitle);
    }
}