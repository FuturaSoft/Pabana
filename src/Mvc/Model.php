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
namespace Pabana\Mvc;

use Pabana\Core\Configuration;
use Pabana\Database\ConnectionCollection;

/**
 * Model class
 *
 * Manage Model
 */
class Model
{
    /**
     * @var     Redirection to $connection var.
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Connection;

    /**
     * @var     \Pabana\Database\Connection Object Connection (default connection).
     * @since   1.1
     */
    public $connection;

    /**
     * Initialize model
     *
     * If default Connection is defined, call it in $Connection var.
     *
     * @since   1.0
     * @return  void
     */
    public function __construct()
    {
        if (ConnectionCollection::existsDefault() === true) {
            $this->connection = ConnectionCollection::getDefault();
            // To maintain compatibility with version 1.0
            $this->Connection = $this->connection;
        }
    }

    /**
     * Call a model class
     *
     * @since   1.0
     * @param   string $modelName Model class name
     * @return  object|bool Return model defined in $modelName or false if error
     */
    public function get($modelName)
    {
        $modelNamespace = Configuration::read('mvc.model.namespace');
        $modelNamespace = $modelNamespace . '\\' . ucFirst($modelName);
        if (!class_exists($modelNamespace)) {
            throw new \Exception('Model "' . $modelNamespace . '" doesn\'t exist.');
            return false;
        }
        return new $modelNamespace();
    }

    /**
     * Insert a record in database
     *
     * @since   1.2
     * @param   array $data Array of column => value
     * @return  bool
     */
    public function insert($data)
    {
        if (!isset($this->table)) {
            throw new \Exception("Table isn't defined");
            return false;
        }
        return $this->connection->insert($this->table, $data);
    }

    /**
     * Update a record in database
     *
     * @since   1.2
     * @param   array $data Array of column => value
     * @param   array $dataWhere Array of column => value
     * @return  bool
     */
    public function update($data, $dataWhere = [])
    {
        if (!isset($this->table)) {
            throw new \Exception("Table isn't defined");
            return false;
        }
        return $this->connection->update($this->table, $data, $dataWhere);
    }

    /**
     * Delete a record in database
     *
     * @since   1.2
     * @param   array $dataWhere Array of column => value
     * @return  bool
     */
    public function delete($dataWhere = [])
    {
        if (!isset($this->table)) {
            throw new \Exception("Table isn't defined");
            return false;
        }
        return $this->connection->delete($this->table, $dataWhere);
    }
}
