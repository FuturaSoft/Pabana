<?php
namespace Pabana\Router;

use Pabana\Router\Route;
use Pabana\Router\RouteRequirement;
use Pabana\Type\Array;

Class RouteCollection {
	private static $oRouteList = new Array();
	private static $sPrefix;
	private static $oRequirement = new RouteRequirement();
	private static $arsOption = array();

	public function add($sRoute, $arsRouteOption, $oRouteRequirement = new RouteRequirement())
	{
		self::$oRouteList->append(new Route($sRoute, $arsOption, $oRouteRequirement));
	}

	public function getByName($sName)
	{
		foreach($this->getAll() as $oRoute) {
			if ($oRoute->getName() === $sName) {
				return $oRoute;
			}
		}
		return false;
	}

	public function getByRoute($sRoute)
	{
		foreach($this->getAll() as $oRoute) {
			if ($oRoute->getRoute() === $sRoute) {
				return $oRoute;
			}
		}
		return false;
	}

	public function getAll()
	{
		return self::$oRouteList->getAll();
	}

	public function setPrefix($sPrefix)
	{
		self::$sPrefix = $sPrefix;
	}
}