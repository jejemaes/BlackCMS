<?php
/**
 * Maes Jerome
 * iTemplateEngine.class.php, created at Jan 2, 2015
 *
 */

namespace system\interfaces;

interface iTemplateEngine{
	
	/**
	 * get the instance of the engine (singleton)
	 * @param string $loaderClass : the name of the class loader
	 */
	public static function getEngine($loaderClass=false);
	
	
	/**
	 * 
	 * @param unknown $id_or_xml_id
	 * @param array $qwebcontext
	 * @param string $loader
	 */
	public function render($id_or_xml_id, $qwebcontext, $loader=NULL);
	
}