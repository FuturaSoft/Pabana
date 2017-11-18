<?php
namespace Pabana\Router;

use Pabana\Error\Error;
use Pabana\Router\RouteCollection;

Class Route
{
	private $sName = null;
	private $sRoute = null;
	private $sNamespace = null;
	private $sController = 'index';
	private $sAction = 'index';
	private $arsParamList = null;

	public function __construct($sRoute, $arsOption = array())
	{
		$this->setRoute($sRoute);
		if (isset($arsOption['controller']) && isset($arsOption['namespace'])) {
			throw new Error('Route can\'t be defined by a controller AND a namespace.');
		} elseif (isset($arsOption['controller']) {
			$this->setController($arsOption['controller']);
		} elseif (isset($arsOption['namespace']) {
			$this->setNamespace(strtolower($arsOption['namespace']));
		}
		if (isset($arsOption['action'])) {
			$this->setAction(strtolower($arsOption['action']));
		}
		if (isset($arsOption['param'])) {
			$this->setParam($arsOption['param']);
		}
		if (isset($arsOption['name'])) {
			$this->setName($arsOption['name']);
		} else {
			$sNameController = $this->sController;
			$sNameAction = $this->sAction;
			if (isset($arsOption['namespace']) {
				$arsNamespaceList = explode('\\', $mUrlValue['namespace']);
				$nNamespaceCount = count($arsNamespaceList);
				$sNameController = $arsNamespaceList[$nNamespaceCount];
			}
			$this->setName($sNameController . '_' . $sNameAction);
		}
	}

	public function getAction()
	{
		return $this->sAction;
	}

	public function getController()
	{
		return $this->sController;
	}

	public function getName()
	{
		return $this->sName;
	}

	public function getNamespace()
	{
		return $this->sNamespace;
	}

	public function getParam()
	{
		return $this->arsParamList;
	}

	public function getRoute()
	{
		return $this->sRoute;
	}

	public function setAction($sAction)
	{
		$this->sAction = $sAction;
		return $this;
	}

	public function setController($sController)
	{
		$this->sController = $sController;
		$sControllerNamespace = Configuration::get('controller.namespace');
		$sNamespace = $sControllerNamespace . '\\' . $this->sController;
		$this->setNamespace($sNamespace);
		return $this;
	}

	public function setName($sName)
	{
		$oRouteCollection = new RouteCollection();
		if ($oRouteCollection->getByName($sName) === false) {
			$this->sName = $sName;
		} else {
			throw new Error('Route name "' . $sName . '" is already defined.');
			return false;
		}
		return $this;
	}

	public function setNamespace($sNamespace)
	{
		$this->sNamespace = $sNamespace;
		return $this;
	}

	public function setParam($arsParamList)
	{
		$this->arsParamList = $arsParamList;
		return $this;
	}

	public function setRoute($sRoute)
	{
		$oRouteCollection = new RouteCollection();
		if ($oRouteCollection->getByRoute($sRoute) === false) {
			$this->sRoute = $sRoute;
		} else {
			throw new Error('Route path "' . $sRoute . '" is already defined.');
			return false;
		}
		return $this;
	}
}