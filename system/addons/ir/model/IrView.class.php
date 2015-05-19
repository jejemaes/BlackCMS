<?php
/**
 * Maes Jerome
 * IrView.class.php, created at Apr 24, 2015
 *
 */

namespace addons\ir\model;
use system\core\BlackModel as BlackModel;

class IrView extends BlackModel{
	
	protected static $table = 'ir_view';
	
	protected static $mapper = 'addons\ir\model\IrViewMapper';
	
	public static function fields(){
		$fields = parent::fields();
		return array_merge($fields, array(
				'name'         => ['type' => 'string', 'required' => true],
				'xml_id'       => ['type' => 'string', 'required' => true, 'index' => true],
				'web_arch'     => ['type' => 'text', 'required' => true],
				'sequence'     => ['type' => 'integer', 'default' => 5, 'required' => true],
				'type'		   => ['type' => 'selection', 'selection' => array('form' => 'Form', 'tree' => 'List', 'template' => 'Template Qweb', 'bundle' => 'Assets Bundle'), 'default' => 'template', 'required' => true],
				'active'	   => ['type' => 'boolean'],
				'inherit_id'   => ['type' => 'integer'],
				//'date_created' => ['type' => 'datetime', 'value' => new \DateTime()]
		));
	}
	
	public static function relations(\Spot\MapperInterface $mapper, \Spot\EntityInterface $entity){
		return [
			'inherit' => $mapper->belongsTo($entity, 'addons\ir\model\IrView', 'inherit_id')
		];
	}
	
	public static function relationsKK(\Spot\MapperInterface $mapper, \Spot\Entity $entity){
		return [
			'inherit' => $mapper->belongsTo($entity, 'addons\ir\model\IrView', 'inherit_id')
		];
	}
}
