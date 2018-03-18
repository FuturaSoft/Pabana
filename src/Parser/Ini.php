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
namespace Pabana\Parser;

/**
 * Ini class
 *
 * Parse Ini file
 */
class Ini
{
    /**
     * @var    string Path of ini file
     * @since   1.0
     */
    private $filename;

    /**
     * Load INI file
     *
     * @since   1.0
     * @param   string $filename File path.
     * @return  bool|$this
     */
    public function load($filename)
    {
        if (!file_exists($filename)) {
            throw new \Exception('Ini file "' . $filename . '" doesn\'t exist.');
            return false;
        }
        $this->filename = $filename;
        return $this;
    }

    /**
     * Transforme INI content to Array
     *
     * @since   1.0
     * @param   bool $processSection Process to array with section.
     * @return  array|bool Array of content of INI or false if error
     */
    public function toArray($processSection = false)
    {
        return parse_ini_file($this->filename, $processSection, INI_SCANNER_TYPED);
    }
}
