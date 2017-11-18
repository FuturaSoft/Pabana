<?php
namespace Pabana\Mvc;

use Pabana\Core\Configuration;
use Pabana\Database\ConnectionCollection;

class Model
{
	public $Connection;

	public function __construct()
	{
		$this->Connection = ConnectionCollection::getDefault();
	}
}