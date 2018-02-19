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

/**
 * Connection class
 *
 * Defined a connection to a database from a datasource
 */
class Connection
{
    /**
     * @var     \Pabana\Database\Datasource Object defined a datasource and its parameters
     * @since   1.0.0
     */
    public $Datasource;

    /**
     * @var     \PDO Object PDO created when connection is effectued
     * @since   1.0.0
     */
    private $oPdo;

    /**
     * @var     string Connection name (by default same as Datasource)
     * @since   1.0.0
     */
    private $sName;

    /**
     * Constructor
     *
     * Store Datasource object, define connection name and do connection
     *
     * @since   1.0.0
     * @param   \Pabana\Database\Datasource $oDatasource Object defined a datasource and its parameters.
     * @param   string $sName Connection name.
     * @param   bool $bAutoConnect If defined connection will do.
     */
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

    /**
     * Do a connection
     *
     * Do a connection to a database from datasource parameter (DSN) via PDO object
     *
     * @since   1.0.0
     * @return  bool True if success or false.
     */
    public function connect()
    {
        try {
            $this->oPdo = new \PDO(
                $this->Datasource->getDsn(),
                                   $this->Datasource->getUser(),
                                   $this->Datasource->getPassword(),
                                   $this->Datasource->getOption()
            );
            return true;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
            return false;
        }
    }

    /**
     * Close a connection
     *
     * Close a connection to a database and destroy PDO object
     *
     * @since   1.0.0
     * @return  bool True if success or false.
     */
    public function disconnect()
    {
        // Check if connection is open
        if ($this->isConnected()) {
            // Destroy PDO object
            $this->oPdo = null;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Execute an SQL statement
     *
     * Execute an SQL statement and return the number of affected rows 
     *
     * @since   1.0.0
     * @param   string $sQuery SQL Statement.
     * @return  bool|integer Return an integer with number of affected rows or return false if error.
     */
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

    /**
     * Get PDO Object
     *
     * Return current PDO object use by this connection
     *
     * @since   1.0.0
     * @return  bool|\PDO Return current PDO object use by this connection or return false if error.
     */
    public function getPdoObject()
    {
        if ($this->isConnected()) {
            return $this->oPdo;
        } else {
            return false;
        }
    }

    /**
     * Get Connection name
     *
     * Return current connection name
     *
     * @since   1.0.0
     * @return  string Return current connection name.
     */
    public function getName()
    {
        return $this->sName;
    }

    /**
     * Check if connection is establised
     *
     * @since   1.0.0
     * @return  bool Return true if connection is establised or return false.
     */
    public function isConnected()
    {
        return isset($this->oPdo);
    }

    /**
     * Get last insert ID
     *
     * Returns the ID of the last inserted row or sequence value
     *
     * @since   1.0.0
     * @return  bool|integer Returns the ID of the last inserted row or sequence value
     */
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

    /**
     * Executes an SQL statement and return result
     *
     * Executes an SQL statement, returning a result set as a \Pabana\Database\Statement object 
     *
     * @since   1.0.0
     * @param   string $sQuery SQL Statement.
     * @return  bool|\Pabana\Database\Statement Returns Statement object or false if error
     */
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

    /**
     * Set Connection name
     *
     * @since   1.0.0
     * @param   string $sName Connection name.
     * @return  void
     */
    public function setName($sName)
    {
        $this->sName = $sName;
    }
}