<?php
/**
 * Maes Jerome
 * test.php, created at Apr 19, 2015
 *
 */

namespace system;

use system\core\BlackModel as BlackModel;

class Post extends BlackModel{
	
	protected static $table = 'posts';
	
	public static function fields(){
		$fields = parent::fields();
		return array_merge($fields, array(
			'title'        => ['type' => 'string', 'required' => true],
			'body'         => ['type' => 'text', 'required' => true],
			'status'       => ['type' => 'integer', 'default' => 0, 'index' => true],
			'author_id'    => ['type' => 'integer', 'required' => true],
			'date_created' => ['type' => 'datetime', 'value' => new \DateTime()]
		));
	}
	
}