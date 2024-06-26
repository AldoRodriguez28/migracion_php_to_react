<?php
/**
* PDO Database class
*
* @author:    	Evert Ulises German Soto
* @copyright: 	wArLeY996 2012
* @version: 	2.1
*/

class wArLeY_DBMS{

	private $database_types = array("sqlite2","sqlite3","sqlsrv","mssql","mysql","pg","ibm","dblib","odbc","oracle","ifmx","fbd");

	private $host;
	private $database;
	private $user;
	private $password;
	private $port;
	private $database_type;
	private $root_mdb;

	private $sql;
	private $con;
	private $err_msg = "";

	/**
	* Constructor of class - Initializes class and connects to the database
	* @param string $database_type the name of the database type
	* @param string $host the host of the database
	* @param string $database the name of the database
	* @param string $user the name of the user for the database
	* @param string $password the passord of the user for the database
	*/

	//Initialize class and connects to the database
	public function __construct($database_type,$host,$database,$user,$password,$port){
		$this->database_type = strtolower($database_type);
		$this->host = $host;
		$this->database = $database;
		$this->user = $user;
		$this->password = $password;
		$this->port = $port;
	}

	//Initialize class and connects to the database
	public function Cnxn(){
		if(in_array($this->database_type, $this->database_types)){
			try{
				switch($this->database_type){
					case "mssql":
						$this->con = new PDO("mssql:host=".$this->host.";dbname=".$this->database, $this->user, $this->password);
						break;
					case "sqlsrv":
						$this->con = new PDO("sqlsrv:server=".$this->host.";database=".$this->database, $this->user, $this->password);
						break;
					case "ibm": //default port = ?
						$this->con = new PDO("ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE=".$this->database."; HOSTNAME=".$this->host.";PORT=".$this->port.";PROTOCOL=TCPIP;", $this->user, $this->password);
						break;
					case "dblib": //default port = 10060
						$this->con = new PDO("dblib:host=".$this->host.":".$this->port.";dbname=".$this->database,$this->user,$this->password);
						break;
					case "odbc":
						$this->con = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=C:\accounts.mdb;Uid=".$this->user);
						break;
					case "oracle":
						$this->con = new PDO("OCI:dbname=".$this->database.";charset=UTF-8", $this->user, $this->password);
						break;
					case "ifmx":
						$this->con = new PDO("informix:DSN=InformixDB", $this->user, $this->password);
						break;
					case "fbd":
						$this->con = new PDO("firebird:dbname=".$this->host.":".$this->database, $this->user, $this->password);
						break;
					case "mysql":
						$this->con = (is_numeric($this->port)) ? new PDO("mysql:host=".$this->host.";port=".$this->port.";dbname=".$this->database, $this->user, $this->password) : new PDO("mysql:host=".$this->host.";dbname=".$this->database, $this->user, $this->password);
						break;
					case "sqlite2": //ej: "sqlite:/path/to/database.sdb"
						$this->con = new PDO("sqlite:".$this->host);
						break;
					case "sqlite3":
						$this->con = new PDO("sqlite::memory");
						break;
					case "pg":
						$this->con = (is_numeric($this->port)) ? new PDO("pgsql:dbname=".$this->database.";port=".$this->port.";host=".$this->host, $this->user, $this->password) : new PDO("pgsql:dbname=".$this->database.";host=".$this->host, $this->user, $this->password);
						break;
					default:
						$this->con = null;
						break;
				}

				$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				//$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
				//$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
				//$this->con->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY => true);

				return $this->con;
			}catch(PDOException $e){
				$this->err_msg = "Error: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Error: Error establishing a database connection (error in params or database not supported).";
			return false;
		}
	}

	//Retrieve connection properties
	public function properties(){
		echo "<span style='display:block;color:#267F00;background:#F4FFEF;border:2px solid #267F00;padding:2px 4px 2px 4px;margin-bottom:5px;'>";
		print_r("<b>DATABASE:</b>&nbsp;".$this->con->getAttribute(PDO::ATTR_DRIVER_NAME)."&nbsp;".$this->con->getAttribute(PDO::ATTR_SERVER_VERSION)."<br/>");
		print_r("<b>STATUS:</b>&nbsp;".$this->con->getAttribute(PDO::ATTR_CONNECTION_STATUS)."<br/>");
		print_r("<b>CLIENT:</b><br/>".$this->con->getAttribute(PDO::ATTR_CLIENT_VERSION)."<br/>");
		print_r("<b>INFORMATION:</b><br/>".$this->con->getAttribute(PDO::ATTR_SERVER_INFO));
		echo "</span>";
	}

	//Retrieve all drivers capables
	public function drivers(){
		print_r(PDO::getAvailableDrivers()); 
	}

	//Execute the transactional operations
	public function transaction($arg){
		$this->err_msg = "";
		if($this->con!=null){
			try{
				if($arg=="B")
					$this->con->beginTransaction();
				elseif($arg=="C")
					$this->con->commit();
				elseif($arg=="R")
					$this->con->rollBack();
				else{
					$this->err_msg = "Error: The passed param is wrong! just allow [B=begin, C=commit or R=rollback]";
					return false;
				}
			}catch(PDOException $e){
				$this->err_msg = "Error: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Iterate over rows
	public function query($sql_statement){
		$this->err_msg = "";
		if($this->con!=null){
			try{
				$this->sql=$sql_statement;
				return $this->con->query($this->sql);
				/*
				$sttmnt = $this->con->prepare($this->sql);
				$sttmnt->fetchAll(PDO::FETCH_OBJ);
				$sttmnt->setFetchMode(PDO::FETCH_OBJ);
				$sttmnt->execute();
				return $sttmnt;
				*/
			}catch(PDOException $e){
				$this->err_msg = "Error: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Querys Anti SQL Injections
	public function query_secure($sql_statement, $params, $fetch_rows=false, $unnamed=false, $delimiter="|"){
		$this->err_msg = "";
		if(!isset($unnamed)) $unnamed = false;
		if(trim((string)$delimiter)==""){
			$this->err_msg = "Error: Delimiter are required.";
			return false;
		}
		if($this->con!=null){
			$obj = $this->con->prepare($sql_statement);
			if(!$unnamed){
				for($i=0;$i<count($params);$i++){
					$params_split = explode($delimiter,$params[$i]);
					(trim($params_split[2])=="INT") ? $obj->bindParam($params_split[0], $params_split[1], PDO::PARAM_INT) : $obj->bindParam($params_split[0], $params_split[1], PDO::PARAM_STR);
				}
				try{
					$obj->execute();
				}catch(PDOException $e){
					$this->err_msg = "Error: ". $e->getMessage();
					return false;
				}
			}else{
				try{
					$obj->execute($params);
				}catch(PDOException $e){
					$this->err_msg = "Error: ". $e->getMessage();
					return false;
				}
			}
			if($fetch_rows)
				return $obj->fetchAll();
			if(is_numeric($this->con->lastInsertId()))
				return $this->con->lastInsertId();
			return true;
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Fetch the first row
	public function query_first($sql_statement){
		$this->err_msg = "";
		if($this->con!=null){
			try{
				$sttmnt = $this->con->prepare($sql_statement);
				$sttmnt->execute();
				return $sttmnt->fetch();
			}catch(PDOException $e){
				$this->err_msg = "Error: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Select single table cell from first record
	public function query_single($sql_statement){
		$this->err_msg = "";
		if($this->con!=null){
			try{
				$sttmnt = $this->con->prepare($sql_statement);
				$sttmnt->execute();
				return $sttmnt->fetchColumn();
			}catch(PDOException $e){
				$this->err_msg = "Error: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Return total records from query as integer
	public function rowcount(){
		$this->err_msg = "";
		if($this->con!=null){
			try{
				$stmnt_tmp = $this->stmntCount($this->sql);
				if($stmnt_tmp!=false && $stmnt_tmp!=""){
					return $this->query_single($stmnt_tmp);
				}else{
					$this->err_msg = "Error: A few data required.";
					return -1;
				}
			}catch(PDOException $e){
				$this->err_msg = "Error: ". $e->getMessage();
				return -1;
			}
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Return name columns as vector
	public function columns($table){
		$this->err_msg = "";
		$this->sql="SELECT * FROM $table";
		if($this->con!=null){
			try{
				$q = $this->con->query($this->sql);
				$column = array();
				foreach($q->fetch(PDO::FETCH_ASSOC) as $key=>$val){
					$column[] = $key;
				}
				return $column;
			}catch(PDOException $e){
				$this->err_msg = "Error: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Insert and get newly created id
	public function insert($table, $data){
		$this->err_msg = "";
		if($this->con!=null){
			try{
				$txt_fields = "";
				$txt_values = "";
				$data_column = explode(",", $data);
				for($x=0;$x<count($data_column);$x++){
					list($field, $value) = explode("=", $data_column[$x]);
					$txt_fields.= ($x==0) ? $field : ",".$field;
					$txt_values.= ($x==0) ? $value : ",".$value;
				}
				$this->con->exec("INSERT INTO ".$table." (".$txt_fields.") VALUES(".$txt_values.");");
				return $this->con->lastInsertId();
			}catch(PDOException $e){
				$this->err_msg = "Error: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Update tables
	public function update($table, $data, $condition=""){
		$this->err_msg = "";
		if($this->con!=null){
			try{
				return (trim($condition)!="") ? $this->con->exec("UPDATE ".$table." SET ".$data." WHERE ".$condition.";") : $this->con->exec("UPDATE ".$table." SET ".$data.";");
			}catch(PDOException $e){
				$this->err_msg = "Error: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Delete records from tables
	public function delete($table, $condition=""){
		$this->err_msg = "";
		if($this->con!=null){
			try{
				return (trim($condition)!="") ? $this->con->exec("DELETE FROM ".$table." WHERE ".$condition.";") : $this->con->exec("DELETE FROM ".$table.";");
			}catch(PDOException $e){
				$this->err_msg = "Error: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Execute Store Procedures
	public function execute($sp_query){
		$this->err_msg = "";
		if($this->con!=null){
			try{
				$this->con->exec($sp_query);
				return true;
			}catch(PDOException $e){
				$this->err_msg = "Error: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Get latest specified id from specified table
	public function getLatestId($db_table, $table_field){
		$this->err_msg = "";
		$sql_statement = "";
		$dbtype = $this->database_type;

		if($dbtype=="sqlsrv" || $dbtype=="mssql" || $dbtype=="ibm" || $dbtype=="dblib" || $dbtype=="odbc"){
			$sql_statement = "SELECT TOP 1 ".$table_field." FROM ".$db_table." ORDER BY ".$table_field." DESC;";
		}elseif($dbtype=="oracle"){
			$sql_statement = "SELECT ".$table_field." FROM ".$db_table." WHERE ROWNUM<=1 ORDER BY ".$table_field." DESC;";
		}elseif($dbtype=="ifmx" || $dbtype=="fbd"){
			$sql_statement = "SELECT FIRST 1 ".$table_field." FROM ".$db_table." ORDER BY ".$table_field." DESC;";
		}elseif($dbtype=="mysql" || $dbtype=="sqlite2" || $dbtype=="sqlite3"){
			$sql_statement = "SELECT ".$table_field." FROM ".$db_table." ORDER BY ".$table_field." DESC LIMIT 1;";
		}elseif($dbtype=="pg"){
			$sql_statement = "SELECT ".$table_field." FROM ".$db_table." ORDER BY ".$table_field." DESC LIMIT 1 OFFSET 0;";
		}

		if($this->con!=null){
			try{
				return $this->query_single($sql_statement);
			}catch(PDOException $e){
				$this->err_msg = "Error: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Get all tables from specified database
	public function ShowTables($database){
		$this->err_msg = "";
		$complete = "";
		$sql_statement = "";
		$dbtype = $this->database_type;

		if($dbtype=="sqlsrv" || $dbtype=="mssql" || $dbtype=="ibm" || $dbtype=="dblib" || $dbtype=="odbc" || $dbtype=="sqlite2" || $dbtype=="sqlite3"){
			$sql_statement = "SELECT name FROM sysobjects WHERE xtype='U';";
		}elseif($dbtype=="oracle"){
			//If the query statement fail, try with uncomment the next line:
			//$sql_statement = "SELECT table_name FROM tabs;";
			$sql_statement = "SELECT table_name FROM cat;";
		}elseif($dbtype=="ifmx" || $dbtype=="fbd"){
			$sql_statement = 'SELECT RDB$RELATION_NAME FROM RDB$RELATIONS WHERE RDB$SYSTEM_FLAG = 0 AND RDB$VIEW_BLR IS NULL ORDER BY RDB$RELATION_NAME;';
		}elseif($dbtype=="mysql"){
			if($database!="") $complete=" FROM $database";
			$sql_statement = "SHOW tables ".$complete.";";
		}elseif($dbtype=="pg"){
			$sql_statement = "SELECT relname AS name FROM pg_stat_user_tables ORDER BY relname;";
		}

		if($this->con!=null){
			try{
				$this->sql=$sql_statement;
				return $this->con->query($this->sql);
			}catch(PDOException $e){
				$this->err_msg = "Error: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Get all databases from your server
	public function ShowDBS(){
		$this->err_msg = "";
		$sql_statement = "";
		$dbtype = $this->database_type;

		if($dbtype=="sqlsrv" || $dbtype=="mssql" || $dbtype=="ibm" || $dbtype=="dblib" || $dbtype=="odbc" || $dbtype=="sqlite2" || $dbtype=="sqlite3"){
			$sql_statement = "SELECT name FROM sys.Databases;";
		}elseif($dbtype=="oracle"){
			//If the query statement fail, try with uncomment the next line:
			//$sql_statement = "SELECT * FROM user_tablespaces";
			$sql_statement = 'SELECT * FROM v$database;';
		}elseif($dbtype=="ifmx" || $dbtype=="fbd"){
			$sql_statement = "";
		}elseif($dbtype=="mysql"){
			$sql_statement = "SHOW DATABASES;";
		}elseif($dbtype=="pg"){
			$sql_statement = "SELECT datname AS name FROM pg_database;";
		}

		if($this->con!=null){
			try{
				$this->sql=$sql_statement;
				return $this->con->query($this->sql);
			}catch(PDOException $e){
				$this->err_msg = "Error: ". $e->getMessage();
				return false;
			}
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Get the latest error ocurred in the connection
	public function getError(){
		return trim($this->err_msg)!="" ? $this->err_msg : "";
	}

	//Disconnect database
	public function disconnect(){
		$this->err_msg = "";
		if($this->con){
			$this->con = null;
			return true;
		}else{
			$this->err_msg = "Error: Connection to database lost.";
			return false;
		}
	}

	//Build the query neccesary for the count(*) in rowcount method
	private function stmntCount($query_stmnt){
		if(trim($query_stmnt)!=""){
			$query_stmnt = trim($query_stmnt);
			$query_split = explode(" ",$query_stmnt);
			$query_flag = false;
			$query_final = "";

			for($x=0;$x<count($query_split);$x++){
				//Checking "SELECT"
				if($x==0 && strtoupper(trim($query_split[$x]))=="SELECT")
					$query_final = "SELECT count(*) ";
				if($x==0 && strtoupper(trim($query_split[$x]))!="SELECT")
					return false;

				//Checking "FROM"
				if(strtoupper(trim($query_split[$x]))=="FROM"){
					$query_final .= "FROM ";
					$query_flag = true;
					continue;
				}

				//Checking "ORDER"
				if(strtoupper(trim($query_split[$x]))=="ORDER" || strtoupper(trim($query_split[$x]))=="GROUP"){
					break;
				}

				//Building the query
				if(trim($query_split[$x])!="" && $query_flag)
					$query_final .= " " . trim($query_split[$x]) . " ";
			}
			return trim($query_final);
		}
		return false;
	}
}
?>