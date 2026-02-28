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
    private static ArrayType $scriptList;

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
    public function __toString(): string
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
    private function append(string $type, string $hrefOrScript): static
    {
        self::$scriptList->append([$type, $hrefOrScript]);
        return $this;
    }

    /**
     * Append a script localized in public/js folder to script list
     *
     * @since   1.0
     * @param   string $href Script name.
     * @return  $this
     */
    public function appendFile(string $href): static
    {
        $href = '/js/' . $href;
        $this->append('file', $href);
        return $this;
    }

    /**
     * Append a script localized in public/lib/library_name/js/ folder to script list
     *
     * @since   1.0
     * @param   string $library Library name.
     * @param   string $href Script name.
     * @return  $this
     */
    public function appendLibrary(string $library, string $href): static
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
    public function appendScript(string $script): static
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
    public function clean(): static
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
    public function get(int $index): mixed
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
    private function insert(int $index, string $type, string $hrefOrScript): static
    {
        self::$scriptList->insert($index, [$type, $hrefOrScript]);
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
    public function insertFile(int $index, string $href): static
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
    public function insertLibrary(int $index, string $library, string $href): static
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
    public function insertScript(int $index, string $script): static
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
    private function prepend(string $type, string $hrefOrScript): static
    {
        self::$scriptList->prepend([$type, $hrefOrScript]);
        return $this;
    }

    /**
     * Prepend a script localized in public/js folder to script list
     *
     * @since   1.0
     * @param   string $href Script name.
     * @return  $this
     */
    public function prependFile(string $href): static
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
    public function prependLibrary(string $library, string $href): static
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
    public function prependScript(string $script): static
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
    public function remove(int $index): bool
    {
        return self::$scriptList->remove($index);
    }

    /**
     * Return HTML code for initialize all script in script list
     *
     * @since   1.0
     * @return  string Html code to initialize scripts
     */
    public function render(): string
    {
        $htmlContent = '';
        foreach (self::$scriptList->toArray() as $script) {
            if ($script[0] == 'script') {
                $htmlContent .= '<script type="text/javascript">' . $script[1] . '</script>' . PHP_EOL;
            } else {
                $scriptPath = APP_ROOT . '/public' . $script[1];
                if (Configuration::read('html.script.test_file_existance') === true) {
                    if (!file_exists($scriptPath)) {
                        trigger_error('Script file "' . $scriptPath . '" doesn\'t exist.', E_USER_WARNING);
                    }
                }
                if (Configuration::read('html.script.version') === true) {
                    $script[1] .= '?v=' . filemtime($scriptPath);
                }
                $htmlContent .= '<script src="' . $script[1] . '" type="text/javascript"></script>' . PHP_EOL;
            }
        }
        return $htmlContent;
    }
}
