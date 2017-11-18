<?php
namespace Pabana\Intl;

use Pabana\Debug\Error;

class Intl {
	public static $enable;
	public static $fallback;
	public static $local;

	public function disable() {
		self::$enable = false;
	}

	public function enable() {
		self::$enable = true;
	}
}
?>