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

use Pabana\Debug\Error;

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
     * @since   1.0.0
     */
    protected $sCharset;

    /**
     * @var     string Database value, can be a name in MySQL or a path in Odbc
     * @since   1.0.0
     */
    protected $sDatabase;

    /**
     * @var     string Database Management System name (Mysql, Odbc, Pgsql, ...)
     * @since   1.0.0
     */
    protected $sDbms;

    /**
     * @var     string Datasource name
     * @since   1.0.0
     */
    protected $sName;

    /**
     * @var     string Datasource password
     * @since   1.0.0
     */
    protected $sPassword;

    /**
     * @var     string Datasource user
     * @since   1.0.0
     */
    protected $sUser;

    /**
     * Getter for Datasource charset
     *
     * This method can get charset value.
     *
     * @since   1.0.0
     * @return  string Charset defined.
     */
    public function getCharset()
    {
        return $this->sCharset;
    }

    /**
     * Getter for Datasource database name or path
     *
     * This method can get database name or path value.
     *
     * @since   1.0.0
     * @return  string Database name.
     */
    public function getDatabase()
    {
        return $this->sDatabase;
    }

    /**
     * Getter for Datasource option
     *
     * This method can get datasource option value.
     *
     * @since   1.0.0
     * @return  array Array of option.
     */
    public function getOption()
    {
        return array();
    }
    
    /**
     * Getter for Datasource name
     *
     * This method can get name value.
     *
     * @since   1.0.0
     * @return  string
     */
    public function getName()
    {
        return $this->sName;
    }

    /**
     * Getter for Datasource password
     *
     * This method can get password value.
     *
     * @since   1.0.0
     * @return  string
     */
    public function getPassword()
    {
        return $this->sPassword;
    }

    /**
     * Getter for Datasource user
     *
     * This method can get user value.
     *
     * @since   1.0.0
     * @return  string
     */
    public function getUser()
    {
        return $this->sUser;
    }

    /*public function isDefault()
    {
        return $this->bDefault;
    }*/

    /**
     * Setter for Datasource charset
     *
     * This method can set charset value.
     *
     * @param   string $sCharset Charset value of Datasource
     * @since   1.0.0
     * @return  $this
     */
    public function setCharset($sCharset)
    {
        $this->sCharset = $sCharset;
        return $this;
    }

    /**
     * Setter for Datasource database name or path
     *
     * This method can set database name or path.
     *
     * @param   string $sDatabase Database name or path
     * @since   1.0.0
     * @return  $this
     */
    public function setDatabase($sDatabase)
    {
        $this->sDatabase = $sDatabase;
        return $this;
    }

    /**
     * Setter for Datasource database managment system
     *
     * This method can set database managment system.
     *
     * @param   string $sDbms Database managment system
     * @since   1.0.0
     * @return  $this
     */
    public function setDbms($sDbms)
    {
        $this->sDbms = $sDbms;
        return $this;
    }

    /**
     * Setter for Datasource name
     *
     * This method can set name of datasource.
     *
     * @param   string $sName Datasource name
     * @since   1.0.0
     * @return  $this
     */
    public function setName($sName)
    {
        $this->sName = $sName;
        return $this;
    }
    
    /**
     * Setter for Datasource password
     *
     * This method can set password of datasource.
     *
     * @param   string $sPassword Datasource password
     * @since   1.0.0
     * @return  $this
     */
    public function setPassword($sPassword)
    {
        $this->sPassword = $sPassword;
        return $this;
    }

    /**
     * Setter for Datasource user
     *
     * This method can set user of datasource.
     *
     * @param   string $sUser Datasource user
     * @since   1.0.0
     * @return  $this
     */
    public function setUser($sUser)
    {
        $this->sUser = $sUser;
        return $this;
    }
}
