<?php
namespace Pabana\Html;

use Pabana\Debug\Error;

class Breadcrumbs
{
	private static $arsBreadcrumbsList = array();

	public function __toString()
	{
		return $this->render();
	}

	public function append($sTitle, $mUrl)
	{
		$sUrl = $this->Url->build($mUrl);
		self::$arsBreadcrumbsList[] = array(
									  	'title' => $sTitle,
									  	'url' => $sUrl
									  );
		return $this;
	}

	public function clean()
	{
		self::$arsBreadcrumbsList = array();
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