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

/**
 * Connection Collection class
 *
 * Store connection use by application
 */
class ConnectionCollection
{
    /**
     * @var     array List of connection in collection
     * @since   1.0.0
     */
    private static $aroConnectionList;

    /**
     * @var     string Name of default connection
     * @since   1.0.0
     */
    private static $sDefaultConnectionName;

    /**
     * Add a connection to collection
     *
     * Store Connection to collection and defined it by default if would
     *
     * @since   1.0.0
     * @param   \Pabana\Database\Connection $cnxConnection Object defined a connection and its parameters.
     * @param   bool $bSetAsDefault If defined connection will be defined as default connection.
     */
    public static function add($cnxConnection, $bSetAsDefault = false)
    {
        $sConnectionName = $cnxConnection->getName();
        self::$aroConnectionList[$sConnectionName] = $cnxConnection;
        if ($bSetAsDefault === true) {
            self::setDefault($sConnectionName);
        }
    }

    /**
     * Check if connection exist
     *
     * Check if a connection exist by it name
     *
     * @since   1.0.0
     * @param   string $sConnectionName Connection who will check.
     * @return  bool Return true if connection exist in collection or return false.
     */
    public static function exists($sConnectionName)
    {
        return isset(self::$aroConnectionList[$sConnectionName]);
    }

    /**
     * Check if default connection exist
     *
     * Check if default connection exist
     *
     * @since   1.0.0
     * @return  bool Return true if default connection exist in collection or return false.
     */
    public static function existsDefault()
    {
        return isset(self::$sDefaultConnectionName);
    }

    /**
     * Get connection by his name
     *
     * @since   1.0.0
     * @param   string $sConnectionName Connection who will get.
     * @return  bool|\Pabana\Database\Connection Return Connection object if exist or return false.
     */
    public static function get($sConnectionName)
    {
        if (self::exists($sConnectionName) === true) {
            return self::$aroConnectionList[$sConnectionName];
        } else {
            throw new Exception('Datasource "' . $sConnectionName . '" isn\'t defined in DatasourceCollection');
            return false;
        }
    }

    /**
     * Get default connection
     *
     * @since   1.0.0
     * @return  bool|\Pabana\Database\Connection Return default Connection object if exist or return false.
     */
    public static function getDefault()
    {
        if (self::existsDefault()) {
            $sDefaultConnectionName = self::$sDefaultConnectionName;
            return self::$aroConnectionList[$sDefaultConnectionName];
        } else {
            throw new Exception('Datasource "' . $sConnectionName . '" isn\'t defined in DatasourceCollection');
            return false;
        }
    }

    /**
     * Get connection collection array
     *
     * @since   1.0.0
     * @return  array Return connection collection array.
     */
    public static function getAll()
    {
        return self::$aroConnectionList;
    }

    /**
     * Set default connection
     *
     * Set default connection name and can force it set
     *
     * @since   1.0.0
     * @param   string $sConnectionName Connection name who will be set by default.
     * @param   bool $bForce Force change of default connection.
     * @return  bool Return true if success or false if error.
     */
    public static function setDefault($sConnectionName, $bForce = true)
    {
        if ($bForce === false && self::existsDefault() === true) {
            throw new Exception('A default Datasource is already defined.');
            return false;
        } else {
            self::$sDefaultConnectionName = $sConnectionName;
            return true;
        }
    }
}
