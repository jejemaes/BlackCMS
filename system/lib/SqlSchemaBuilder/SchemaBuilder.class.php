<?php
/**
 * Maes Jerome
 * SchemaBuilder.class.php, created at May 17, 2015
 *
 */
namespace system\lib\SqlSchemaBuilder;
use \Spot\Relation\BelongsTo as BelongsTo;
use \Spot\Relation\HasMany as HasMany;
use \Spot\Relation\HasManyThrough as HasManyThrough;
use \Spot\Relation\HasOne as HasOne;


class SchemaBuilder{
	
	protected static $_instance;
	
	public static function getInstance(){
		if (!isset(self::$_instance)) {
			$c = __CLASS__;
			self::$_instance = new $c;
			self::$_instance->__construct();
		}
		return self::$_instance;
	}
	
	const DEFAULT_SIZE_INTEGER = 10;
	const DEFAULT_SIZE_STRING = 128;
	
	private $_connection;
	
	private function __construct(){
		
	}
	
	public function init($dbname, $dbuser, $dbpass, $dbhost, $dbdriver='pdo_mysql'){
		$connectionParams = array(
				'dbname'    => $dbname,
				'user'      => $dbuser,
				'password'  => $dbpass,
				'host'      => $dbhost,
				'driver'    => $dbdriver,
		);
		/* Connect to the database */
		$this->_connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
	}
	
	
	/*
	 $comparator = new \Doctrine\DBAL\Schema\Comparator();
$schemaDiff = $comparator->compare($fromSchema, $toSchema);

$queries = $schemaDiff->toSql($myPlatform); // queries to get from one to another schema.
$saveQueries = $schemaDiff->toSaveSql($myPlatform);

	 */
	
	public function generateQueryFor($modelClassName){
		$schema = new \Doctrine\DBAL\Schema\Schema();
		
		$table_name = $modelClassName::$table;
		
		// now use the Schema object to create a '$table_name' table
		$table = $schema->createTable($table_name);
		
		// generate the classic fields (not relationnal ones) of the table
		$field_definitions = $modelClassName::fields();
		$table = $this->_addColumns($table, $field_definitions);
		
		$platform = $this->_connection->getDatabasePlatform();
		
		$queries = $schema->toSql($platform);
		return $queries;
		
	}
	
	
	public function addTable($entity_name, $schema=FALSE){
		// create schema if needed
		if(!$schema){
			$schema = new \Doctrine\DBAL\Schema\Schema();
		}
		
		// now use the Schema object to create a '$table_name' table
		$table_name = $entity_name::table();
		$table = $schema->createTable($table_name);
		
		// generate the classic fields (not relationnal ones) of the table
		$field_definitions = $entity_name::fields();
		$table = $this->_addColumns($table, $field_definitions);
		
		// generate the relational fields of the table
		$mapper = App()->mapper($entity_name);
		echo '<hr>acaca';
		$field_definitions = $entity_name::relations($mapper, new $entity_name());
		$table = $this->_addRelationalColumns($schema, $table, $field_definitions);
		
		
		$platform = $this->_connection->getDatabasePlatform();
		return $schema->toSql($platform);
	}
	
	/**
	 * add the simple scalar columns (not relationnal ones) to the given table
	 * @param Table $table : the table to add columns
	 * @param key-array $field_definitions : definitions of columns. 
	 * 						- 'key' is the name of the colum, 
	 * 						- 'value is a key-array with columns properties
	 */
	private function _addColumns($table, $field_definitions){
		global $Logger;
		foreach ($field_definitions as $field_name => $field_definition){
			$method = '_addColumn' . ucfirst($field_definition['type']);
		 	if (method_exists($this, $method)) {
                $column = $this->$method($table, $field_name, $field_definition);
                
                $common_options = array();
                // INDEX on column
                // TODO : will not work : use addIndex() method
                if(array_key_exists('index', $field_definition) && $field_definition['index']){
                	$common_options['index'] = True;
                }
                // NOT NULL constraint
                $common_options['notnull'] = False;
                if(array_key_exists('required', $field_definition) && $field_definition['required']){
                	$common_options['notnull'] = True;
                }
                // DEFAULT value of the column
                if(array_key_exists('default', $field_definition)){
                	$common_options['default'] = $field_definition['default'];
                }
                // PRIMARY value of the column
                if(array_key_exists('primary', $field_definition) && $field_definition['primary']){
                	$table->setPrimaryKey(array($field_name), $field_name . '_primary_key');
                }
                // UNIQUE constraint
                if(array_key_exists('unique', $field_definition) && $field_definition['unique']){
                	$table->addUniqueIndex(array($field_name));
                }
                $column->setOptions($common_options); 
            }else{
            	$Logger->warn('DBAL : Not type ' . $field_definition['type'] . ' for column ' . $field_name);
            }
		}
	}
	
	private function _addColumnInteger($table, $field_name, $field_definition){
		$column = $table->addColumn($field_name, "integer");
		$options = array(
			'autoincrement' => array_key_exists('autoincrement', $field_definition),
			'unsigned' => array_key_exists('autoincrement', $field_definition),
			'size' => array_key_exists('size', $field_definition) ? array_key_exists('size', $field_definition) : self::DEFAULT_SIZE_INTEGER,
		);
		return $column->setOptions($options);
	}
	
	private function _addColumnString($table, $field_name, $field_definition){
		$column = $table->addColumn($field_name, "string");
		$options = array(
			'lenght' => array_key_exists('size', $field_definition) ? array_key_exists('size', $field_definition) : self::DEFAULT_SIZE_STRING,
		);
		return $column->setOptions($options);
	}
	
	private function _addColumnText($table, $field_name, $field_definition){
		$column = $table->addColumn($field_name, "text");
		return $column;
	}
	
	private function _addColumnBoolean($table, $field_name, $field_definition){
		$column = $table->addColumn($field_name, "boolean");
		return $column;
	}
	
	private function _addColumnDatetime($table, $field_name, $field_definition){
		$column = $table->addColumn($field_name, "datetime");
		return $column;
	}
	
	private function _addColumnSelection($table, $field_name, $field_definition){
		// TODO : make an ENUM or check server side the selection !
		$column = $table->addColumn($field_name, "string");
		$options = array(
			'size' => array_key_exists('size', $field_definition) ? array_key_exists('size', $field_definition) : self::DEFAULT_SIZE_INTEGER,
		);
		return $column->setOptions($options);
	}
	
	
	
	private function _addRelationalColumns($schema, $current_table, $field_definitions){
		foreach ($field_definitions as $field_name => $relation){
			// Many2one
			if($relation instanceOf BelongsTo){
				$entity_name = $relation->entityName();
				$foreign_key_field_name = $relation->foreignKey();
				
				var_dump($schema->getTableNames());
				$foreign_table = $this->addTable($entity_name, $schema);
				
				$current_table->addForeignKeyConstraint($foreign_table, array($foreign_key_field_name), array("id"), array("onUpdate" => "CASCADE"));
			}
			// One2many
			// TODO
			// Many2many
			// TODO
			// Many2manyThrought ?
			// TODO
		}
		return $current_table;
	}
}