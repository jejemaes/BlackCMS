<?php
/**
 * Maes Jerome
 * autoload.php, created at Apr 19, 2015
 *
 */
function classLoader($class_name){
	$directories = explode("\\", $class_name);
	if($directories[0] == 'addons'){
		$directories = array_slice($directories, 1);
		$module_dir = array(_DIR_MODULE, _DIR_MODULE_SYSTEM);
		$found = false;
		foreach ($module_dir as $mod_dir){
			if(! $found){
				$path = $mod_dir . DIRECTORY_SEPARATOR . implode($directories, DIRECTORY_SEPARATOR);
				$file = __SITE_PATH . $path . '.class.php';
				$found = _include_file($file);
			}
		}
	}else{
		$root = implode($directories, DIRECTORY_SEPARATOR);
		$file = __SITE_PATH . $root . '.class.php';
		_include_file($file);
	}
}

function _include_file($filename){
	global $Logger;
	if (!file_exists($filename)){
		$Logger->debug("File " . $filename . " not found !");
		return false;
	}
	include $filename;
	$Logger->debug("File " . $filename . " imported !");
	return true;
}

spl_autoload_register('classLoader');