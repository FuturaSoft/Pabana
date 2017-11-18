<?php
namespace Pabana\Html\Head;

use Pabana\Debug\Error;

class Icon
{
	private static $arsIconList = array();

	public function __toString()
	{
		return $this->render();
	}

	public function append($sHref, $bAutoPath = true)
	{
		if ($bAutoPath === true) {
			$sHref = '/img/' . $sHref;
		}
		self::$arsIconList[] = array($sHref);
		return $this;
	}

	public function clean()
	{
		self::$arsIconList = array();
		return $this;
	}

	public function prepend($sHref, $bAutoPath = true)
	{
		if ($bAutoPath === true) {
			$sHref = '/img/' . $sHref;
		}
		$arsIcon = array($sHref);
		array_unshift(self::$arsIconList, $arsIcon);
		return $this;
	}

	public function render()
	{
		$sHtml = '';
		foreach(self::$arsIconList as $arsIcon) {
			$sHtml .= '<link href="' . $arsIcon[0] . '" rel="icon">' . PHP_EOL;
		}
		return $sHtml;
	}
}