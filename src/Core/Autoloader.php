<?php
namespace Pabana\Core;

class Autoloader
{
	public function __construct()
	{
		$arsAutoLoader = spl_autoload_functions();
		$sPabanaAutoLoaderClass = __NAMESPACE__ . '\AutoLoader::loadClass';
		if(!is_array($arsAutoLoader) || !in_array($sPabanaAutoLoaderClass, $arsAutoLoader)) {
			spl_autoload_register($sPabanaAutoLoaderClass, true, true);
		}
    }

    public function register()
    {
    	
    }
	
	public static function loadClass($sClass)
	{
		$sClassPath = str_replace('\\', '/', $sClass) . '.php';
		if(stream_resolve_include_path($sClassPath)) {
			require($sClassPath);
			return true;
		} else {
			throw new Error('Class inclusion error "' . $sClassPath . '"');
			return false;
		}
	}
}