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
 * Link class
 *
 * Add link to Html
 */
class Link
{
    /**
     * @var     Pabana\Type\ArrayType List of defined link
     * @since   1.0
     */
    private static ArrayType $linkList;

    /**
     * Constructor
     *
     * @since   1.1
     */
    public function __construct()
    {
        self::$linkList = new ArrayType();
    }

    /**
     * Activate the render method
     *
     * @since   1.0
     * @return  string Html code to initialize link
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Append a link
     *
     * @since   1.0
     * @param   string $href Link path.
     * @param   string $rel Rel attribute.
     * @param   string $type Type attribute.
     * @param   string $media Media attribute.
     * @return  $this
     */
    public function append(string $href, ?string $rel = null, ?string $type = null, ?string $media = null): static
    {
        self::$linkList->append([$href, $rel, $type, $media]);
        return $this;
    }

    /**
     * Clean list of link
     *
     * @since   1.0
     * @return  void
     */
    public function clean(): void
    {
        self::$linkList->clean();
    }

    /**
     * Get a value of an item in link list
     *
     * @since   1.1
     * @param   int $index Index of item position
     * @return  array Return a value of link list
     */
    public function get(int $index): mixed
    {
        return self::$linkList->get($index);
    }

    /**
     * Insert an icon to defined position in icon list
     *
     * @since   1.1
     * @param   int $index Index of insert position
     * @param   string $href Link path.
     * @param   string $rel Rel attribute.
     * @param   string $type Type attribute.
     * @param   string $media Media attribute.
     * @return  $this
     */
    private function insert(int $index, string $href, ?string $rel = null, ?string $type = null, ?string $media = null): static
    {
        self::$linkList->insert($index, [$href, $rel, $type, $media]);
        return $this;
    }

    /**
     * Prepend a link
     *
     * @since   1.0
     * @param   string $href Link path.
     * @param   string $rel Rel attribute.
     * @param   string $type Type attribute.
     * @param   string $media Media attribute.
     * @return  $this
     */
    public function prepend(string $href, ?string $rel = null, ?string $type = null, ?string $media = null): static
    {
        self::$linkList->prepend([$href, $rel, $type, $media]);
        return $this;
    }

    /**
     * Remove a link from link list
     *
     * @since   1.1
     * @param   int $index Index of link
     * @return  bool True if remove success, else false.
     */
    public function remove(int $index): bool
    {
        return self::$linkList->remove($index);
    }

    /**
     * Return HTML code for initialize all link in link list
     *
     * @since   1.0
     * @return  string Html code to initialize link
     */
    public function render(): string
    {
        $htmlContent = '';
        foreach (self::$linkList->toArray() as $link) {
            $htmlContent .= '<link href="' . $link[0] . '"';
            if (!empty($link[1])) {
                $htmlContent .= ' rel="' . $link[1] . '"';
            }
            if (!empty($link[2])) {
                $htmlContent .= ' type="' . $link[2] . '"';
            }
            if (!empty($link[3])) {
                $htmlContent .= ' media="' . $link[3] . '"';
            }
            $htmlContent .= '>' . PHP_EOL;
        }
        return $htmlContent;
    }
}
