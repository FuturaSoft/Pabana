<?php
namespace Pabana\Network;

class Response {
	public function setLocation($sUrl, $bExit = true) {
		header("Location: " . $sUrl);
		if($bExit === true) {
			exit();
		}
		return true;
	}
}