<?php
namespace Pabana\Html\Helper;

use Pabana\Debug\Error;
use Pabana\Core\Configuration;
use Pabana\Router\RouteCollection;

class Url
{
	private $bIsRoute = false;
	private $sController = null;
	private $sAction = null;
	private $sRoute = null;

	public function build($mUrlValue, $armParam = array())
	{
		$this->setParam($armParam);
		if (is_array($mUrlValue))) {
			if (isset($mUrlValue['controller']) && isset($mUrlValue['namespace'])) {
				throw new Error('Url can\'t be defined by a controller AND a namespace.');
				return false;
			} else if (isset($mUrlValue['controller']) && isset($mUrlValue['action'])) {
				$this->setController($mUrlValue['controller']);
				$this->setAction($mUrlValue['action']);
			} else if (isset($mUrlValue['namespace']) && isset($mUrlValue['action'])) {
				$arsNamespaceList = explode('\\', $mUrlValue['namespace']);
				$nNamespaceCount = count($arsNamespaceList);
				$sController = $arsNamespaceList[$nNamespaceCount];
				$this->setController($sController);
				$this->setAction($mUrlValue['action']);
			} else {
				throw new Error('Url MUST be defined by an action AND a controller OR a namespace.');
				return false;
			}
			return $this->render();
		} else if (is_string($mUrlValue)) {
			$oRouteCollection = new RouteCollection();
			$oRoute = $oRouteCollection->getByName($mUrlValue);
			if ($oRoute === false) {
				throw new Error('Route name "' . $sName . '" doesn\'t exist.');
				return false;
			} else {
				$this->bIsRoute = true;
				$sRoute = $oRoute->getRoute();
				$this->setRoute($sRoute);
			}
		}
		return $this;
	}

	private function getAction()
	{
		return $this->sAction;
	}

	private function getController()
	{
		return $this->sController;
	}

	private function getRoute()
	{
		return $this->sRoute;
	}

	private function setAction($sAction)
	{
		$this->sAction = $sAction;
	}

	private function setController($sController)
	{
		$this->sController = $sController;
	}

	private function setRoute($sRoute)
	{
		$this->sRoute = $sRoute;
	}

	private function render()
	{
		$cRouterSeparator = Configuration::get('router.separator'));
		if($this->bIsRoute === false) {
			$sUrl = $cRouterSeparator . $this->getController();
			$sUrl .= $cRouterSeparator . $this->getAction();
		} else {
			$sUrl = $this->getRoute();
		}
		foreach($this->getParam() as $sParamKey => $sParamValue) {
			// Param name
			$sUrl .= $cRouterSeparator . $sParamKey;
			// Param value
			$sUrl .= $cRouterSeparator . $sParamValue;
		}
		return $sUrl;
	}
}