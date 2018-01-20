<?php
namespace Pabana\Routing;

use Pabana\Routing\Route;

Class RouteCollection {
	private static $arsRouteCollection;
	private static $sFallbackAction = 'index';
	private static $sFallbackController = 'Error';
	private static $arcSeparator = array('/', '?', '&', '=');

	public static function add($sRoute, $arsOption) {
		self::$arsRouteCollection[] = new Route($sRoute, $arsOption);
	}

	public static function getFallbackAction()
	{
		return self::$sFallbackAction;
	}

	public static function getFallbackController()
	{
		return self::$sFallbackController;
	}

	public static function getAll() {
		return self::$arsRouteCollection;
	}

	public static function getSeparator()
	{
		return self::$arcSeparator;
	}

	public static function setFallback($sController, $sAction) {
		self::$sFallbackAction = $sAction;
		self::$sFallbackController = $sController;
	}

	public static function setSeparator($mSeparator, $bMerge = true) {
		if (is_array($mSeparator)) {
			if ($bMerge === true) {
				$arcSeparator = $mSeparator + $arcSeparator;
			} else {
				$arcSeparator = $mSeparator;
			}
		} else {
			if ($bMerge === true) {
				$arcSeparator[] = $mSeparator;
			} else {
				$arcSeparator = array($mSeparator);
			}
		}
		self::$arcSeparator = $arcSeparator;
	}
}