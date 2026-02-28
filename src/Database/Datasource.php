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

/**
 * Datasource class
 *
 * Global information about Datasource.
 * This class is extends by Mysql, Odbc, Pgsql, ...
 */
class Datasource
{
    /**
     * @var     string Charset use in Datasource
     * @since   1.0
     */
    protected string $charset = '';

    /**
     * @var     string Database value, can be a name in MySQL or a path in Odbc
     * @since   1.0
     */
    protected string $database = '';

    /**
     * @var     string Database Management System name (Mysql, Odbc, Pgsql, ...)
     * @since   1.0
     */
    protected string $dbms = '';

    /**
     * @var     string Datasource name
     * @since   1.0
     */
    protected string $name = '';

    /**
     * @var     string Datasource password
     * @since   1.0
     */
    protected string $password = '';

    /**
     * @var     string Datasource user
     * @since   1.0
     */
    protected string $user = '';

    /**
     * Getter for Datasource charset
     *
     * This method can get charset value.
     *
     * @since   1.0
     * @return  string Charset defined.
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * Getter for Datasource database name or path
     *
     * This method can get database name or path value.
     *
     * @since   1.0
     * @return  string Database name.
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * Getter for Datasource option
     *
     * This method can get datasource option value.
     *
     * @since   1.0
     * @return  array Array of option.
     */
    public function getOption(): array
    {
        return [];
    }

    /**
     * Getter for Datasource name
     *
     * This method can get name value.
     *
     * @since   1.0
     * @return  string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Getter for Datasource password
     *
     * This method can get password value.
     *
     * @since   1.0
     * @return  string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Getter for Datasource user
     *
     * This method can get user value.
     *
     * @since   1.0
     * @return  string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * Setter for Datasource charset
     *
     * This method can set charset value.
     *
     * @param   string $charset Charset value of Datasource
     * @since   1.0
     * @return  $this
     */
    public function setCharset(string $charset): static
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * Setter for Datasource database name or path
     *
     * This method can set database name or path.
     *
     * @param   string $database Database name or path
     * @since   1.0
     * @return  $this
     */
    public function setDatabase(string $database): static
    {
        $this->database = $database;
        return $this;
    }

    /**
     * Setter for Datasource database managment system
     *
     * This method can set database managment system.
     *
     * @param   string $dbms Database managment system
     * @since   1.0
     * @return  $this
     */
    public function setDbms(string $dbms): static
    {
        $this->dbms = $dbms;
        return $this;
    }

    /**
     * Setter for Datasource name
     *
     * This method can set name of datasource.
     *
     * @param   string $name Datasource name
     * @since   1.0
     * @return  $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Setter for Datasource password
     *
     * This method can set password of datasource.
     *
     * @param   string $password Datasource password
     * @since   1.0
     * @return  $this
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Setter for Datasource user
     *
     * This method can set user of datasource.
     *
     * @param   string $user Datasource user
     * @since   1.0
     * @return  $this
     */
    public function setUser(string $user): static
    {
        $this->user = $user;
        return $this;
    }
}
