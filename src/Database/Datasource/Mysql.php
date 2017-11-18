<?php
namespace Pabana\Database\Datasource;

use Pabana\Database\Datasource;
use Pabana\Debug\Error;

class Mysql extends Datasource
{
    private $sHost;
    private $nPort = 3306;
    private $sUnixSocket;
    
    public function construct($sCnxName)
    {
        $this->setName($sCnxName);
        $this->setDbms('Mysql');
    }

    protected function checkParam()
    {
        if(empty($this->getHost()) && empty($this->getUnixSocket())) {
            $sErrorMessage = 'Connexion to Mysql must have an host or an unix socket defined';
            throw new Error($sErrorMessage);
            return false;
        } else {
            return true;
        }
    }

    public function getDsn()
    {
        if($this->checkParam()) {
            if(!empty($this->getHost())) {
                $sDsn = 'mysql:host=' . $this->getHost() . ';';
                if(!empty($this->getPort())) {
                    $sDsn .= 'port=' . $this->getPort() . ';';
                }
            } else {
                $sDsn = 'mysql:unixsocket=' . $this->getUnixSocket() . ';';
            }
            if(!empty($this->getDatabase())) {
                $sDsn .= 'dbname=' . $this->getDatabase() . ';';
            }
            if(!empty($this->getCharset())) {
                $sDsn .= 'charset=' . $this->getCharset() . ';';
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

    public function getUnixSocket()
    {
        return $this->sUnixSocket;
    }

    public function setHost($sHost)
    {
        $this->sHost = $sHost;
        return $this;
    }

    public function setPort($nPort)
    {
        $this->nPort = $nPort;
        return $this;
    }

    public function setUnixSocket($sUnixSocket)
    {
        $this->sUnixSocket = $sUnixSocket;
        return $this;
    }
}