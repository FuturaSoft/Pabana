<?php
namespace Pabana\Router;

Class RouteRequirement {
	private $mHost;
	private $mMethod;
	private $mScheme;
	private $mHttpPort;
	private $mHttpsPort;

	public function getHost()
	{
		return $this->mHost;
	}

	public function getMethod() {
		return $this->mMethod;
	}

	public function getScheme() {
		return $this->mScheme;
	}

	public function getHttpPort()
	{
		return $this->mHttpPort;
	}

	public function getHttpsPort()
	{
		return $this->mHttpsPort;
	}

	public function setHost($mHost)
	{
		$this->mHost = $mHost;
	}

	public function setMethod($mMethod) {
		$this->mMethod = $mMethod;
	}

	public function setScheme($mScheme) {
		$this->mScheme = $mScheme;
	}

	public function setHttpPort($mHttpPort)
	{
		$this->mHttpPort = $mHttpPort;
	}

	public function setHttpsPort($mHttpsPort)
	{
		$this->mHttpsPort = $mHttpsPort;
	}
}