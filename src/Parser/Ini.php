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

class Ini
{
    private $sFilename;

    public function load($sFilename)
    {
        if (!file_exists($sFilename)) {
            throw new \Exception('Ini file "' . $sFilename . '" doesn\'t exist.');
            return false;
        }
        $this->sFilename = $sFilename;
        return $this;
    }

    public function toArray($bProcessSection = false)
    {
        return parse_ini_file($this->sFilename, $bProcessSection, INI_SCANNER_TYPED);
    }
}
