<?php
namespace Pabana\Database;

class Statement
{
    private $oStatement;

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
        } else if ($sFetchType === 'assoc') {
            return $this->oStatement->fetch(\PDO::FETCH_ASSOC);
        } else if ($sFetchType === 'obj') {
        	return $this->oStatement->fetch(\PDO::FETCH_OBJ);
		}
	}
    
    public function fetchAll($sFetchType = 'assoc')
    {
		if ($sFetchType === 'num') {
			return $this->oStatement->fetchAll(\PDO::FETCH_NUM);
        } else if ($sFetchType === 'assoc') {
            return $this->oStatement->fetchAll(\PDO::FETCH_ASSOC);
        } else if ($sFetchType === 'obj') {
        	return $this->oStatement->fetchAll(\PDO::FETCH_OBJ);
		}
	}
}