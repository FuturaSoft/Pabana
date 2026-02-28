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
 * Statement class
 *
 * Statement manipulation class
 */
class Statement
{
    /**
     * @var     \PDOStatement Object created by PDO query.
     * @since   1.0
     */
    private \PDOStatement $statement;

    /**
     * Constructor
     *
     * @since   1.0
     * @param   \PDOStatement $statement Object created by PDO->query().
     */
    public function __construct(\PDOStatement $statement)
    {
        $this->statement = $statement;
    }

    /**
     * Binds a parameter to the specified variable name
     *
     * @since   1.0
     * @param   mixed $parameter Parameter identifier.
     * @param   mixed $value Name of the PHP variable to bind to the SQL statement parameter.
     * @param   int $dataType Explicit data type for the parameter using the PDO::PARAM_* constants.
     * @param   int $valueLength Length of the data type.
     * @return  bool Returns TRUE on success or FALSE on failure.
     */
    public function bindParam(mixed $parameter, mixed $value, int $dataType = \PDO::PARAM_STR, ?int $valueLength = null): bool
    {
        return $this->statement->bindParam($parameter, $value, $dataType, $valueLength);
    }

    /**
     * Binds a value to a parameter
     *
     * @since   1.0
     * @param   mixed $parameter Parameter identifier.
     * @param   mixed $value Name of the PHP variable to bind to the SQL statement parameter.
     * @param   int $dataType Explicit data type for the parameter using the PDO::PARAM_* constants.
     * @return  bool Returns TRUE on success or FALSE on failure.
     */
    public function bindValue(mixed $parameter, mixed $value, int $dataType = \PDO::PARAM_STR): bool
    {
        return $this->statement->bindValue($parameter, $value, $dataType);
    }

    /**
     * Returns the number of columns in the result set
     *
     * @since   1.0
     * @return  int Returns the number of columns in the result.
     */
    public function columnCount(): int
    {
        return $this->statement->columnCount();
    }

    /**
     * Executes a prepared statement
     *
     * @since   1.0
     * @param   mixed $valueList An array of values.
     * @return  bool Returns TRUE on success or FALSE on failure.
     */
    public function execute(?array $valueList = null): bool
    {
        return $this->statement->execute($valueList);
    }

    /**
     * Fetches the next row from a result set
     *
     * @since   1.0
     * @param   string $fetchType Controls how the next row will be returned to the caller.
     * @return  array|object|false Return value depends on the fetch type. FALSE is returned on failure.
     */
    public function fetch(string $fetchType = 'assoc'): array|object|false
    {
        $pdoFetchType = match ($fetchType) {
            'num' => \PDO::FETCH_NUM,
            'assoc' => \PDO::FETCH_ASSOC,
            'obj' => \PDO::FETCH_OBJ,
        };
        return $this->statement->fetch($pdoFetchType);
    }

    /**
     * Returns an array containing all of the result set rows
     *
     * @since   1.0
     * @param   string $fetchType Controls how the next row will be returned to the caller.
     * @return  array|false Return value depends on the fetch type. FALSE is returned on failure.
     */
    public function fetchAll(string $fetchType = 'assoc'): array|false
    {
        $pdoFetchType = match ($fetchType) {
            'num' => \PDO::FETCH_NUM,
            'assoc' => \PDO::FETCH_ASSOC,
            'obj' => \PDO::FETCH_OBJ,
        };
        return $this->statement->fetchAll($pdoFetchType);
    }
}
