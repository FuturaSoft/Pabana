<?php
namespace Pabana\Html\Head;

use Pabana\Debug\Error;

class Link
{
	private static $arsLinkList = array();

	public function __toString()
	{
		return $this->render();
	}

	public function append($sHref, $sRel = null, $sType = null, $sMedia = null)
	{
		self::$arsLinkList[] = array($sHref, $sRel, $sType, $sMedia);
		return $this;
	}

	public function clean()
	{
		self::$arsLinkList = array();
		return $this;
	}

	public function prepend($sHref, $sRel = null, $sType = null, $sMedia = null)
	{
		$arsLink = array($sHref, $sRel, $sType, $sMedia);
		array_unshift(self::$arsLinkList, $arsLink);
		return $this;
	}

	public function render()
	{
		$sHtml = '';
		foreach(self::$arsLinkList as $arsLink) {
			$sHtml .= '<link href="' . $arsLink[0] . '"';
			if(!empty($arsLink[1])) {
				$sHtml .= ' rel="' . $arsLink[1] . '"';
			}
			if(!empty($arsLink[2])) {
				$sHtml .= ' type="' . $arsLink[2] . '"';
			}
			if(!empty($arsLink[3])) {
				$sHtml .= ' media="' . $arsLink[3] . '"';
			}
			$sHtml .= '>' . PHP_EOL;
		}
		return $sHtml;
	}
}