<?php
namespace Pabana\Html;

use Pabana\Debug\Error;
use Pabana\Html\Doctype;
use Pabana\Html\Head;


class Html
{
	public $Doctype;
	public $Head;
	public $Script;
	public $Breadcrumbs;

	public function __construct()
	{
		$this->Doctype = new Doctype();
		$this->Head = new Head();
		$this->Script = new Script();
		$this->Breadcrumbs = new Breadcrumbs();
	}

	public function clean()
	{
		$this->Doctype->clean();
		$this->Head->clean();
		$this->Script->clean();
		$this->Breadcrumbs->clean();
	}
}