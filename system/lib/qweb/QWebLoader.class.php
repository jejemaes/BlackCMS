<?php
/**
 * Maes Jerome
 * IrQwebLoader.class.php, created at May 11, 2015
 *
 */
namespace system\lib\qweb;

use system\interfaces\iTemplateLoader;


class QWebLoader implements iTemplateLoader{
	
	/**
	 * Load the template named 'name', and return its code
	 * @param string $name : the name of the searched template
	 * @return code : the code (content) of the template
	 */
	public function load_template($name){
		$ViewMapper = App()->mapper('addons\ir\model\IrView');
		$tmp = $ViewMapper->apply_inheritance_arch($name);
		//echo htmlspecialchars($tmp);
		return $tmp;
	}
	
	/**
	 * Store the given template in the database
	 * @param string $name : the name of the template
	 * @param code $content : the content of the template
	*/
	public function add_template($name, $content){
		
	}
	
	/**
	 * remove (definitively) the given template
	 * @param string $name : the name of the template to remove
	*/
	public function remove_template($name){
		
	}
	
}