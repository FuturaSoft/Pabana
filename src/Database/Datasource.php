<?php
namespace Pabana\Database;

use Pabana\Debug\Error;

class Datasource
{
    protected $sCharset;
    protected $sDatabase;
    protected $sDbms = null;
    protected $bDefault = false;
	protected $sName;
    protected $sPassword;
    protected $sUser;

    public function getCharset()
    {
        return $this->sCharset;
    }

    public function getDatabase()
    {
        return $this->sDatabase;
    }

    public function getOption()
    {
        return array();
    }
	
	public function getName()
    {
        return $this->sName;
    }

    public function getPassword()
    {
        return $this->sPassword;
    }

    public function getUser()
    {
        return $this->sUser;
    }

    public function isDefault()
    {
        return $this->bDefault;
    }

    public function setCharset($sCharset)
    {
        $this->sCharset = $sCharset;
        return $this;
    }

    public function setDatabase($sDatabase)
    {
        $this->sDatabase = $sDatabase;
        return $this;
    }

    public function setDbms($sDbms)
    {
        $this->sDbms = $sDbms;
        return $this;
    }

    public function setDefault($bDefault)
    {
        $this->bDefault = $bDefault;
        return $this;
    }

	public function setName($sName)
    {
        $this->sName = $sName;
        return $this;
    }
	
    public function setPassword($sPassword)
    {
        $this->sPassword = $sPassword;
        return $this;
    }

    public function setUser($sUser)
    {
        $this->sUser = $sUser;
        return $this;
    }
}