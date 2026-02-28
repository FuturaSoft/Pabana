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
 * Odbc class
 *
 * Defined a connection to a database via Odbc
 */
class Odbc extends Datasource
{
    /**
     * @var     string ODBC driver name
     * @since   1.0
     */
    private string $sDriver = '';

    /**
     * @var     integer Exclusive
     * @since   1.0
     */
    private int $nExclusive = 0;

    /**
     * @var     integer Extended Ansi SQL
     * @since   1.0
     */
    private int $nExtendedAnsiSql = 0;

    /**
     * @var     integer Locale identifier
     * @since   1.0
     */
    private int $nLocaleIdentifier = 0;

    /**
     * @var     string System database path
     * @since   1.0
     */
    private string $sSystemDatabase = '';

    /**
     * Constructor
     *
     * Set Connection name and define DBMS to Odbc
     *
     * @since   1.0
     * @param   string $sCnxName Connection name.
     */
    public function __construct(string $sCnxName)
    {
        $this->setName($sCnxName);
        $this->setDbms('Odbc');
    }

    /**
     * Check connection parameters
     *
     * Check if connection parameters is correct
     *
     * @since   1.0
     * @return  bool True if success or false.
     */
    protected function checkParam(): bool
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
     * @since   1.0
     * @return  string|bool Return DSN string if success or false else.
     */
    public function getDsn(): string|false
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

    public function getDriver(): string
    {
        return $this->sDriver;
    }

    public function getExclusive(): int
    {
        return $this->nExclusive;
    }

    public function getExtendedAnsiSql(): int
    {
        return $this->nExtendedAnsiSql;
    }

    public function getLocaleIdentifier(): int
    {
        return $this->nLocaleIdentifier;
    }

    public function getSystemDatabase(): string
    {
        return $this->sSystemDatabase;
    }

    public function setDriver(string $sDriver): static
    {
        $this->sDriver = $sDriver;
        return $this;
    }

    public function setExclusive(int $nExclusive): static
    {
        $this->nExclusive = $nExclusive;
        return $this;
    }

    public function setExtendedAnsiSql(int $nExtendedAnsiSql): static
    {
        $this->nExtendedAnsiSql = $nExtendedAnsiSql;
        return $this;
    }

    public function setLocaleIdentifier(int $nLocaleIdentifier): static
    {
        $this->nLocaleIdentifier = $nLocaleIdentifier;
        return $this;
    }

    public function setSystemDatabase(string $sSystemDatabase): static
    {
        $this->sSystemDatabase = $sSystemDatabase;
        return $this;
    }
}
