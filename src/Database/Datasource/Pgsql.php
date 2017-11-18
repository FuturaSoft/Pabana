<?php
namespace Pabana\Database\Datasource;

use Pabana\Database\Datasource;
use Pabana\Debug\Error;

class Pgsql extends Datasource {
    private $sHost;
    private $nPort = 5432;
    
    public function __construct($sName)
    {
        $this->setName($sName);
        $this->setDbms('Pgsql');
    }
    
    protected function checkParam()
    {
        if(empty($this->getHost())) {
            $sErrorMessage = 'Connexion to PostgreSQL must have an host defined';
            throw new Error($sErrorMessage);
            return false;
        } else {
            return true;
        }
	}
    
    public function getDsn()
    {
        if($this->checkParam()) {
            $sDsn = 'pgsql:host=' . $this->getHost() . ';';
            if(!empty($this->getPort())) {
                $sDsn .= 'port=' . $this->getPort() . ';';
            }
            if(!empty($this->getDatabase())) {
                $sDsn .= 'dbname=' . $this->getDatabase() . ';';
            }
            return $sDsn;
        } else {
            return false;
        }
    }
    
    public function getHost()
    {
        return $this->sHost;
    }
    
    public function getPort()
    {
        return $this->nPort;
    }
    
    public function setHost($sHost)
    {
        $this->sHost = $sHost;
    }
    
    public function setPort($nPort)
    {
        $this->nPort = $nPort;
    }
}