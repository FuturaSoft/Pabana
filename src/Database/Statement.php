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
 * Statement class
 *
 * Statement manipulation class
 */
class Statement
{
    /**
     * @var     \PDOStatement Object created by PDO query.
     * @since   1.0.0
     */
    private $oStatement;

    /**
     * Constructor
     *
     * @since   1.0.0
     * @param   \PDOStatement $oStatement Object created by PDO->query().
     */
    public function __construct($oStatement)
    {
        $this->oStatement = $oStatement;
    }
    
    public function bindParam($mParameter, $mValue, $nDataType =  null, $nValueLength = null)
    {
        return $this->oStatement->bindParam($mParameter, $mValue, $nDataType, $nValueLength);
    }
    
    public function bindValue($mParameter, $mValue, $nDataType =  null)
    {
        return $this->oStatement->bindValue($mParameter, $mValue, $nDataType);
    }
    
    public function columnCount()
    {
        return $this->oStatement->columnCount();
    }
    
    public function execute($armValues = null)
    {
        return $this->oStatement->execute($armValues);
    }
    
    public function fetch($sFetchType = 'assoc')
    {
        if ($sFetchType === 'num') {
            return $this->oStatement->fetch(\PDO::FETCH_NUM);
        } elseif ($sFetchType === 'assoc') {
            return $this->oStatement->fetch(\PDO::FETCH_ASSOC);
        } elseif ($sFetchType === 'obj') {
            return $this->oStatement->fetch(\PDO::FETCH_OBJ);
        }
    }
    
    public function fetchAll($sFetchType = 'assoc')
    {
        if ($sFetchType === 'num') {
            return $this->oStatement->fetchAll(\PDO::FETCH_NUM);
        } elseif ($sFetchType === 'assoc') {
            return $this->oStatement->fetchAll(\PDO::FETCH_ASSOC);
        } elseif ($sFetchType === 'obj') {
            return $this->oStatement->fetchAll(\PDO::FETCH_OBJ);
        }
    }
}