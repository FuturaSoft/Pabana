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
namespace Pabana\Html;

use Pabana\Core\Configuration;
use Pabana\Type\ArrayType;

/**
 * Script class
 *
 * Add script to Html
 */
class Script
{
    /**
     * @var     Pabana\Type\ArrayType List of defined script
     * @since   1.0
     */
    private static $scriptList;

    /**
     * Constructor
     *
     * @since   1.1
     */
    public function __construct()
    {
        self::$scriptList = new ArrayType();
    }

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0
     * @return  string Html code to initialize scripts
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Append a script to script list
     *
     * @since   1.0
     * @param   string $type Type of script (file, library or script)
     * @param   string $hrefOrScript Path of script from /public or script content.
     * @return  $this
     */
    private function append($type, $hrefOrScript)
    {
        self::$scriptList->append(array($type, $hrefOrScript));
        return $this;
    }

    /**
     * Append a script localized in public/js folder to script list
     *
     * @since   1.0
     * @param   string $href Script name.
     * @return  $this
     */
    public function appendFile($href)
    {
        $href = '/js/' . $href;
        $this->append('file', $href);
        return $this;
    }

    /**
     * Append a script localized in public/lib/library_name/js/ folder to script list
     *
     * @since   1.0
     * @param   string $sLibrary Library name.
     * @param   string $sHref Script name.
     * @return  $this
     */
    public function appendLibrary($library, $href)
    {
        $href = '/lib/' . $library . '/js/' . $href;
        $this->append('library', $href);
        return $this;
    }

    /**
     * Append a script give in argument
     *
     * @since   1.1
     * @param   string $script Script code.
     * @return  $this
     */
    public function appendScript($script)
    {
        $this->append('script', $script);
        return $this;
    }

    /**
     * Clean list of script
     *
     * @since   1.0
     * @return  $this
     */
    public function clean()
    {
        self::$scriptList->clean();
        return $this;
    }

    /**
     * Get a value of scriptList
     *
     * @since   1.1
     * @param   int $index Index of insert position
     * @return  array Return a value of scriptList
     */
    public function get($index)
    {
        return self::$scriptList->get($index);
    }

    /**
     * Insert a script to script list
     *
     * @since   1.1
     * @param   int $index Index of insert position
     * @param   string $type Type of script (file, library or script)
     * @param   string $hrefOrScript Path of script from /public or script content.
     * @return  $this
     */
    private function insert($index, $type, $hrefOrScript)
    {
        self::$scriptList->insert($index, array($type, $hrefOrScript));
        return $this;
    }

    /**
     * Insert a script localized in public/js folder to script list
     *
     * @since   1.1
     * @param   int $index Index of insert position
     * @param   string $href Script name.
     * @return  $this
     */
    public function insertFile($index, $href)
    {
        $href = '/js/' . $href;
        $this->insert($index, 'file', $href);
        return $this;
    }

    /**
     * Insert a script localized in public/lib/library_name/js/ folder to script list
     *
     * @since   1.1
     * @param   int $index Index of insert position
     * @param   string $library Library name.
     * @param   string $href Script name.
     * @return  $this
     */
    public function insertLibrary($index, $library, $href)
    {
        $href = '/lib/' . $library . '/js/' . $href;
        $this->insert($index, 'library', $href);
        return $this;
    }

    /**
     * Insert a script give in argument
     *
     * @since   1.1
     * @param   int $index Index of insert position
     * @param   string $script Script code.
     * @return  $this
     */
    public function insertScript($index, $script)
    {
        $this->insert($index, 'script', $script);
        return $this;
    }

    /**
     * Prepend a script to script list
     *
     * @since   1.0
     * @param   string $type Type of script (file, library or script)
     * @param   string $hrefOrScript Path of script from /public or script content.
     * @return  $this
     */
    private function prepend($type, $hrefOrScript)
    {
        self::$scriptList->prepend(array($type, $hrefOrScript));
        return $this;
    }

    /**
     * Prepend a script localized in public/js folder to script list
     *
     * @since   1.0
     * @param   string $href Script name.
     * @return  $this
     */
    public function prependFile($href)
    {
        $href = '/js/' . $href;
        $this->prepend('file', $href);
        return $this;
    }

    /**
     * Prepend a script localized in public/lib/library_name/js/ folder to script list
     *
     * @since   1.0
     * @param   string $library Library name.
     * @param   string $href Script name.
     * @return  $this
     */
    public function prependLibrary($library, $href)
    {
        $href = '/lib/' . $library . '/js/' . $href;
        $this->prepend('library', $href);
        return $this;
    }

    /**
     * Prepend a script give in argument
     *
     * @since   1.1
     * @param   string $script Script code.
     * @return  $this
     */
    public function prependScript($script)
    {
        $this->prepend('script', $script);
        return $this;
    }

    /**
     * Remove a script to script list
     *
     * @since   1.1
     * @param   int $index Index of script
     * @return  bool True if remove success, else false.
     */
    public function remove($index)
    {
        return self::$scriptList->remove($index);
    }

    /**
     * Return HTML code for initialize all script in script list
     *
     * @since   1.0
     * @return  string Html code to initialize scripts
     */
    public function render()
    {
        $htmlContent = '';
        foreach (self::$scriptList->toArray() as $script) {
            if ($script[0] == 'script') {
                $htmlContent .= '<script type="text/javascript">' . $script[1] . '</script>' . PHP_EOL;
            } else {
                if (Configuration::read('html.script.test_file_existance') === true) {
                    $scriptPath = APP_ROOT . '/public' . $script[1];
                    if (!file_exists($scriptPath)) {
                        trigger_error('Script file "' . $scriptPath . '" doesn\'t exist.', E_USER_WARNING);
                    }
                }
                $htmlContent .= '<script src="' . $script[1] . '" type="text/javascript"></script>' . PHP_EOL;
            }
        }
        return $htmlContent;
    }
}
