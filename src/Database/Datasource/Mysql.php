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
 * Mysql class
 *
 * Defined a connection to a Mysql or MariaDb database
 */
class Mysql extends Datasource
{
    /**
     * @var     string Hostname or IP adresse
     * @since   1.0.0
     */
    private $sHost;

    /**
     * @var     integer Port number (by default 3306)
     * @since   1.0.0
     */
    private $nPort = 3306;

    /**
     * @var     string Unix Socket path
     * @since   1.0.0
     */
    private $sUnixSocket;
    
    /**
     * Constructor
     *
     * Set Connection name and define DBMS to MySQL
     *
     * @since   1.0.0
     * @param   string $sCnxName Connection name.
     */
    public function __construct($sCnxName)
    {
        $this->setName($sCnxName);
        $this->setDbms('Mysql');
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
        if (empty($this->getHost()) && empty($this->getUnixSocket())) {
            $sErrorMessage = 'Connexion to Mysql must have an host or an unix socket defined';
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
            if (!empty($this->getHost())) {
                $sDsn = 'mysql:host=' . $this->getHost() . ';';
                if (!empty($this->getPort())) {
                    $sDsn .= 'port=' . $this->getPort() . ';';
                }
            } else {
                $sDsn = 'mysql:unixsocket=' . $this->getUnixSocket() . ';';
            }
            if (!empty($this->getDatabase())) {
                $sDsn .= 'dbname=' . $this->getDatabase() . ';';
            }
            if (!empty($this->getCharset())) {
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
