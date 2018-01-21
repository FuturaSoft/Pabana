<?php
namespace Pabana\Core;

use \Pabana\Network\Http\Request;

class Bootstrap {
	public $Request;

	public function __construct()
	{
		$this->Request = new Request();
	}
}