<?php
namespace Pabana\Database\Datasource;

use Pabana\Database\Datasource;
use Pabana\Debug\Error;

class Odbc extends Datasource
{
    private $sDriver = 'Microsoft Access Driver (*.mdb, *.accdb)';
    private $nExclusive;
    private $nExtendedAnsiSql;
    private $nLocaleIdentifier;
    private $sSystemDatabase;

    public function __construct($sName)
    {
        $this->setName($sName);
        $this->setDbms('Odbc');
    }

    protected function checkParam()
    {
        if(empty($this->getDriver())) {
            $sErrorMessage = 'Connexion by Odbc must have a driver defined';
            throw new Error($sErrorMessage);
            return false;
        } else {
            return true;
        }
    }
	
	public function getDsn()
    {
		if($this->checkParam()) {
			$sDsn = 'odbc:Driver={' . $this->getDriver() . '};';
            if(!empty($this->getUser())) {
                $sDsn .= 'Uid=' . $this->getUser() . ';';
            } else {
                $sDsn .= 'Uid=Admin;';
            }
            $sDsn .= 'Pwd=' . $this->getPassword() . ';Dbq=' . $this->getDatabase() . ';';
			if(!empty($this->getSystemDatabase())) {
				$sDsn .= 'SystemDB=' . $this->getSystemDatabase() . ';';
			}
			return $sDsn;
		} else {
			return false;
		}
    }

    public function getDriver()
    {
        return $this->sDriver;
    }
    
    public function getExclusive()
    {
        return $this->nExclusive;
    }
    
    public function getExtendedAnsiSql()
    {
        return $this->nExtendedAnsiSql;
    }
    
    public function getLocaleIdentifier()
    {
        return $this->nLocaleIdentifier;
    }
    
    public function getSystemDatabase()
    {
        return $this->sSystemDatabase;
    }
    
    public function setDriver($sDriver)
    {
        $this->sDriver = $sDriver;
        return $this;
    }
    
    public function setExclusive($nExclusive)
    {
        $this->nExclusive = $nExclusive;
        return $this;
    }
    
    public function setExtendedAnsiSql($nExtendedAnsiSql)
    {
        $this->nExtendedAnsiSql = $nExtendedAnsiSql;
        return $this;
    }
    
    public function setLocaleIdentifier($nLocaleIdentifier)
    {
        $this->nLocaleIdentifier = $nLocaleIdentifier;
        return $this;
    }
    
    public function setSystemDatabase($sSystemDatabase)
    {
        $this->sSystemDatabase = $sSystemDatabase;
        return $this;
    }
}