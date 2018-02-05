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

class ConnectionCollection
{
    private static $aroConnectionList;
    private static $sDefaultConnectionName;

    public static function add($cnxConnection, $bSetAsDefault = false)
    {
        $sConnectionName = $cnxConnection->getName();
        self::$aroConnectionList[$sConnectionName] = $cnxConnection;
        if ($bSetAsDefault === true) {
            self::setDefault($sConnectionName);
        }
    }

    public static function exists($sConnectionName)
    {
        return isset(self::$aroConnectionList[$sConnectionName]);
    }

    public static function existsDefault()
    {
        return isset(self::$sDefaultConnectionName);
    }

    public static function get($sConnectionName)
    {
        if (self::exists($sConnectionName) === true) {
            return self::$aroConnectionList[$sConnectionName];
        } else {
            throw new Exception('Datasource "' . $sConnectionName . '" isn\'t defined in DatasourceCollection');
            return false;
        }
    }

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

    public static function getAll()
    {
        return self::$aroConnectionList;
    }

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
