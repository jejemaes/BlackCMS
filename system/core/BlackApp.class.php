<?php
/**
 * Maes Jerome
 * BlackApp.class.php, created at Apr 22, 2015
 *
 */

namespace system\core;


class BlackApp extends \Spot\Locator{
	
	private $spot;
	public $_slim;
	
	function __construct($sgbd_name, $db_params, $options=array()) {
	
		$cfg = new \Spot\Config();
		$cfg->addConnection($sgbd_name, [
			'dbname' => $db_params['db_name'],
			'user' => $db_params['db_login'],
			'password' => $db_params['db_pass'],
			'host' => $db_params['db_host'],
			'driver' => $db_params['db_driver'],
		]);
		
		parent::__construct($cfg);
		
		$this->_slim = new \SlimController\Slim(array(
			'controller.method_suffix'   => '', // create a route by passing the exact name of the method
		));
	}
	
	
	//###########################
	//#### ROUTE Management #####
	//###########################

//$app->get('/archive/:year', function ($year) {
//     echo "You are viewing archives from $year";
// })->conditions(array('year' => '(19|20)\d\d'));
//"className:methodName"
	public function addRoute($path, $class_and_methode, $meth = 'GET', $auth='none', $conditions=array()){
		// TODO : maybe remove or improve !
		$path = str_replace(' ', '%20', __BASE_PATH_URL) . $path;
		
		$route = $this->_slim->addControllerRoute($path, $class_and_methode);
		// add methods
		if(!is_array($meth)){
			$meth = array($meth);
		}
		foreach ($meth as $m){
			$route = $route->via(strtoupper($m));
		}
		// add conditions
		return $route->conditions($conditions);
	}
	
	
	
	public function dispatch(){
		return $this->_slim->run();
	}
	
	//###########################
	//#### MODEL Management #####
	//###########################
	
	
	public function env($model_name){
		// get the class of the model
	}
	
	/**
	 * Find the mapper for the given classname
	 * (non-PHPdoc)
	 * @see \Spot\Locator::mapper()
	 */
	public function mapper($entityName){
		//TODO check if $entityName instance of 'BlackModel'
		if (!isset($this->mapper[$entityName])) {
			// Get custom mapper, if set
			$mapper = $entityName::mapper();
			// Fallback to generic mapper
			if ($mapper === false) {
				$mapper = 'system\core\BlackMapper';
			}
			$this->mapper[$entityName] = new $mapper($this, $entityName);
		}
	
		return $this->mapper[$entityName];
	}
	
}