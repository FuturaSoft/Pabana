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
namespace Pabana\Database;

use Pabana\Database\Statement;

class Connection
{
    public $Datasource;
    private $oPdo;
    private $sName;

    public function __construct($oDatasource, $sName = '', $bAutoConnect = true)
    {
        $this->Datasource = $oDatasource;
        if (empty($sName) === true) {
            $sName = $this->Datasource->getName();
        }
        $this->setName($sName);
        if ($bAutoConnect === true) {
            $this->connect();
        }
    }

    public function connect()
    {
        try {
            $this->oPdo = new \PDO(
                $this->Datasource->getDsn(),
                                   $this->Datasource->getUser(),
                                   $this->Datasource->getPassword(),
                                   $this->Datasource->getOption()
            );
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
            return false;
        }
    }

    public function disconnect()
    {
        if ($this->isConnected()) {
            $this->oPdo = null;
            return true;
        } else {
            return false;
        }
    }

    public function exec($sQuery)
    {
        if ($this->isConnected()) {
            try {
                return $this->oPdo->exec($sQuery);
            } catch (PDOException $e) {
                throw new \Exception($e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

    public function getPdoObject()
    {
        if ($this->isConnected()) {
            return $this->oPdo;
        } else {
            return false;
        }
    }

    public function getName()
    {
        return $this->sName;
    }

    public function isConnected()
    {
        return isset($this->oPdo);
    }

    public function lastInsertId()
    {
        if ($this->isConnected()) {
            try {
                return $this->oPdo->lastInsertId();
            } catch (PDOException $e) {
                throw new \Exception($e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

    public function query($sQuery)
    {
        if ($this->isConnected()) {
            try {
                $oStatement = $this->oPdo->query($sQuery);
                return new Statement($oStatement);
            } catch (PDOException $e) {
                throw new \Exception($e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

    public function setName($sName)
    {
        $this->sName = $sName;
    }
}
