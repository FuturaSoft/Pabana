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
 * Connection Collection class
 *
 * Store connection use by application
 */
class ConnectionCollection
{
    /**
     * @var     array List of connection in collection
     * @since   1.0
     */
    private static $connectionList;

    /**
     * @var     string Name of default connection
     * @since   1.0
     */
    private static $defaultConnectionName;

    /**
     * Add a connection to collection
     *
     * Store Connection to collection and defined it by default if would
     *
     * @since   1.0
     * @param   \Pabana\Database\Connection $connection Object defined a connection and its parameters.
     * @param   bool $setAsDefault If defined connection will be defined as default connection.
     * @return  void
     */
    public static function add($connection, $setAsDefault = false)
    {
        $connectionName = $connection->getName();
        self::$connectionList[$connectionName] = $connection;
        if ($setAsDefault === true) {
            self::setDefault($connectionName);
        }
    }

    /**
     * Check if connection exist
     *
     * Check if a connection exist by it name
     *
     * @since   1.0
     * @param   string $connectionName Connection who will check.
     * @return  bool Return true if connection exist in collection or return false.
     */
    public static function exists($connectionName)
    {
        return isset(self::$connectionList[$connectionName]);
    }

    /**
     * Check if default connection exist
     *
     * Check if default connection exist
     *
     * @since   1.0
     * @return  bool Return true if default connection exist in collection or return false.
     */
    public static function existsDefault()
    {
        return isset(self::$defaultConnectionName);
    }

    /**
     * Get connection by his name
     *
     * @since   1.0
     * @param   string $connectionName Connection who will get.
     * @return  bool|\Pabana\Database\Connection Return Connection object if exist or return false.
     */
    public static function get($connectionName)
    {
        if (self::exists($connectionName) === true) {
            return self::$connectionList[$connectionName];
        } else {
            throw new \Exception('Datasource "' . $connectionName . '" isn\'t defined in DatasourceCollection');
            return false;
        }
    }

    /**
     * Get default connection
     *
     * @since   1.0
     * @return  bool|\Pabana\Database\Connection Return default Connection object if exist or return false.
     */
    public static function getDefault()
    {
        if (self::existsDefault()) {
            $defaultConnectionName = self::$defaultConnectionName;
            return self::$connectionList[$defaultConnectionName];
        } else {
            throw new \Exception('Datasource "' . self::$defaultConnectionName . '" isn\'t defined in DatasourceCollection');
            return false;
        }
    }

    /**
     * Get connection collection array
     *
     * @since   1.0
     * @return  array Return connection collection array.
     */
    public static function getAll()
    {
        return self::$connectionList;
    }

    /**
     * Set default connection
     *
     * Set default connection name and can force it set
     *
     * @since   1.0
     * @param   string $connectionName Connection name who will be set by default.
     * @param   bool $force Force change of default connection.
     * @return  bool Return true if success or false if error.
     */
    public static function setDefault($connectionName, $force = true)
    {
        if ($force === false && self::existsDefault() === true) {
            throw new \Exception('A default Datasource is already defined.');
            return false;
        } else {
            self::$defaultConnectionName = $connectionName;
            return true;
        }
    }
}
