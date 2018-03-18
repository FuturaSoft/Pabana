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
     * Constructor
     *
     * Set Connection name and define DBMS to Sqlserver
     *
     * @since   1.0
     * @param   string $sCnxName Connection name.
     */
    public function __construct($sCnxName)
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
     * @return  string|bool Return DSN string if success or false else.
     */
    public function getDsn()
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
                $sDsn .= ' MultipleActiveResultSets=' . $this->getMultipleActiveResultSets() . ';';
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
    
    public function getApplication()
    {
        return $this->getDsnParam('APP');
    }
    
    public function getConnectionPooling()
    {
        return $this->getDsnParam('ConnectionPooling');
    }

    public function getDatabase()
    {
        return $this->getDsnParam('Database');
    }
    
    public function getEncrypt()
    {
        return $this->getDsnParam('Encrypt');
    }
    
    public function getFailoverPartner()
    {
        return $this->getDsnParam('Failover_Partner');
    }
    
    public function getLoginTimeout()
    {
        return $this->getDsnParam('LoginTimeout');
    }
    
    public function getMultipleActiveResultSets()
    {
        return $this->getDsnParam('MultipleActiveResultSets');
    }
    
    public function getQuotedId()
    {
        return $this->getDsnParam('QuotedId');
    }

    public function getServer()
    {
        return $this->getDsnParam('Server');
    }
    
    public function getTraceFile()
    {
        return $this->getDsnParam('TraceFile');
    }
    
    public function getTraceOn()
    {
        return $this->getDsnParam('TraceOn');
    }
    
    public function getTransactionIsolation()
    {
        return $this->getDsnParam('TransactionIsolation');
    }
    
    public function getTrustServerCertificate()
    {
        return $this->getDsnParam('TrustServerCertificate');
    }
    
    public function getWsid()
    {
        return $this->getDsnParam('WSID');
    }
    
    public function setApplication($application)
    {
        $this->setDsnParam('APP', $application);
        return $this;
    }
    
    public function setConnectionPooling($sConnectionPooling)
    {
        $this->sConnectionPooling = $sConnectionPooling;
        return $this;
    }
    
    public function setEncrypt($sEncrypt)
    {
        $this->sEncrypt = $sEncrypt;
        return $this;
    }
    
    public function setFailoverPartner($sFailoverPartner)
    {
        $this->sFailoverPartner = $sFailoverPartner;
        return $this;
    }
    
    public function setLoginTimeout($nLoginTimeout)
    {
        $this->nLoginTimeout = $nLoginTimeout;
        return $this;
    }
    
    public function setMultipleActiveResultSets($sMultipleActiveResultSets)
    {
        $this->sMultipleActiveResultSets = $sMultipleActiveResultSets;
        return $this;
    }
    
    public function setQuotedId($nQuotedId)
    {
        $this->nQuotedId = $nQuotedId;
        return $this;
    }
    
    public function setTraceFile($sTraceFile)
    {
        $this->sTraceFile = $sTraceFile;
        return $this;
    }
    
    public function setTraceOn($sTraceOn)
    {
        $this->sTraceOn = $sTraceOn;
        return $this;
    }
    
    public function setTransactionIsolation($nTransactionIsolation)
    {
        $this->nTransactionIsolation = $nTransactionIsolation;
        return $this;
    }
    
    public function setTrustServerCertificate($sTrustServerCertificate)
    {
        $this->sTrustServerCertificate = $sTrustServerCertificate;
        return $this;
    }
    
    public function setWsid($sWsid)
    {
        $this->sWsid = $sWsid;
        return $this;
    }
}
