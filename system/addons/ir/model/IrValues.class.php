<?php
/**
 * Maes Jerome
 * IrValues.class.php, created at May 17, 2015
 *
 */

namespace addons\ir\model;
use system\core\BlackModel as BlackModel;

class IrValues extends BlackModel{

	protected static $table = 'ir_values';

	public static function fields(){
		$fields = parent::fields();
		return array_merge($fields, array(
				'key'         	=> ['type' => 'string', 'required' => true, 'index' => true],
				'value'        	=> ['type' => 'text', 'required' => true],
				'type'     		=> ['type' => 'string', 'required' => true],
				'description'   => ['type' => 'text'],
		));
	}
}
