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
 * @since         1.0
 * @license       https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */
namespace Pabana\Database;

use Pabana\Core\Configuration;
use Pabana\Database\Statement;

/**
 * Connection class
 *
 * Defined a connection to a database from a datasource
 */
class Connection
{
    /**
     * @var     Redirection to $datasource var
     * @since   1.1
     * @deprecated deprecated since version 1.1
     */
    public $Datasource;

    /**
     * @var     \Pabana\Database\Datasource Object Datasource.
     * @since   1.1
     */
    public $datasource;

    /**
     * @var     \PDO Object PDO created when connection is effectued
     * @since   1.0
     */
    private $pdo;

    /**
     * @var     string Connection name (by default same as Datasource)
     * @since   1.0
     */
    private $name;

    /**
     * Constructor
     *
     * Store Datasource object, define connection name and do connection
     *
     * @since   1.0
     * @param   \Pabana\Database\Datasource $datasource Object defined a datasource and its parameters.
     * @param   string $name Connection name.
     * @param   bool $autoConnect If defined connection will do.
     */
    public function __construct($datasource, $name = '', $autoConnect = true)
    {
        $this->datasource = $datasource;
        // To maintain compatibility with version 1.0
        $this->Datasource = $this->datasource;
        if (empty($name) === true) {
            $name = $this->datasource->getName();
        }
        $this->setName($name);
        if ($autoConnect === true) {
            $this->connect();
        }
    }

    /**
     * Do a connection
     *
     * Do a connection to a database from datasource parameter (DSN) via PDO object
     *
     * @since   1.0
     * @return  bool True if success or false.
     */
    public function connect()
    {
        try {
            $this->pdo = new \PDO(
                $this->datasource->getDsn(),
                $this->datasource->getUser(),
                $this->datasource->getPassword(),
                $this->datasource->getOption()
            );
            if (Configuration::read('debug.level') > 0) {
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
            }
            return true;
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
            return false;
        }
    }

