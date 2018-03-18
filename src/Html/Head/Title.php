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
 * @since         1.0
 * @license       https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */
namespace Pabana\Html\Head;

use Pabana\Type\ArrayType;

/**
 * Title class
 *
 * Define title use
 */
class Title
{
    /**
     * @var     Pabana\Type\ArrayType List of defined title
     * @since   1.0
     */
    private static $titleList;

    /**
     * Constructor
     *
     * @since   1.1
     */
    public function __construct()
    {
        self::$titleList = new ArrayType();
    }

    /**
     * Activate the render method
     *
     * @since   1.0
     * @return  string Html code for Title
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Append string to title value
     *
     * @since   1.0
     * @param   string $title Title of page
     * @return  $this
     */
    public function append($title)
    {
        self::$titleList->append($title);
        return $this;
    }

    /**
     * Clean all element define in title
     *
     * @since   1.0
     * @return  void
     */
    public function clean()
    {
        self::$titleList->clean();
    }

    /**
     * Prepend string to title value
     *
     * @since   1.0
     * @param   string $title Title of page
     * @return  $this
     */
    public function prepend($title)
    {
        self::$titleList->prepend($title);
        return $this;
    }

    /**
     * Return HTML code for Title
     *
     * @since   1.0
     * @return  string|boolean Html code for Title or false is empty
     */
    public function render()
    {
        if (!empty(self::$titleList->toArray())) {
            return '<title>' . implode('', self::$titleList->toArray()) . '</title>' . PHP_EOL;
        } else {
            return false;
        }
    }

    /**
     * Set title tag value
     *
     * @since   1.0
     * @param   string $title Title of page
     * @return  $this
     */
    public function set($title)
    {
        $this->clean();
        return $this->append($title);
    }
}
