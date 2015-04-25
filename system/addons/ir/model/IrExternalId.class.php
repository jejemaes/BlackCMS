<?php
/**
 * Maes Jerome
 * IrExternalId.class.php, created at Apr 25, 2015
 *
 */


namespace addons\ir;

use system\core\BlackModel as BlackModel;
 

class IrExternalId extends BlackModel{
	
	protected static $table = 'ir_external_id';
	
	public static function fields(){
		$fields = parent::fields();
		return array_merge($fields, array(
				'name'         => ['type' => 'string', 'required' => true, 'index' => true],
				'res_model'    => ['type' => 'string', 'required' => true], // will the table name (model name, instead of class name) !!
				'res_id'       => ['type' => 'integer', 'required' => true],
				'module'       => ['type' => 'string']
		));
	}
	
	
	public static function xml_id_to_object($xml_id, $raise_if_not_found=false){
		self::mapper();
	}
	
	
}