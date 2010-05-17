<?php
require(CORE."/class/SKDatabase.php");

abstract class SKModel {

	const NO_PUBLISHED = 0;
	const PUBLISHED = 1;
	const DRAFT = 2;

	private static $params = array('fields' => '*', 'where' => 1, 'join' => '', 'order' => '`order` DESC','group_by'=>'','include'=>array());

	private $imported_functions = array();

	public $connection;

	protected $uses = array();
	private $usesColumns = array('trash' => '`deleted` = 0', 'status' => '`status` = 1');
	private $noFlagTables = array('core_comments');

	public $table = "";
	public $primaryKey = "id";

	public function __construct() {
		$this->connection = SKDatabase::getInstance();
		if (empty($this->table)) {
			$this->table = strtolower(get_class($this));
		}
	}

	public function __call($method, $args) {
		// Verifica se realmente existe o método desejado
		if(array_key_exists($method, $this->imported_functions)) {
//			$args[] = $this;
			return call_user_func_array(array($this->imported_functions[$method], $method), $args);
		}
		throw new Exception ('Verifique se você chamou o método import no modelo: ' . $method);
	}

	protected function imports($class) {
		// TODO: 
		require_once CORE."/models/behaviors/".$class.".php";
		//Instância o objeto correspondente a classe passada.
		$new_import = new $class(&$this);
		//Obtém os métodos da classe
		$import_functions = get_class_methods($new_import);
		//Adiciona os métodos da classe informada
		foreach($import_functions as $function_name) {
			$this->imported_functions[$function_name] = &$new_import;
		}
	}

	public function query($sql) {
		return $this->connection->find($sql);
	}
	
	public function execute($sql) {
		return $this->connection->query($sql);
	}

	public function findAll($params = array()) {
		//Implementar label e tags
		/*	array(
		 'label' => 'cidade=arapiraca|cidade=maceio',
		 'tags' => 'esporte,ronaldinho',
		 'fields' => 'author, title',
		 'limit' => '10',
		 'where' => 'name = danillo',
		 'order' => 'DESC'
		 )*/

		 $params = array_merge(self::$params, $params);

		
		// SELECTS
		if (!empty($params['select'])) {
			if(!$selects = $this->findAllModelsUsingSelector(&$params)){
				return false;
			}
		}

		// TAGS
		if (!empty($params['tags'])) {
			if(!$tags = $this->findAllWithTags(&$params)){
				return false;
			}
		}

		$sql = "SELECT ".$params['fields']." FROM ".$this->table;
		$sql .= " ".$params['join']." ";					 
		$sql .=	" WHERE ".$this->getStringWhere($params['where']);
		$sql .= " ".$params['group_by'];
		$sql .= " ORDER BY ".$params['order'];
		$sql .= (!empty($params['limit'])? " LIMIT ".$params['limit']:"");
		

		$records = $this->connection->find_with_key($sql,$this->primaryKey);
		
		
		$record_size = count($records);
		if($record_size === 0){
			return false;
		}
		
		// Se não tiver algum include já retorna.
		if(count($params['include']) === 0){
			return $records; 
		}
		
		
		$ids = array();
		foreach ($records as $record) {
			$ids[] = "'".$record['id']."'";
		}
		$ids = join(',',$ids);
		
		// Inclui nos registros seus selects e options.
		if(in_array('selects',$params['include'])){
			// Se já houver selects não consulta
			if(empty($selects)){
				$name = get_class($this);
				if(!empty($this->name)) $name = $this->name;
				$recordType = "Modules::".$name;
				
				$selects =$this->connection->find('SELECT * FROM core_select_options_records WHERE record_type = \''.$recordType.'\' AND record_id IN ('.$ids.')');
			}
			foreach ($selects as $select) {
				if(is_array($records[$select['record_id']]['selects'])){
					$records[$select['record_id']]['selects'][$select['select_type_alias']] = $select['select_option_name'];
				}else{
					$records[$select['record_id']]['selects'] = array($select['select_type_alias'] => $select['select_option_name']);
				}
			}
		}
		
		
		
		// Adiciona as tags ao resultado
		if(in_array('tags',$params['include'])){
			
			// Busca as tags de cada registro.
			$name = get_class($this);
			if(!empty($this->name)) $name = $this->name;
			$recordType = "Modules::".$name;
			$tags = $this->connection->find('SELECT * FROM core_module_records_tags WHERE record_type = \''.$recordType.'\' AND record_id IN ('.$ids.')');
			
			// Seta as tags nos registros como array vazio
			foreach ($records as $key => $value) {
				$records[$key]['tags'] = array();
			}

			// Junta as tags ao registro.
			foreach ($tags as $tag) {
	 			if(is_array($records[$tag['record_id']]['tags'])) {
	 				$records[$tag['record_id']]['tags'][$tag['core_tag_id']] = $tag['tag_name'];
	 			}else {
	 				$records[$tag['record_id']]['tags'] = array($tag['core_tag_id'] => $tag['tag_name']);
	 			}
	 		}
	
		}
		return $records; 
	}
	
	
	
	//Adiciona a cláusula WHERE os valores a serem utilizados pelos modulos
	public function getStringWhere($paramWhere) {
		if (!in_array($this->table, $this->noFlagTables)) {
			foreach ($this->uses as $key) {
				$paramWhere .= " AND ".$this->usesColumns[$key];
			}
		}
		return $paramWhere;
	}
	
	
	public function findFirst($params = array()) {
		$params = array_merge(self::$params, $params);
		$params['limit'] = 1;
		$record = $this->findAll($params);
		if(!$record) return false;
		$record = array_pop($record);
		return (!empty($record)? $record : false);
	}
	
	
	public function find($id, $params = array()) {
		$params['where'] = $this->table.".".$this->primaryKey." = ".$id . (!empty($params['where'])? " AND ".$params['where']:"");
		$params['limit'] = 1;
		$record = $this->findAll($params);
		if(!$record) return false;
		$record = array_pop($record);
		return (!empty($record)? $record:array());
	}
	
	//TODO Criar método findFirst

	public function save($data) {
		return $this->connection->save($this->table, $data);
	}
}
?>
