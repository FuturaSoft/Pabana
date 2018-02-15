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

/**
 * Odbc class
 *
 * Defined a connection to a database via Odbc
 */
class Odbc extends Datasource
{
    private $sDriver;
    private $nExclusive;
    private $nExtendedAnsiSql;
    private $nLocaleIdentifier;
    private $sSystemDatabase;

    /**
     * Constructor
     *
     * Set Connection name and define DBMS to Odbc
     *
     * @since   1.0.0
     * @param   string $sCnxName Connection name.
     */
    public function __construct($sCnxName)
    {
        $this->setName($sCnxName);
        $this->setDbms('Odbc');
    }

    /**
     * Check connection parameters
     *
     * Check if connection parameters is correct
     *
     * @since   1.0.0
     * @return  bool True if success or false.
     */
    protected function checkParam()
    {
        if (empty($this->getDriver())) {
            $sErrorMessage = 'Connexion by Odbc must have a driver defined';
            throw new \Exception($sErrorMessage);
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * Get DSN string
     *
     * Return DSN string build from connection parameters
     *
     * @since   1.0.0
     * @return  string|bool Return DSN string if success or false else.
     */
    public function getDsn()
    {
        if ($this->checkParam()) {
            $sDsn = 'odbc:Driver={' . $this->getDriver() . '};';
            if (!empty($this->getUser())) {
                $sDsn .= 'Uid=' . $this->getUser() . ';';
            } else {
                $sDsn .= 'Uid=Admin;';
            }
            $sDsn .= 'Pwd=' . $this->getPassword() . ';Dbq=' . $this->getDatabase() . ';';
            if (!empty($this->getSystemDatabase())) {
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
