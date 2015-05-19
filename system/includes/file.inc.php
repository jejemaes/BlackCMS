<?php
/**
 * Maes Jerome
 * include_file.inc.php, created at May 13, 2015
 *
 */

function load_route_file($module_names){
	global $Logger;
	foreach ($module_names as $mod_name){
		$right_path = false;
		foreach (array(_DIR_MODULE, _DIR_MODULE_SYSTEM) as $mod_dir){
			$filename = $mod_dir . $mod_name . '/routes.inc.php';
			if(!$right_path && file_exists($filename)){
				$right_path = $filename;
			}
		}
		if($right_path){
			include $right_path;
		}else{
			$Logger->warn("Module " . $mod_name . " don't have a routes.inc.php file to include");
		}
		
	}
}