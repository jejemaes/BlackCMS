<?php
/**
 * Maes Jerome
 * IrViewMapper.class.php, created at Apr 25, 2015
 *
 */
namespace addons\ir\model;

use \system\core\BlackMapper;
use \DOMDocument as DOMDocument;

class IrViewMapper extends BlackMapper{
	
	/**
	 * render the given view with the given value, using the specified template engine
	 * @param string $xml_id : the name of the view to render (modue.view_name).
	 * @param array $values : the render values
	 * @param string $engine_class : (optional) the class name of the engine
	 * @return string : the code of the rendered view
	 */
	public function render($xml_id, array $values, $engine_class = 'system\lib\qweb\QwebEngine'){
		$engine = $engine_class::getEngine();
		echo $engine->render($xml_id, $values);
		return '%%%%%';
	}
	
	
	
	
	

	/**
	 * get the view correspondng to the given xml_id
	 * @param string $xmlid : xml_id of the view. Must looks like "module"."view_name".
	 * @return IrView : the view 
	 */
	public function get_view($xmlid){
		$views = $this->where(['xml_id' => $xmlid]);
		if (count($views) > 0){
			return $views[0];
		}
		throw new ObjectNotFoundException('IrView', $xmlid);
	}
	
	/**
	 * get the inherited views of the given view_id
	 * @param integer $view_id : identifier of the view (master view)
	 * @return Ambigous <\ActiveRecord\mixed, NULL, unknown, \ActiveRecord\Model, multitype:>
	 */
	public function get_inherited_view($view_id){
		return $this->all()->where(['inherit_id' => $view_id, 'active' => 1])->order(['sequence' => 'DESC']);;
		//return static::find('all', array('conditions' => array('parent_id = ? AND active = ?', $view_id, '1'), 'order' => 'sequence desc'));
	}
	
	
	/**
	 * get the arch field of the given view xmlid, after the inheritances were applied.
	 * It return a string (code) of thebase view extended by its children
	 * @param string $xmlid : the base xmlid
	 * @return string : the inherited view arch
	 */
	public function apply_inheritance_arch($xmlid){
		$base_view = $this->get_view($xmlid);
		$inherited_views = $this->get_inherited_view($base_view->id);
	
		$base_arch_dom = new DOMDocument();
		$base_arch_dom->loadXML($base_view->web_arch, LIBXML_NOWARNING);
	
		foreach ($inherited_views as $view){
			$view_arch_dom = new DOMDocument;
			$view_arch_dom->loadXML($view->web_arch, LIBXML_NOWARNING);
				
			$elements = $view_arch_dom->getElementsByTagName("xpath");
			foreach ($elements as $xpath_element) {
				$query = $xpath_element->getAttribute('expr');
				$position = $xpath_element->getAttribute('position') ? $xpath_element->getAttribute('position') : 'inside';
					
				if($query){
					$xpath = new DOMXPath($base_arch_dom);
					$result = $xpath->query($query);
						
					if($result){
						// import nodes (child of xpath tags) from inherited view to base DOM
						$result = $result->item(0); //TODO only take the first ?
						$nodes = array();
						foreach($xpath_element->childNodes as $child){
							$nodes[] = $base_arch_dom->importNode($child, true);
						}
						// place correctly the new nodes into the base architecture DOM
						if($position == 'inside'){
							foreach ($nodes as $n) {
								$result->appendChild($n);
							}
						}
						if($position == 'replace'){
							foreach ($nodes as $n) {
								$result->parentNode->insertBefore($n, $result);
							}
							$result->parentNode->removeChild($result);
						}
						if($position == 'before'){
							foreach ($nodes as $n) {
								$result->parentNode->insertBefore($n, $result);
							}
						}
						if($position == 'after'){
							// TODO
						}
					}else{
						// no elem found, wrong xpath expr
					}
				}else{
					// TODO : not tag expr
				}
			}
		}
		//echo htmlspecialchars($base_arch_dom->saveXML());
		//var_dump($base_arch_dom);
		return $base_arch_dom->saveXML();
	}
	
	
}