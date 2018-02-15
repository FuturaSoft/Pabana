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
     * @since   1.0.0
     */
    private $sFilename;

    /**
     * Load INI file
     *
     * @since   1.0.0
     * @param   string $sFilename File path.
     * @return  bool|$this
     */
    public function load($sFilename)
    {
        if (!file_exists($sFilename)) {
            throw new \Exception('Ini file "' . $sFilename . '" doesn\'t exist.');
            return false;
        }
        $this->sFilename = $sFilename;
        return $this;
    }

    /**
     * Transforme INI content to Array
     *
     * @since   1.0.0
     * @param   bool $bProcessSection Process to array with section.
     * @return  array|bool Array of content of INI or false if error
     */
    public function toArray($bProcessSection = false)
    {
        return parse_ini_file($this->sFilename, $bProcessSection, INI_SCANNER_TYPED);
    }
}