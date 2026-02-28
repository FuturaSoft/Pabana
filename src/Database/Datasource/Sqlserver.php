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
namespace Pabana\Database\Datasource;

use Pabana\Database\Datasource;

/**
 * Sqlserver class
 *
 * Defined a connection to a Sqlserver database
 */
class Sqlserver extends Datasource
{
    /**
     * @var     string Hostname or IP address
     * @since   1.0
     */
    private string $host = '';

    /**
     * @var     int Port number
     * @since   1.0
     */
    private int $port = 1433;

    /**
     * @var     string Application name
     * @since   1.0
     */
    private string $application = '';

    /**
     * @var     string Connection pooling
     * @since   1.0
     */
    private string $connectionPooling = '';

    /**
     * @var     string Encrypt connection
     * @since   1.0
     */
    private string $encrypt = '';

    /**
     * @var     string Failover partner
     * @since   1.0
     */
    private string $failoverPartner = '';

    /**
     * @var     int Login timeout
     * @since   1.0
     */
    private int $loginTimeout = 0;

    /**
     * @var     string Multiple active result sets
     * @since   1.0
     */
    private string $multipleActiveResultSets = '';

    /**
     * @var     int Quoted identifier
     * @since   1.0
     */
    private int $quotedId = 0;

    /**
     * @var     string Trace file path
     * @since   1.0
     */
    private string $traceFile = '';

    /**
     * @var     string Trace on
     * @since   1.0
     */
    private string $traceOn = '';

    /**
     * @var     int Transaction isolation level
     * @since   1.0
     */
    private int $transactionIsolation = 0;

    /**
     * @var     string Trust server certificate
     * @since   1.0
     */
    private string $trustServerCertificate = '';

    /**
     * @var     string Workstation ID
     * @since   1.0
     */
    private string $wsid = '';

    /**
     * Constructor
     *
     * Set Connection name and define DBMS to Sqlserver
     *
     * @since   1.0
     * @param   string $sCnxName Connection name.
     */
    public function __construct(string $sCnxName)
    {
        $this->setName($sCnxName);
        $this->setDbms('sqlsrv');
    }

    /**
     * Get DSN string
     *
     * Return DSN string build from connection parameters
     *
     * @since   1.0
     * @return  string|false Return DSN string if success or false else.
     */
    public function getDsn(): string|false
    {
        if ($this->checkParam()) {
            $sDsn = 'sqlsrv:';
            if (!empty($this->getApplication())) {
                $sDsn .= 'APP=' . $this->getApplication() . ';';
            }
            if (!empty($this->getConnectionPooling())) {
                $sDsn .= 'ConnectionPooling=' . $this->getConnectionPooling() . ';';
            }
            if (!empty($this->getDatabase())) {
                $sDsn .= 'Database=' . $this->getDatabase() . ';';
            }
            if (!empty($this->getEncrypt())) {
                $sDsn .= 'Encrypt=' . $this->getEncrypt() . ';';
            }
            if (!empty($this->getFailoverPartner())) {
                $sDsn .= 'FailoverPartner=' . $this->getFailoverPartner() . ';';
            }
            if (!empty($this->getLoginTimeout())) {
                $sDsn .= 'LoginTimeout=' . $this->getLoginTimeout() . ';';
            }
            if (!empty($this->getMultipleActiveResultSets())) {
                $sDsn .= 'MultipleActiveResultSets=' . $this->getMultipleActiveResultSets() . ';';
            }
            if (!empty($this->getQuotedId())) {
                $sDsn .= 'QuotedId=' . $this->getQuotedId() . ';';
            }
            if (!empty($this->getHost())) {
                $sDsn .= 'Server=' . $this->getHost();
                if (!empty($this->getPort())) {
                    $sDsn .= ',' . $this->getPort();
                }
                $sDsn .= ';';
            }
            if (!empty($this->getTraceFile())) {
                $sDsn .= 'TraceFile=' . $this->getTraceFile() . ';';
            }
            if (!empty($this->getTraceOn())) {
                $sDsn .= 'TraceOn=' . $this->getTraceOn() . ';';
            }
            if (!empty($this->getTransactionIsolation())) {
                $sDsn .= 'TransactionIsolation=' . $this->getTransactionIsolation() . ';';
            }
            if (!empty($this->getTrustServerCertificate())) {
                $sDsn .= 'TrustServerCertificate=' . $this->getTrustServerCertificate() . ';';
            }
            if (!empty($this->getWsid())) {
                $sDsn .= 'WSID=' . $this->getWsid() . ';';
            }
            return $sDsn;
        } else {
            return false;
        }
    }

    public function getApplication(): string
    {
        return $this->application;
    }

    public function getConnectionPooling(): string
    {
        return $this->connectionPooling;
    }

    public function getEncrypt(): string
    {
        return $this->encrypt;
    }

    public function getFailoverPartner(): string
    {
        return $this->failoverPartner;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getLoginTimeout(): int
    {
        return $this->loginTimeout;
    }

    public function getMultipleActiveResultSets(): string
    {
        return $this->multipleActiveResultSets;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getQuotedId(): int
    {
        return $this->quotedId;
    }

    public function getTraceFile(): string
    {
        return $this->traceFile;
    }

    public function getTraceOn(): string
    {
        return $this->traceOn;
    }

    public function getTransactionIsolation(): int
    {
        return $this->transactionIsolation;
    }

    public function getTrustServerCertificate(): string
    {
        return $this->trustServerCertificate;
    }

    public function getWsid(): string
    {
        return $this->wsid;
    }

    public function setApplication(string $application): static
    {
        $this->application = $application;
        return $this;
    }

    public function setConnectionPooling(string $connectionPooling): static
    {
        $this->connectionPooling = $connectionPooling;
        return $this;
    }

    public function setEncrypt(string $encrypt): static
    {
        $this->encrypt = $encrypt;
        return $this;
    }

    public function setFailoverPartner(string $failoverPartner): static
    {
        $this->failoverPartner = $failoverPartner;
        return $this;
    }

    public function setHost(string $host): static
    {
        $this->host = $host;
        return $this;
    }

    public function setLoginTimeout(int $loginTimeout): static
    {
        $this->loginTimeout = $loginTimeout;
        return $this;
    }

    public function setMultipleActiveResultSets(string $multipleActiveResultSets): static
    {
        $this->multipleActiveResultSets = $multipleActiveResultSets;
        return $this;
    }

    public function setPort(int $port): static
    {
        $this->port = $port;
        return $this;
    }

    public function setQuotedId(int $quotedId): static
    {
        $this->quotedId = $quotedId;
        return $this;
    }

    public function setTraceFile(string $traceFile): static
    {
        $this->traceFile = $traceFile;
        return $this;
    }

    public function setTraceOn(string $traceOn): static
    {
        $this->traceOn = $traceOn;
        return $this;
    }

    public function setTransactionIsolation(int $transactionIsolation): static
    {
        $this->transactionIsolation = $transactionIsolation;
        return $this;
    }

    public function setTrustServerCertificate(string $trustServerCertificate): static
    {
        $this->trustServerCertificate = $trustServerCertificate;
        return $this;
    }

    public function setWsid(string $wsid): static
    {
        $this->wsid = $wsid;
        return $this;
    }
}
