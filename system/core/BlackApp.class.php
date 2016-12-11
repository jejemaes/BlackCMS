<?php
/**
 * Maes Jerome
 * BlackApp.class.php, created at Apr 22, 2015
 *
 */

namespace system\core;
use \system\exceptions\ProgrammingException as ProgrammingException;

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
			//'view' => 'addons\ir\model\IrView',
			'controller.method_suffix'   => '', // create a route by passing the exact name of the method
		));
	}
	
	
	//###########################
	//########## HTTP ###########
	//###########################

	/**
	 * add a route to the mapping routes
	 * @param string $path : the path of the route to add. Should be unique.
	 * @param string $class_and_method : callback of the route, controller class and method name such as 'className:methodName'
	 * @param string $meth : method allowed to access  the route. String concat with '|' as separator.
	 * @param string $auth : authentification required to access the route ('public', .
	 * @param array $conditions : key-array of type/regex condition for the route params. Use the SlimFramework format (@see http://docs.slimframework.com/routing/conditions/)
	 * @return Ambigous <\Slim\Route, \Slim\Route>
	 */
	public function addRoute($path, $class_and_method, $meth = 'GET|POST', $auth='public', $conditions=array()){
		// TODO : maybe remove or improve !
		$path = str_replace(' ', '%20', __BASE_PATH_URL) . $path;
		
		// authenticate callable (slim middleware)
		$authenticateRoute = function ($auth='public') {
			return function () use ($auth) {
				$is_authenticate = App()->_authenticate($auth);
				if (!$is_authenticate) {
					App()->_slim->redirect('/login');
				}
			};
		};
		
		// create route
		$route = $this->_slim->addControllerRoute($path, $class_and_method, array($authenticateRoute($auth)));
		
		// add methods
		$meth = preg_split("/[|]/", $meth);
		foreach ($meth as $m){
			$route = $route->via(strtoupper($m));
		}
		
		// add conditions
		return $route->conditions($conditions);
	}
	
	/**
	 * dispatch the current request to the correct route
	 * @return the response of the matched controller method
	 */
	public function dispatch(){
		return $this->_slim->run();
	}
	
	
	//###########################
	//#### AUTHENTIFICATION #####
	//###########################
	
	/**
	 * authentification dispatching
	 * @param unknown $auth
	 * @throws ProgrammingException
	 */
	public function _authenticate($auth){
		$method_name = '_authenticate' . ucfirst($auth);
		if(method_exists($this, $method_name)){
			return $this->$method_name();
		}
		throw new ProgrammingException('AUTH ERROR : no ' . $method_name . ' definied for type authentification ' . $auth);
	}
	
	
	public function _authenticatePublic(){
		// TODO
		return true;
	}
	
	public function _authenticateUser(){
		// TODO
		return true;
	}
	
	
	//###########################
	//#### ENTITY Management ####
	//###########################
	
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