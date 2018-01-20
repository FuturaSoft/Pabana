<?php
namespace Pabana\Routing;

Class Route {
	private $_sRoute = null;
	private $_sController = 'index';
	private $_sAction = 'index';
	private $_arsParamList = null;

	public function __construct($sRoute, $arsOption = array()) {
		$this->_sRoute = $sRoute;
		if(isset($arsOption['controller'])) {
			$this->_sController = $arsOption['controller'];
		}
		if(isset($arsOption['action'])) {
			$this->_sAction = $arsOption['action'];
		}
		if(isset($arsOption['param'])) {
			$this->_arsParamList = $arsOption['param'];
		}
	}

	public function getRoute() {
		return $this->_sRoute;
	}

	public function getController() {
		return $this->_sController;
	}

	public function getAction() {
		return $this->_sAction;
	}

	public function getParamList() {
		return $this->_arsParamList;
	}
}