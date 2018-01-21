<?php
namespace Pabana\Intl;

class Encoding
{
	public function detect($sValue)
	{
		return mb_detect_encoding($sValue);
	}

	public function convert($mValue, $sInCharset = 'auto', $sOutCharset = 'auto', $bTranslit = true, $bIgnore = true)
	{
		$mReturn = $mValue;
		if (is_string($mValue)) {
			if ($sInCharset == 'auto') {
				$sInCharset = $this->detect($mValue);
			}
			if ($sOutCharset == 'auto') {
				$sOutCharset = Configuration::read('application.encoding');
			}
			if ($bTranslit === true) {
				$sOutCharset .= '//TRANSLIT';
			} else if ($bIgnore === true) {
				$sOutCharset .= '//IGNORE';
			}
			$mReturn = @iconv($sInCharset, $sOutCharset, $mValue);
		} else if (is_array($mValue)) {
			$mReturn = array();
			foreach ($mValue as $mArrayKey => $mArrayValue) {
				$mArrayKey = $this->convert($mArrayKey, $sInCharset, $sOutCharset, $bTranslit, $bIgnore);
				$mArrayValue = $this->convert($mArrayValue, $sInCharset, $sOutCharset, $bTranslit, $bIgnore);
				$mReturn[$mArrayKey] = $mArrayValue;
			}
		}
		return $mReturn;
	}
}
?>