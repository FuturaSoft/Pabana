<?php
namespace Pabana\Parser;

use Pabana\Error\Error;

Class Ini
{
	private $sFilename;

	public function load($sFilename)
	{
		if(!file_exists($sFilename)) {
			throw new Error('Ini file "' . $sFilename . '" doesn\'t exist.');
			return false;
		}
		$this->sFilename = $sFilename;
		return $this;
	}

	public function toArray($bProcessSection = false)
	{
		return parse_ini_file($this->sFilename, $bProcessSection, INI_SCANNER_TYPED);
	}
}