    /**
     * Initiates a transaction
     *
     * @since   1.1
     * @return  bool True if success or false.
     */
    public function beginTransaction()
    {
        // Check if connection is open
        if ($this->isConnected()) {
            $this->pdo->beginTransaction();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Commits a transaction
     *
     * @since   1.1
     * @return  bool True if success or false.
     */
    public function commit()
    {
        // Check if connection is open
        if ($this->isConnected()) {
            $this->pdo->commit();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete a record in table
     *
     * @since   1.2
     * @param   string $table     Table of database
     * @param   array  $dataWhere Array of column => value for where
     * @return  bool
     */
    public function delete($table, $dataWhere = [])
    {
        if ($this->isConnected()) {
            $query = "DELETE FROM `" . $table . "`";
            if (!empty($dataWhere)) {
                foreach ($dataWhere as $column => $value) {
                    $dataProcessedWhereQuery = "`" . $column . "`";
                    if ($value !== '' || $value !== null) {
                        $dataProcessedWhereQuery .= "=" . $this->pdo->quote($value);
                    } else {
                        $dataProcessedWhereQuery .= 'IS NULL';
                    }
                    $dataProcessedWhere[] = $dataProcessedWhereQuery;
                }
                $query .= ' WHERE ' . implode(' AND ', $dataProcessedWhere);
            }
            $query .= ';';
            try {
                return $this->pdo->exec($query);
            } catch (\PDOException $e) {
                throw new \Exception($e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Close a connection
     *
     * Close a connection to a database and destroy PDO object
     *
     * @since   1.0
     * @return  bool True if success or false.
     */
    public function disconnect()
    {
        // Check if connection is open
        if ($this->isConnected()) {
            // Destroy PDO object
            $this->pdo = null;
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
     * @since   1.0
     * @param   string $query SQL Statement.
     * @return  bool|integer Return an integer with number of affected rows or return false if error.
     */
    public function exec($query)
    {
        if ($this->isConnected()) {
            try {
                return $this->pdo->exec($query);
            } catch (\PDOException $e) {
                throw new \Error($e->getMessage());
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
     * @since   1.0
     * @return  bool|\PDO Return current PDO object use by this connection or return false if error.
     */
    public function getPdoObject()
    {
        if ($this->isConnected()) {
            return $this->pdo;
        } else {
            return false;
        }
    }

    /**
     * Get Connection name
     *
     * Return current connection name
     *
     * @since   1.0
     * @return  string Return current connection name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Insert a record in table
     *
     * @since   1.2
     * @param   string $table Table of database
     * @param   array  $data  Array of column => value
     * @return  bool
     */
    public function insert($table, $data)
    {
        if ($this->isConnected()) {
            $query = "INSERT INTO `" . $table . "` SET ";
            $dataProcessed = [];
            foreach ($data as $column => $value) {
                $dataProcessedQuery = "`" . $column . "`=";
                if ($value !== '' || $value !== null) {
                    $dataProcessedQuery .= $this->pdo->quote($value);
                } else {
                    $dataProcessedQuery .= 'NULL';
                }
                $dataProcessed[] = $dataProcessedQuery;
            }
            $query .= implode(',', $dataProcessed);
            $query .= ';';
            try {
                $this->pdo->exec($query);
                return $this->lastInsertId();
            } catch (\PDOException $e) {
                throw new \Exception($e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Check if connection is establised
     *
     * @since   1.0
     * @return  bool Return true if connection is establised or return false.
     */
    public function isConnected()
    {
        return isset($this->pdo);
    }

    /**
     * Check if actual request is transaction
     *
     * @since   1.1
     * @return  bool Return true if actual request is a transaction
     */
    public function isTransaction()
    {
        if ($this->isConnected()) {
            return $this->pdo->inTransaction();
        } else {
            return false;
        }
    }

    /**
     * Get last insert ID
     *
     * Returns the ID of the last inserted row or sequence value
     *
     * @since   1.0
     * @return  bool|integer Returns the ID of the last inserted row or sequence value
     */
    public function lastInsertId()
    {
        if ($this->isConnected()) {
            try {
                return $this->pdo->lastInsertId();
            } catch (\PDOException $e) {
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
     * @since   1.0
     *
     * @param   string $query       SQL Statement
     * @param   array  $dataPrepare Array of data prepare
     *
     * @return  bool|\Pabana\Database\Statement Returns Statement object or false if error
     */
    public function query($query, $dataPrepare = [])
    {
        if ($this->isConnected()) {
            try {
                if (!empty($dataPrepare)) {
                    $statement = $this->pdo->prepare($query);
                    $statement->execute($dataPrepare);
                    return new Statement($statement);
                } else {
                    $statement = $this->pdo->query($query);
                    return new Statement($statement);
                }
            } catch (\PDOException $e) {
                throw new \Exception($e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Execute a query and return all result
     *
     * @since   1.2
     *
     * @param   string $query       SQL Statement
     * @param   array  $dataPrepare Array of data prepare
     * @param   string $fetchType   Type de retour
     *
     * @return  bool|mixed Returns all result or false if error
     */
    public function queryAll($query, $dataPrepare = [], $fetchType = 'assoc')
    {
        if ($this->isConnected()) {
            $statement = $this->query($query, $dataPrepare);
            if ($statement === false) {
                return false;
            }
            return $statement->fetchAll($fetchType);
        } else {
            return false;
        }
    }

    /**
     * Execute a query and return one line
     *
     * @since   1.2
     *
     * @param   string $query       SQL Statement
     * @param   array  $dataPrepare Array of data prepare
     * @param   string $fetchType   Type de retour
     *
     * @return  bool|mixed Returns a line or false if error
     */
    public function queryOne($query, $dataPrepare = [], $fetchType = 'assoc')
    {
        if ($this->isConnected()) {
            $statement = $this->query($query, $dataPrepare);
            if ($statement === false) {
                return false;
            }
            $mResult = $statement->fetch($fetchType);
            if (empty($mResult)) {
                return null;
            }
            return $mResult;
        } else {
            return false;
        }
    }

    /**
     * Execute a query and return one column
     *
     * @since   1.2
     *
     * @param   string $query       SQL Statement
     * @param   array  $dataPrepare Array of data prepare
     * @param   string $fetchType   Type de retour
     *
     * @return  bool|mixed Return one column value or false if error
     */
    public function queryOneColumn($query, $dataPrepare = [])
    {
        if ($this->isConnected()) {
            $statement = $this->query($query, $dataPrepare);
            if ($statement === false) {
                return false;
            }
            $mResult = $statement->fetch('num');
            if (empty($mResult)) {
                return null;
            }
            return $mResult[0];
        } else {
            return false;
        }
    }

    /**
     * Rolls back a transaction
     *
     * @since   1.1
     *
     * @return  bool True if success or false.
     */
    public function rollBack()
    {
        // Check if connection is open
        if ($this->isConnected()) {
            $this->pdo->rollBack();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set Connection name
     *
     * @since   1.0
     *
     * @param   string $name Connection name.
     *
     * @return  void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Update a record in table
     *
     * @since   1.2
     *
     * @param   string $table     Table of database
     * @param   array  $data      Array of column => value
     * @param   array  $dataWhere Array of column => value for where
     *
     * @return  bool
     */
    public function update($table, $data, $dataWhere = [])
    {
        if ($this->isConnected()) {
            $query = "UPDATE `" . $table . "` SET ";
            $dataProcessed = [];
            foreach ($data as $column => $value) {
                $dataProcessedQuery = "`" . $column . "`=";
                if ($value !== '' || $value !== null) {
                    $dataProcessedQuery .= $this->pdo->quote($value);
                } else {
                    $dataProcessedQuery .= 'NULL';
                }
                $dataProcessed[] = $dataProcessedQuery;
            }
            $query .= implode(',', $dataProcessed);
            if (!empty($dataWhere)) {
                foreach ($dataWhere as $column => $value) {
                    $dataProcessedWhereQuery = "`" . $column . "`";
                    if ($value !== '' || $value !== null) {
                        $dataProcessedWhereQuery .= "=" . $this->pdo->quote($value);
                    } else {
                        $dataProcessedWhereQuery .= 'IS NULL';
                    }
                    $dataProcessedWhere[] = $dataProcessedWhereQuery;
                }
                $query .= ' WHERE ' . implode(' AND ', $dataProcessedWhere);
            }
            $query .= ';';
            try {
                return $this->pdo->exec($query);
            } catch (\PDOException $e) {
                new \Exception($e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }
}
