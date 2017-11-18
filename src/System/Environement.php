<?php
namespace Pabana\System;

Class Environment
{
	public $is64BitsOperatingSystem = null;
	public $is64BitsProcess = null;
	public $newLine = PHP_EOL;

	public function __construct($sRoute, $arsOption = array())
	{
		$this->is64BitsOperatingSystem = $this->_is64BitsOperatingSystem());
		$this->is64BitsProcess = $this->_is64BitsProcess());
		$this->machineName = gethostname();
	}

	private function _is64BitsOperatingSystem()
	{
		if (strstr(php_uname("m"), '64')) {
			return true;
		} else {
			return false;
		}
	}

	private function _is64BitsProcess()
	{
		if (PHP_INT_SIZE > 4) {
			return true;
		} else {
			return false;
		}
	}

	public function exit($nExitCode)
	{
		exit($nExitCode);
	}
}