<?php
namespace Pabana\Mvc;

use Pabana\Core\Configuration;
use Pabana\Error\Error;
use Pabana\Html\Html;
use Pabana\Mvc\View;
use Pabana\Network\Request;

class Layout
{
	private static $armVariable = array();
	private static $bAutoRender;
	private static $sDirectory;
	private static $sExtension;
	private static $sName;
	private static $sPrefix;
	public $Html;
	public $View;

	public function __construct()
	{
		// Load Mvc\Html helper to $Html var
		$this->Html = new Html();
		// Load Mvc\View to $View var
		$this->View = new View();
		// Set auto render status from configuration
		$this->setAutoRender(Configuration::get('layout.auto_render'));
		// Set default directory for view
		$sDirectory = Configuration::get('application.path') . Configuration::get('layout.path');
		$this->setDirectory($sDirectory);
	}

	public function __toString()
	{
        return $this->render();
    }

    public function element($sElement)
    {
		$sPath = $this->getDirectory() . '/' . $this->getName() . '/' . $sElement . '.php';
		foreach ($this->getVarList() as $sVarName => $mVarValue) {
			${$sVarName} = $mVarValue;
		}
		ob_start();
		require($sPath);
		echo PHP_EOL;
		$sHtml = ob_get_contents();
		ob_end_clean();
		return $sHtml;
	}

	public function getAutoRender()
	{
		return self::$bAutoRender;
	}

	public function getDirectory()
	{
		return self::$sDirectory;
	}

	public function getName()
	{
		return self::$sName;
	}

	public function getVar($sVarName)
	{
		if (isset(self::$armVariable[$sVarName])) {
			return self::$armVariable[$sVarName];
		} else {
			throw new Error("Variable '" . $sVarName . "' isn't defined in Layout.");
			return false;
		}
	}

	public function getVarList()
	{
		return self::$armVariable;
	}

	public function render()
	{
		return $this->element('index');
	}

	public function renderInit()
	{
		var_dump(Configuration::getAll());
		$sInitPath = $this->getDirectory() . '/' . $this->getName() . '/' . 'Init.php';
		require($sInitPath);
	}

	public function setAutoRender($bAutoRender)
	{
		self::$bAutoRender = $bAutoRender;
		return true;
	}

	public function setDirectory($sDirectory)
	{
		self::$sDirectory = $sDirectory;
		return true;
	}

	public function setName($sName, $bCleanRender = true)
	{
		self::$sName = $sName;
		if($bCleanRender === true) {
			$this->Html->clean();
			$this->renderInit();
		}
		return true;
	}

	public function setVar($sVarName, $sVarValue)
	{
		if(!isset(self::$armVariable[$sVarName])) {
			self::$armVariable[$sVarName] = $sVarValue;
			return true;
		} else {
			throw new Error("La variable " . $sVarName . " est déja défini dans le Layout");
			return false;
		}
	}
}