<?php
namespace Pabana\Network\Http;

class Response
{
	public function setLocation($sUrl, $bExit = true)
	{
		header("Location: " . $sUrl);
		if($bExit === true) {
			exit();
		}
		return true;
	}
}