<?php
namespace Pabana\Database\Datasource;

use Pabana\Database\Datasource;
use Pabana\Debug\Error;

class Sqlite extends Datasource
{
    private $bCnxMemory = false;
    
    public function __construct($sName)
    {
        $this->setName($sName);
        $this->setDbms('Sqlite');
    }
    
    protected function checkParam()
    {
        if(empty($this->getDatabase()) && $this->getMemory() === false) {
            $sErrorMessage = 'Connexion to SQLite must have a database defined or be in memory';
            throw new Error($sErrorMessage);
            return false;
        } else {
            return true;
        }
	}
    
    public function createDsn()
    {
        if($this->checkParam()) {
            $sDsn = 'sqlite:';
            if($this->getMemory() === true) {
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