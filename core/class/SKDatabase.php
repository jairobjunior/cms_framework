<?php
class SKDatabase {

	//internal info
	var $errno = 0;

	//number of rows affected by SQL query
	var $affected_rows = 0;

	var $connection = 0;

	private $error;

	var $query_id = 0;

	private static $SKDatabase = null;

	private function __construct(){
		$this->error = array();
		$this->connect();
	}

	public static function getInstance(){
		if (self::$SKDatabase === null) 
			self::$SKDatabase = new SKDatabase();
		return self::$SKDatabase;
	}

	public function __destruct(){ mysql_close($this->connection); }

	public function connect(){
		$this->connection = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("Não foi possível conectar ao banco de dados, verifique os seus dados.");
		mysql_select_db(DB_NAME, $this->connection) or die("O banco de dados informado não existe.");
		$this->query("SET NAMES '".CHARSET."'"); //persian support
	}

	public function disconnect(){
		mysql_close($this->connection);
		$this->connection = null;
	}

	public function query($sql){
		$result = mysql_query($sql, $this->connection);
		if (!$result) {
			die('Invalid query: ' . mysql_error());
		}
		return $result;
	}

	public function find($sql){
		$rows = array();
		$result = $this->query($sql);
		while($r = mysql_fetch_assoc($result)) {
			$rows[] = $r;
		}
		mysql_free_result($result);
		return $rows;
	}
	
	// Coloca a chave do array o valor de um campo.
	public function find_with_key($sql,$key){
		$rows = array();
		$result = $this->query($sql);
		while($r = mysql_fetch_assoc($result)) {
			$rows[$r[$key]] = $r;
		}
		mysql_free_result($result);
		return $rows;
	}

	public function save($table, $data) {
		$query = "INSERT INTO `".$table."` ";
		$values = ''; $columns = '';

		foreach ($data as $key => $val) {
			$columns .= "`$key`, ";
			if(strtolower($val)=='null') $values .= "NULL, ";
			elseif(strtolower($val)=='now()') $values .= "NOW(), ";
			else $values .= "'".$this->escape($val)."', ";
		}

		$query .= "(". rtrim($columns, ', ') .") VALUES (". rtrim($values, ', ') .");";

		if ($this->query($query)) {
			return mysql_insert_id();
		}
		return false;
	}

	function escape($string) {
		if(get_magic_quotes_runtime()) $string = stripslashes($string);
		return mysql_real_escape_string($string);
	}

