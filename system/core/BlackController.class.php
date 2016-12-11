<?php
/**
 * Maes Jerome
 * BlackController.class.php, created at Apr 22, 2015
 *
 */

namespace system\core;

class BlackController extends \SlimController\SlimController{
	
	public $view;
	
	public function __construct($app){
		$this->view = App()->mapper('addons\ir\model\IrView');
		parent::__construct($app);
	}
	
	
	 protected function render($template, $args = array(), $status=null){
		
		if (!is_null($status)) { // copy from parent
			$this->app->response->status($status);
		}
		// TODO add a prepare value to render, to extends the values array (for website)
		
		// TODO check the headers, ...
		$this->app->response->body($this->view->render($template, $args));
	}
	
}