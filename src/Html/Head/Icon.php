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
 * Icon class
 *
 * Add icon to Html
 */
class Icon
{
    /**
     * @var     Pabana\Type\ArrayType List of defined icon
     * @since   1.0
     */
    private static $iconList;

    /**
     * Constructor
     *
     * @since   1.1
     */
    public function __construct()
    {
        self::$iconList = new ArrayType();
    }

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0
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
     * @since   1.0
     * @param   string $href Icon path.
     * @return  $this
     */
    public function append($href)
    {
        $href = '/img/' . $href;
        self::$iconList->append(array($href));
        return $this;
    }

    /**
     * Clean list of icon
     *
     * @since   1.0
     * @return  $this
     */
    public function clean()
    {
        self::$iconList->clean();
        return $this;
    }

    /**
     * Get a value of an item in icon list
     *
     * @since   1.1
     * @param   int $index Index of item position
     * @return  array Return a value of icon list
     */
    public function get($index)
    {
        return self::$iconList->get($index);
    }

    /**
     * Insert an icon to defined position in icon list
     *
     * @since   1.1
     * @param   int $index Index of insert position
     * @param   string $href Path of icon.
     * @return  $this
     */
    private function insert($index, $href)
    {
        $href = '/img/' . $href;
        self::$iconList->insert($index, array($href));
        return $this;
    }

    /**
     * Prepend a icon localized in public/img/ folder to icon list
     *
     * @since   1.0
     * @param   string $href Icon name.
     * @return  $this
     */
    public function prepend($href)
    {
        $href = '/img/' . $href;
        self::$iconList->prepend(array($href));
        return $this;
    }

    /**
     * Remove an icon from icon list
     *
     * @since   1.1
     * @param   int $index Index of icon
     * @return  bool True if remove success, else false.
     */
    public function remove($index)
    {
        return self::$iconList->remove($index);
    }

    /**
     * Render
     *
     * Return HTML code for initialize all icon in icon list
     *
     * @since   1.0
     * @return  string Html code to initialize icon
     */
    public function render()
    {
        $htmlContent = '';
        foreach (self::$iconList->toArray() as $icon) {
            $htmlContent .= '<link href="' . $icon[0] . '" rel="icon">' . PHP_EOL;
        }
        return $htmlContent;
    }
}
