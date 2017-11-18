<?php
namespace Pabana\Router;

use Pabana\Core\Configuration;
use Pabana\Network\Http\Request;
use Pabana\Router\Route;
use Pabana\Router\RouteCollection;

Class Router
{
	public static $action;
	public static $controller;
	public static $namespace;
	public static $RouteCollection;
	private $arcSeparator = array('/', '?', '&', '=');

	public function __construct()
	{
		// Set separator
		if(Configuration::exists('router.separator')) {
			$this->setSeparator(Configuration::get('router.separator'));
		}
		// Check if a router config file is enable
		if(Configuration::exists('router.config_file')) {
			$sConfigFilePath = Configuration::get('application.path') . Configuration::get('router.config_file');
			Self::$RouteCollection = $this->loadConfig($sConfigFilePath);
		}
	}

	public function loadConfig($sConfigFile)
	{
		return (include $sConfigFile);
	}

	public function resolve()
	{
		// Get current URL
		$oRequest = new Request();
		$sUrl = $oRequest->url;
		// Remove last char of URL if it's a separator
		$sUrl = $this->removeLastSeparator($sUrl);
		// Get list of separator for this URL
		$arcUrlSeparator = $this->listSeparator($sUrl);
		// Get list of value of this URL
		$arsUrlValue = $this->listValue($sUrl);
		// Check route
		$bCheckResult = $this->checkRoute($arcUrlSeparator, $arsUrlValue);
		// If no result is find in route, use default router (/{controller}/{action})
		if (!$bCheckResult) {
			$this->checkDefault($arsUrlValue);
		}
		// Check if controller and action exists
		$this->checkNamespace();
	}

	public function setSeparator($mSeparator)
	{
		$arcSeparator = array();
		if(is_array($mSeparator)) {
			$arcSeparator = $mSeparator;
		} else {
			$arcSeparator[] = $mSeparator;
		}
		$this->arcSeparator = $arcSeparator;
	}

	private function checkRoute($arcUrlSeparator, $arsUrlValue)
	{
		foreach (Self::$RouteCollection->getList() as $oRoute) {
			// Get route
			$sRoute = $oRoute->getRoute();
			// Remove last char of route if it's a separator
			$sRoute = $this->removeLastSeparator($sRoute);
			// Get list of separator for this route
			$arcRouteSeparator = $this->listSeparator($sRoute);
			// If URL and route have same number of separator in same position
			if ($arcUrlSeparator == $arcRouteSeparator) {
				// Get list of value of this route
				$arsRouteValueList = $this->listValue($sRoute);
				// Valid boolean
				$bGoodRoute = true;
				// Array to store param
				$arsParamList = array();
				// Check each value of url to compare with route
				foreach($arsRouteValueList as $nParamIndex => $sRouteValue) {
					if(isset($arsUrlValue[$nParamIndex])) {
						// Get first char of 
						$cFirstCharValue = substr($sRouteValue, 0, 1);
						if($cFirstCharValue == ':') {
							$arsParamList[substr($sRouteValue, 1)] = $arsUrlValue[$nParamIndex];
						} else if($sRouteValue != $arsUrlValue[$nParamIndex] && $cFirstCharValue != '*') {
							$bGoodRoute = false;
						}
					} else {
						$bGoodRoute = false;
					}
				}
				// If valid boolean is true
				if($bGoodRoute) {
					// Set controller to route controller
					Self::$controller = $oRoute->getController();
					// Set namespace to route controller
					Self::$namespace = $oRoute->getNamespace();
					// Set action to route action
					Self::$action = $oRoute->getAction();
					// Add route param to param list
					if(is_array($oRoute->getParamList())) {
						$arsParamList = $arsParamList + $oRoute->getParamList();
					}
					// Set param list
					$_GET = $arsParamList;
					// End of check route
					return true;
				}
			}
		}
		// No route is found
		return false;
	}

	private function checkDefault($arsUrlValueList)
	{
		$sController = 'Index';
		$sAction = 'index';
		$arsParamList = array();
		foreach ($arsUrlValueList as $nIndexValue => $sUrlValue) {
			if($nIndexValue == 0) {
				$sController = $sUrlValue;
			} else if($nIndexValue == 1) {
				$sAction = $sUrlValue;
			} else {
				if(isset($arsUrlValueList[$nIndexValue]) && $nIndexValue % 2 == 0) {
					$sParamName = $arsUrlValueList[$nIndexValue];
					$sParamValue = null;
					if(isset($arsUrlValueList[$nIndexValue + 1])) {
						$sParamValue = $arsUrlValueList[$nIndexValue + 1];
					}
					$arsParamList[$sParamName] = $sParamValue;
				}
			}
		}
		Self::$controller = $sController;
		// Set action to route action
		Self::$action = $sAction;
		// Set param list
		$_GET = $arsParamList;
	}

	private function checkNamespace()
	{
		if(!class_exists(Self::$namespace)) {
			// Set controller to route action
			Self::$controller = Configuration::get('controller.error');
			// Set namespace to route action
			Self::$namespace = Configuration::get('controller.namespace') . '\\';
			Self::$namespace .= Configuration::get('controller.error') . '\\';
			// Set action to route action
			Self::$action = 'index';
			// Set param list
			$_GET = array('code' => 404);
		}
	}

	private function listSeparator($sUrl)
	{
		$armSeparatorPosition = array();
		foreach ($this->arcSeparator as $cSeparator) {
			$sUrlSearch = $sUrl;
			while ($nSeparatorPosition = strrpos($sUrlSearch, $cSeparator)) {
		        $armSeparatorPosition[$nSeparatorPosition] = $cSeparator;
		        $sUrlSearch = substr($sUrlSearch, 0, $nSeparatorPosition);
		    }
		}
		ksort($armSeparatorPosition);
		return array_values($armSeparatorPosition);
	}

	private function removeLastSeparator($sUrl)
	{
		$cLastChar = substr($sUrl, -1);
		if(in_array($cLastChar, $this->arcSeparator)) {
			$sUrl = substr($sUrl, 0, -1);
		}
		return $sUrl;
	}

	private function listValue($sUrl)
	{
		$sRegexSeparator = implode('', $this->arcSeparator);
		$sRegexSeparator = preg_quote($sRegexSeparator);
		$arsListValue = preg_split("{[" . $sRegexSeparator . "]}", $sUrl);
		array_shift($arsListValue);
		return $arsListValue;
	}
}