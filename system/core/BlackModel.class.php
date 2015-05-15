<?php
/**
 * Maes Jerome
 * BlackModel.class.php, created at Apr 22, 2015
 *
 */

namespace system\core;

class BlackModel extends \Spot\Entity{
	
	public static function fields(){
		$fields = parent::fields();
		$mandatory_fields = array(
			'id' => ['type' => 'integer', 'autoincrement' => true, 'primary' => true, 'index' => true]
		);
		return array_merge($fields, $mandatory_fields);
	}
	
}