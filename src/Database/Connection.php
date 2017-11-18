<?php
namespace Pabana\Database;

use Pabana\Database\ConnectionCollection;
use Pabana\Database\Statement;
use Pabana\Debug\Error;

class Connection
{
    public $Datasource = null;
	private $oPdo = null;

    public function __construct($oDatasource, $bAutoConnect = true, $bAutoRegister = true)
    {
        $this->Datasource = $oDatasource;
        if ($bAutoConnect) {
            $this->connect($bAutoRegister);
        }
    }

	public function connect($bAutoRegister = true)
    {
		try {
			$this->oPdo = new \PDO($this->Datasource->getDsn(),
                                   $this->Datasource->getUser(),
                                   $this->Datasource->getPassword(),
                                   $this->Datasource->getOption());
            if ($bAutoRegister) {
                ConnectionCollection::add($this->Datasource->getName(), $this);
            }
		}
        catch (PDOException $e) {
        	throw new Error($e->getMessage());
            return false;
        }
	}

	public function disconnect()
    {
		if($this->isConnected()) {
			$this->oPdo = null;
			return true;
		} else {
            return false;
        }
	}

    public function exec($sQuery)
    {
        if($this->isConnected()) {
            try {
                return $this->oPdo->exec($sQuery);
            }
            catch (PDOException $e) {
                throw new Error($e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

	public function getPdoObject()
    {
		return $this->oPdo;
	}

	public function isConnected()
    {
		return !empty($this->oPdo);
	}

    public function lastInsertId()
    {
        return $this->oPdo->lastInsertId();
    }

	public function query($sQuery)
    {
        if($this->isConnected()) {
            try {
                $oStatement = $this->oPdo->query($sQuery);
                return new Statement($oStatement);
            }
            catch (PDOException $e) {
            	throw new Error($e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }
}