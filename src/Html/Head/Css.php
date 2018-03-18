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

use Pabana\Core\Configuration;
use Pabana\Type\ArrayType;

/**
 * css class
 *
 * Add css to Html
 */
class Css
{
    /**
     * @var     Pabana\Type\ArrayType List of defined css
     * @since   1.0
     */
    private static $cssList;

    /**
     * Constructor
     *
     * @since   1.1
     */
    public function __construct()
    {
        self::$cssList = new ArrayType();
    }

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0
     * @return  string Html code to initialize css
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Append a css to css list
     *
     * @since   1.1
     * @param   string $type Type of css (file, library or css)
     * @param   string $hrefOrCss Path of css from /public or css content.
     * @return  $this
     */
    private function append($type, $hrefOrCss)
    {
        self::$cssList->append(array($type, $hrefOrCss));
        return $this;
    }

    /**
     * Append a css code give in argument
     *
     * @since   1.1
     * @param   string $css Css code.
     * @return  $this
     */
    public function appendCss($css)
    {
        $this->append('css', $css);
        return $this;
    }

    /**
     * Append a css localized in public/css folder to css list
     *
     * @since   1.0
     * @param   string $href Css file name.
     * @return  $this
     */
    public function appendFile($href)
    {
        $href = '/css/' . $href;
        $this->append('file', $href);
        return $this;
    }

    /**
     * Append a css localized in public/lib/library_name/css/ folder to css list
     *
     * @since   1.0
     * @param   string $library Library name.
     * @param   string $href Css file name.
     * @return  $this
     */
    public function appendLibrary($library, $href)
    {
        $href = '/lib/' . $library . '/css/' . $href;
        $this->append('library', $href);
        return $this;
    }

    /**
     * Clean list of css
     *
     * @since   1.0
     * @return  $this
     */
    public function clean()
    {
        self::$cssList->clean();
        return $this;
    }

    /**
     * Get a value of css List
     *
     * @since   1.1
     * @param   int $index Index of insert position
     * @return  array Return a value of cssList
     */
    public function get($index)
    {
        return self::$cssList->get($index);
    }

    /**
     * Insert a css to css list
     *
     * @since   1.1
     * @param   int $index Index of insert position
     * @param   string $type Type of css (file, library or css)
     * @param   string $hrefOrCss Path of css from /public or css content.
     * @return  $this
     */
    private function insert($index, $type, $hrefOrCss)
    {
        self::$cssList->insert($index, array($type, $hrefOrCss));
        return $this;
    }

    /**
     * Insert a css give in argument
     *
     * @since   1.1
     * @param   int $index Index of insert position
     * @param   string $css css code.
     * @return  $this
     */
    public function insertCss($index, $css)
    {
        $this->insert($index, 'css', $css);
        return $this;
    }

    /**
     * Insert a css localized in public/css folder to css list
     *
     * @since   1.1
     * @param   int $index Index of insert position
     * @param   string $href css name.
     * @return  $this
     */
    public function insertFile($index, $href)
    {
        $href = '/css/' . $href;
        $this->insert($index, 'file', $href);
        return $this;
    }

    /**
     * Insert a css localized in public/lib/library_name/css/ folder to css list
     *
     * @since   1.1
     * @param   int $index Index of insert position
     * @param   string $library Library name.
     * @param   string $href css name.
     * @return  $this
     */
    public function insertLibrary($index, $library, $href)
    {
        $href = '/lib/' . $library . '/css/' . $href;
        $this->insert($index, 'library', $href);
        return $this;
    }

    /**
     * Prepend a css to css list
     *
     * @since   1.1
     * @param   string $type Type of css (file, library or css)
     * @param   string $hrefOrCss Path of css from /public or css content.
     * @return  $this
     */
    private function prepend($type, $hrefOrCss)
    {
        self::$cssList->prepend(array($type, $hrefOrCss));
        return $this;
    }

    /**
     * Prepend a css give in argument
     *
     * @since   1.1
     * @param   string $css css code.
     * @return  $this
     */
    public function prependCss($css)
    {
        $this->prepend('css', $css);
        return $this;
    }

    /**
     * Prepend a css localized in public/css folder to css list
     *
     * @since   1.0
     * @param   string $href css name.
     * @return  $this
     */
    public function prependFile($href)
    {
        $href = '/css/' . $href;
        $this->prepend('file', $href);
        return $this;
    }

    /**
     * Prepend a css localized in public/lib/library_name/css/ folder to css list
     *
     * @since   1.0
     * @param   string $library Library name.
     * @param   string $href css name.
     * @return  $this
     */
    public function prependLibrary($library, $href)
    {
        $href = '/lib/' . $library . '/css/' . $href;
        $this->prepend('library', $href);
        return $this;
    }

    /**
     * Remove a css to css list
     *
     * @since   1.1
     * @param   int $index Index of css
     * @return  bool True if remove success, else false.
     */
    public function remove($index)
    {
        return self::$cssList->remove($index);
    }

    /**
     * Render
     *
     * Return HTML code for initialize all css file in css list
     *
     * @since   1.0
     * @return  string Html code to initialize css file
     */
    public function render()
    {
        $htmlContent = '';
        foreach (self::$cssList->toArray() as $css) {
            if ($css[0] == 'css') {
                $htmlContent .= '<style type="text/css">' . $css[1] . '</script>' . PHP_EOL;
            } else {
                if (Configuration::read('html.css.test_file_existance') === true) {
                    $cssPath = APP_ROOT . '/public' . $css[1];
                    if (!file_exists($cssPath)) {
                        trigger_error('Css file "' . $cssPath . '" doesn\'t exist.', E_USER_WARNING);
                    }
                }
                $htmlContent .= '<link href="' . $css[1] . '" rel="stylesheet" type="text/css">' . PHP_EOL;
            }
        }
        return $htmlContent;
    }
}
