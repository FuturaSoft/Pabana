<?php
namespace Pabana\Html;

use Pabana\Debug\Error;

class Script
{
	private static $sJsCode = '';
	private static $arsScriptList = array();

	public function __toString()
	{
		return $this->render();
	}

	public function append($sHref)
	{
		self::$arsScriptList[] = array($sHref);
		return $this;
	}

	public function appendFile($sHref, $bAutoPath = true)
	{
		if ($bAutoPath === true) {
			$sHref = '/js/' . $sHref;
		}
		self::$arsScriptList[] = array($sHref);
		return $this;
	}

	public function appendLibrary($sLibrary, $sHref)
	{
		self::$arsScriptList[] = array('/lib/' . $sLibrary . '/js/' . $sHref);
		return $this;
	}

	public function appendScript($sJsCode)
	{
		self::$sJsCode .= $sJsCode;
		return $this;
	}

	public function clean()
	{
		self::$sJsCode = '';
		self::$arsScriptList = array();
		return $this;
	}

	public function prepend($sHref)
	{
		$arsScript = array($sHref);
		array_unshift(self::$arsScriptList, $arsScript);
		return $this;
	}

	public function prependFile($sHref, $bAutoPath = true)
	{
		if ($bAutoPath === true) {
			$sHref = '/js/' . $sHref;
		}
		$arsScript = array($sHref);
		array_unshift(self::$arsScriptList, $arsScript);
		return $this;
	}

	public function prependLibrary($sLibrary, $sHref)
	{
		$arsScript = array('/lib/' . $sLibrary . '/js/' . $sHref);
		array_unshift(self::$arsScriptList, $arsScript);
		return $this;
	}

	public function prependScript($sJsCode)
	{
		self::$sJsCode = $sJsCode . self::$sJsCode;
		return $this;
	}

	public function render()
	{
		$sHtml = '';
		foreach(self::$arsScriptList as $arsScript) {
			$sHtml .= '<script src="' . $arsScript[0] . '" type="text/javascript"></script>' . PHP_EOL;
		}
		if(!empty(self::$sJsCode)) {
			$sHtml .= '<script type="text/javascript">' . self::$sJsCode . '</script>' . PHP_EOL;
		}
		return $sHtml;
	}
}