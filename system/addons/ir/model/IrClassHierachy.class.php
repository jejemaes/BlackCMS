<?php
/**
 * Maes Jerome
 * IrClassHierachy.class.php, created at Apr 25, 2015
 *
 */

namespace addons\ir;

use system\core\BlackModel as BlackModel;


class IrClassHierachy extends BlackModel{
	
	protected static $table = 'ir_external_id';
	
	protected static $mapper = 'system\core\BlackMapper';
	
	
	public static function fields(){
		$fields = parent::fields();
		return array_merge($fields, array(
				'class_name'	=> ['type' => 'string', 'required' => true],
				'model'    		=> ['type' => 'string', 'required' => true], // will the table name (model name, instead of class name) !!
		));
	}
	
	public static function relations(Mapper $mapper, Entity $entity){
		return [
			'author' => $mapper->belongsTo($entity, 'addons\ir\IrClassHierachy', 'parent_id')
		];
	}
	
	
	public static function getClass($model){
		self::mapper();
	}

}
