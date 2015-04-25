<?php
/**
 * Maes Jerome
 * BlackApp.class.php, created at Apr 22, 2015
 *
 */

namespace system\core;


class BlackApp extends \Spot\Locator{
	
	private $spot;
	
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
	}
	
	
	//###########################
	//#### ROUTE Management #####
	//###########################

//$app->get('/archive/:year', function ($year) {
//     echo "You are viewing archives from $year";
// })->conditions(array('year' => '(19|20)\d\d'));
	public function route($path, $route, $meth = 'GET', $auth='none', $conditions=array()){
		
	}
	
	
	
	public function dispatch(){
		return $this->run();
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