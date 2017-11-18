<?php
namespace Pabana\System;

Class Version
{
	public $build;
	public $major;
	public $minor;
	public $revision;

	public function __construct($nMajor, $nMinor, $nBuild, $nRevision)
	{
		$this->major = $nMajor;
		$this->minor = $nMinor;
		$this->build = $nBuild;
		$this->revision = $nRevision;
	}

	public function compare()
	{
		
	}
}