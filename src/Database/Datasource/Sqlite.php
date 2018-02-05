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
namespace Pabana\Database\Datasource;

use Pabana\Database\Datasource;

class Sqlite extends Datasource
{
    private $bMemory = false;
    
    public function __construct($sName)
    {
        $this->setName($sName);
        $this->setDbms('Sqlite');
    }
    
    protected function checkParam()
    {
        if (empty($this->getDatabase()) && $this->getMemory() === false) {
            $sErrorMessage = 'Connexion to SQLite must have a database defined or be in memory';
            throw new \Exception($sErrorMessage);
            return false;
        } else {
            return true;
        }
    }
    
    public function getDsn()
    {
        if ($this->checkParam()) {
            $sDsn = 'sqlite:';
            if ($this->getMemory() === true) {
                $sDsn .= ':memory:';
            } else {
                $sDsn .= $this->getDatabase();
            }
            return $sDsn;
        } else {
            return false;
        }
    }
    
    public function getMemory()
    {
        return $this->bMemory;
    }
    
    public function setMemory($bCnxMemory)
    {
        $this->bMemory = $bMemory;
    }
}
