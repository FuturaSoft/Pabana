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
 * Pgsql class
 *
 * Defined a connection to a Pgsql database
 */
class Pgsql extends Datasource
{
    private $sHost;
    private $nPort = 5432;
    
    /**
     * Constructor
     *
     * Set Connection name and define DBMS to Pgsql
     *
     * @since   1.0.0
     * @param   string $sCnxName Connection name.
     */
    public function __construct($sCnxName)
    {
        $this->setName($sCnxName);
        $this->setDbms('Pgsql');
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
        if (empty($this->getHost())) {
            $sErrorMessage = 'Connexion to PostgreSQL must have an host defined';
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
            $sDsn = 'pgsql:host=' . $this->getHost() . ';';
            if (!empty($this->getPort())) {
                $sDsn .= 'port=' . $this->getPort() . ';';
            }
            if (!empty($this->getDatabase())) {
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
