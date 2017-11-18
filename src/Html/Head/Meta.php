<?php
namespace Pabana\Html\Head;

use Pabana\Debug\Error;

class Meta
{
	private static $arsHttpEquiv = array();
	private static $arsMeta = array();

	public function __toString()
	{
		return $this->render();
	}

	public function clean()
	{
		self::$arsHttpEquiv = array();
		self::$arsMeta = array();
	}

	public function render()
	{
		$sHtml = '';
		foreach(self::$arsHttpEquiv as $sHttpEquivName => $sHttpEquivValue) {
			$sHtml .= '<meta http-equiv="' . $sHttpEquivName . '" content="' . $sHttpEquivValue . '">' . PHP_EOL;
		}
		foreach(self::$arsMeta as $sMetaName => $sMetaValue) {
			$sHtml .= '<meta name="' . $sMetaName . '" value="' . $sMetaValue . '">' . PHP_EOL;
		}
		return $sHtml;
	}

	public function set($sMetaName, $sMetaValue)
	{
		self::$arsMeta[$sMetaName] = $sMetaValue;
		return $this;
	}

	public function setApplicationName($sApplicationName)
	{
		return $this->set('application-name', $sApplicationName);
	}

	public function setAuthor($sAuthor)
	{
		 return $this->set('author', $sAuthor);
	}

	public function setDefaultStyle($sDefaultStyle)
	{
		return $this->setHttpEquiv('default-style', $sDefaultStyle);
	}

	public function setDescription($sDescription)
	{
		return $this->set('description', $sDescription);
	}

	public function setGenerator($sGenerator)
	{
		return $this->set('generator', $sGenerator);
	}

	public function setHttpEquiv($sHttpEquivName, $sHttpEquivValue)
	{
		self::$arsHttpEquiv[$sHttpEquivName] = $sHttpEquivValue;
		return $this;
	}

	public function setKeyword($sKeyword)
	{
		return $this->set('keywords', $sKeyword);
	}

	public function setRefresh($sRefresh)
	{
		return $this->setHttpEquiv('refresh', $sRefresh);
	}

	public function setUaCompatible($sUaCompatible)
	{
		return $this->setHttpEquiv('X-UA-Compatible', $sUaCompatible);
	}

	public function setViewport($sViewport)
	{
		return $this->set('viewport', $sViewport);
	}
}