<?php
/**
 * Maes Jerome
 * IrController.class.php, created at May 13, 2015
 *
 */

namespace addons\ir\controller;

use system\core\BlackController as BlackController;


class IrController extends BlackController{

	public function indexAction(){
		$this->view->render('base.test1', array('caca' => (1 == 1), 'is_class' => FALSE, 'styles' => "bolD", "website" => "eeee"));
	}
	
	
	public function bundleAction($type, $name){
		$this->render('base.test1', array('caca' => (1 == 1), 'is_class' => FALSE, 'styles' => "bolD", "website" => "eeee"));
	}

	
}