	/*
	 #-#############################################
	 # desc: close the connection
	 function close() {
	 if(!@mysql_close($this->connection)){
	 $this->oops("Connection close failed.");
	 }
	 }#-#close()


	 #-#############################################
	 # Desc: escapes characters to be mysql ready
	 # Param: string
	 # returns: string
	 function escape($string) {
	 if(get_magic_quotes_runtime()) $string = stripslashes($string);
	 return mysql_real_escape_string($string);
	 }#-#escape()


	 #-#############################################
	 # Desc: executes SQL query to an open connection
	 # Param: (MySQL query) to execute
	 # returns: (query_id) for fetching results etc
	 function query($sql) {
	 // do query
	 $this->query_id = @mysql_query($sql, $this->connection);

	 if (!$this->query_id) {
	 $this->oops("<b>MySQL Query fail:</b> $sql");
	 return 0;
	 }

	 $this->affected_rows = @mysql_affected_rows($this->connection);

	 return $this->query_id;
	 }#-#query()


	 #-#############################################
	 # desc: fetches and returns results one line at a time
	 # param: query_id for mysql run. if none specified, last used
	 # return: (array) fetched record(s)
	 function fetch_array($query_id=-1) {
	 // retrieve row
	 if ($query_id!=-1) {
	 $this->query_id=$query_id;
	 }

	 if (isset($this->query_id)) {
	 $record = @mysql_fetch_assoc($this->query_id);
	 }else{
	 $this->oops("Invalid query_id: <b>$this->query_id</b>. Records could not be fetched.");
	 }

	 return $record;
	 }#-#fetch_array()


	 #-#############################################
	 # desc: returns all the results (not one row)
	 # param: (MySQL query) the query to run on server
	 # returns: assoc array of ALL fetched results
	 function fetch_all_array($sql) {
	 $query_id = $this->query($sql);
	 $out = array();

	 while ($row = $this->fetch_array($query_id, $sql)){
	 $out[] = $row;
	 }

	 $this->free_result($query_id);
	 return $out;
	 }#-#fetch_all_array()


	 #-#############################################
	 # desc: frees the resultset
	 # param: query_id for mysql run. if none specified, last used
	 function free_result($query_id=-1) {
	 if ($query_id!=-1) {
	 $this->query_id=$query_id;
	 }
	 if($this->query_id!=0 && !@mysql_free_result($this->query_id)) {
	 $this->oops("Result ID: <b>$this->query_id</b> could not be freed.");
	 }
	 }#-#free_result()


	 #-#############################################
	 # desc: does a query, fetches the first row only, frees resultset
	 # param: (MySQL query) the query to run on server
	 # returns: array of fetched results
	 function query_first($query_string) {
	 $query_id = $this->query($query_string);
	 $out = $this->fetch_array($query_id);
	 $this->free_result($query_id);
	 return $out;
	 }#-#query_first()


	 #-#############################################
	 # desc: does an update query with an array
	 # param: table (no prefix), assoc array with data (doesn't need escaped), where condition
	 # returns: (query_id) for fetching results etc
	 function query_update($table, $data, $where='1') {
	 $q="UPDATE `".$this->pre.$table."` SET ";

	 foreach($data as $key=>$val) {
	 if(strtolower($val)=='null') $q.= "`$key` = NULL, ";
	 elseif(strtolower($val)=='now()') $q.= "`$key` = NOW(), ";
	 else $q.= "`$key`='".$this->escape($val)."', ";
	 }

	 $q = rtrim($q, ', ') . ' WHERE '.$where.';';

	 return $this->query($q);
	 }#-#query_update()


	 #-#############################################
	 # desc: does an insert query with an array
	 # param: table (no prefix), assoc array with data
	 # returns: id of inserted record, false if error
	 function query_insert($table, $data) {
	 $q="INSERT INTO `".$this->pre.$table."` ";
	 $v=''; $n='';

	 foreach($data as $key=>$val) {
	 $n.="`$key`, ";
	 if(strtolower($val)=='null') $v.="NULL, ";
	 elseif(strtolower($val)=='now()') $v.="NOW(), ";
	 else $v.= "'".$this->escape($val)."', ";
	 }

	 $q .= "(". rtrim($n, ', ') .") VALUES (". rtrim($v, ', ') .");";

	 if($this->query($q)){
	 //$this->free_result();
	 return mysql_insert_id();
	 }
	 else return false;

	 }#-#query_insert()


	 #-#############################################
	 # desc: throw an error message
	 # param: [optional] any custom error to display
	 function oops($msg='') {
	 if($this->connection>0){
	 $this->error=mysql_error($this->connection);
	 $this->errno=mysql_errno($this->connection);
	 }
	 else{
	 $this->error=mysql_error();
	 $this->errno=mysql_errno();
	 }
	 ?>
	 <table align="center" border="1" cellspacing="0" style="background:white;color:black;width:80%;">
	 <tr><th colspan=2>Database Error</th></tr>
	 <tr><td align="right" valign="top">Message:</td><td><?php echo $msg; ?></td></tr>
	 <?php if(strlen($this->error)>0) echo '<tr><td align="right" valign="top" nowrap>MySQL Error:</td><td>'.$this->error.'</td></tr>'; ?>
	 <tr><td align="right">Date:</td><td><?php echo date("l, F j, Y \a\\t g:i:s A"); ?></td></tr>
	 <tr><td align="right">Script:</td><td><a href="<?php echo @$_SERVER['REQUEST_URI']; ?>"><?php echo @$_SERVER['REQUEST_URI']; ?></a></td></tr>
	 <?php if(strlen(@$_SERVER['HTTP_REFERER'])>0) echo '<tr><td align="right">Referer:</td><td><a href="'.@$_SERVER['HTTP_REFERER'].'">'.@$_SERVER['HTTP_REFERER'].'</a></td></tr>'; ?>
	 </table>
	 <?php
	 }#-#oops()
	 */

}//CLASS Database
###################################################################################################

?>
