<?php
/**
 * Maes Jerome
 * init.php, created at Apr 19, 2015
 *
 */

// define system directory constants
define('_DIR_SYS', __SITE_PATH . 'system/core/');
define('_DIR_EXCEPTION', __SITE_PATH . 'system/exceptions/');
define('_DIR_INCLUDE', __SITE_PATH . 'system/includes/');
define('_DIR_MODULE', __SITE_PATH . 'addons/');
define('_DIR_MODULE_SYSTEM', __SITE_PATH . 'system/addons/');
define('_DIR_MEDIA', __SITE_PATH . 'media/');

// autoload for classes
include _DIR_INCLUDE . 'autoload.php';
include _DIR_INCLUDE . 'file.inc.php';

// imports
use system\core\BlackApp as BlackApp;
use system\core\Logger as Logger;



// instanciate the Logger
include _DIR_SYS . 'Logger.class.php';
global $Logger;
$Logger = Logger::getInstance(Logger::LOG_DEBUG);


// instanciate the app (helpers)
function App() {
	static $app;
	if($app === null) {
		$cfg = array(
			'db_name' => DB_NAME,
			'db_login' => DB_LOGIN,
			'db_pass' => DB_PASS,
			'db_host' => DB_HOST,
			'db_driver' => 'pdo_mysql',
		);
		$app = new BlackApp('mysql', $cfg);
	}
	return $app;
}
$app = App();

// include routes files
//TODO use mapper to get installed module names
load_route_file(array('ir'));


$app->dispatch();

/*
$post = new \system\Post();

var_dump($post::fields());
*/
/*
echo '<hr>';
$ViewMapper = $app->mapper('addons\ir\model\IrView');
echo $ViewMapper->render('base.test1',  array('caca' => (1 == 1), 'is_class' => FALSE, 'styles' => "bolD", "website" => "eeee"));
echo "<hr>";
*/
