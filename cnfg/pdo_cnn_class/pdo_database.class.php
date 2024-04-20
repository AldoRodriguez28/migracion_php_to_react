<?php
/**
* PDO Database class
*
* @author:    	Evert Ulises German Soto
* @copyright: 	wArLeY996 2011
* @version: 	1.5
*
* v1.5: Se agregaron dos nuevas funciones: 1.- ShowTables and 2.- ShowDBS
* v1.4: Se controlan los mensajes de error.
* v1.3: Se reparo un error, la funcion "insert" solo funcionaba para MySQL y SQLSrv.
* v1.2: Se agrego la libreria "mssql", y se agrego tambien la funcion getLatestId(tabla, id)
* v1.1: Al ejecutar un insert, delete o update, regresa el total de renglones afectados.
* v1.0: Se creo la clase funcional.
*/

class wArLeY_DBMS{

	var $database_types="";
	
	var $host;
	var $database;
	var $user;
	var $password;
	var $port;
	var $database_type;
	var $root_mdb;
	
	var $sql;	
	var $con;
	
	/**
	* Constructor of class - Initializes class and connects to the database
	* @param string $database_type the name of the database (sqlite2=SQLite2,sqlite3=SQLite3,sqlsrv=MS SQL,mssql=MS SQL,mysql=MySQL,pg=PostgreSQL,ibm=IBM,dblib=DBLIB,odbc=Microsoft Access,oracle=ORACLE,ifmx=Informix,fbd=Firebird)
	* @param string $host the host of the database
	* @param string $database the name of the database
	* @param string $user the name of the user for the database
	* @param string $password the passord of the user for the database
	*
	*  You can use this shortcuts for the database type:
	*
	* 		sqlite2	-> SQLite2
	* 		sqlite3	-> SQLite3
	* 		sqlsrv 	-> Microsoft SQL Server (Works under Windows, accept all SQL Server versions [max version 2008]) - TESTED
	* 		mssql 	-> Microsoft SQL Server (Works under Windows and Linux, but just work with SQL Server 2000) - TESTED
	* 		mysql 	-> MySQL - TESTED
	* 		pg 		-> PostgreSQL - TESTED
	*		ibm		-> IBM
	*		dblib	-> DBLIB
	*		odbc	-> Microsoft Access
	*		oracle	-> ORACLE
	*		ifmx 	-> Informix
	*		fbd		-> Firebird - TESTED
	*/
	
	//Initialize class and connects to the database
	function wArLeY_DBMS($database_type,$host,$database,$user,$password, $port){			
		try {
			$database_type=strtolower($database_type);
			$this->host=$host;
			$this->database=$database;
			$this->user=$user;
			$this->password=$password;
			$this->port=$port;
			
			$this->database_types=array("sqlite2","sqlite3","sqlsrv","mssql","mysql","pg","ibm","dblib","odbc","oracle","ifmx","fbd");
			
			if(in_array($database_type, $this->database_types)){		
				$this->database_type=$database_type;
		
				if($this->database_type=="mssql"){
					$this->con = new PDO("mssql:host=$host;dbname=$database", $user, $password);
				}
				if($this->database_type=="sqlsrv"){
					$this->con = new PDO("sqlsrv:server=$host;database=$database", $user, $password);
				}
				if($this->database_type=="ibm"){
					//default port = ?
					$this->con = new PDO("ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE=$database; HOSTNAME=$host;PORT=$port;PROTOCOL=TCPIP;", $user, $password);
				}
				if($this->database_type=="dblib"){
					//default port = 10060
					$this->con = new PDO("dblib:host=$host:$port;dbname=$database",$user,$password);
				}
				if($this->database_type=="odbc"){
					$this->con = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=C:\accounts.mdb;Uid=$user");
				}
				if($this->database_type=="oracle"){
					$this->con = new PDO("OCI:dbname=$database;charset=UTF-8", $user, $password);
				}
				if($this->database_type=="ifmx"){
					$this->con = new PDO("informix:DSN=InformixDB", $user, $password);
				}
				if($this->database_type=="fbd"){
					$this->con = new PDO("firebird:dbname=$host:$database", $user, $password);
				}
				if($this->database_type=="mysql"){
					$this->con = new PDO("mysql:host=$host;dbname=$database", $user, $password);
				}
				if($this->database_type=="sqlite2"){
					$this->con = new PDO("sqlite:/path/to/database.sdb");
				}
				if($this->database_type=="sqlite3"){
					$this->con = new PDO("sqlite::memory");
				}
				if($this->database_type=="pg"){
					if ($port!="")
						$this->con = new PDO("pgsql:dbname=$database;host=$host;port=$port", $user, $password);
					else
						$this->con = new PDO("pgsql:dbname=$database;host=$host", $user, $password);
				}
				
				$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				//$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
				//$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
				//$this->con->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY => true);
				
				/*
				print_r($db->getAttribute(PDO::ATTR_CONNECTION_STATUS));
				print_r($db->getAttribute(PDO::ATTR_DRIVER_NAME));
				print_r($db->getAttribute(PDO::ATTR_SERVER_VERSION));
				print_r($db->getAttribute(PDO::ATTR_CLIENT_VERSION));
				print_r($db->getAttribute(PDO::ATTR_SERVER_INFO));
				*/
			}
		} catch(PDOException $e) {
			//echo "Error: ". $e->getMessage();
			return false;
		}		
	}	
		
	//Iterate over rows
	function query($sql_statement){		
		if($this->con!=null){
			try {
				$this->sql=$sql_statement;
				return $this->con->query($this->sql);			
			} catch(PDOException $e) {
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	//Fetch the first row
	function query_first($sql_statement){
		if($this->con!=null){
			try {
				$sttmnt = $this->con->prepare($sql_statement);
				$sttmnt->execute();	
				return $sttmnt->fetch();
			} catch(PDOException $e) {
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	//Select single table cell from first record
	function query_single($sql_statement){
		if($this->con!=null){
			try {
				$sttmnt = $this->con->prepare($sql_statement);
				$sttmnt->execute();	
				return $sttmnt->fetchColumn();
			} catch(PDOException $e) {
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	//Return total records from query as integer
	function rowcount(){
		if($this->con!=null){
			try {	
				$rows = $this->con->query($this->sql);
				$count = 0;
				foreach($rows as $row){
					$count++;  //Not is the best way but works!
				}
				return $count;
			} catch(PDOException $e) {
				return -1;
			}
		}
		else{
			return false;
		}
	}
	
	//Return name columns as vector
	function columns($table){
		$this->sql="Select * From $table";
		
		if($this->con!=null){
			try {
				$q = $this->con->query($this->sql);
				$column = array();
				foreach($q->fetch(PDO::FETCH_ASSOC) as $key=>$val){
					 $column[] = $key;
				}			
				return $column;
			} catch(PDOException $e) {
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	//Insert and get newly created id
	function insert($table, $data){
		$count = 0;
		if($this->con!=null){
			try {
				if($this->database_type=="mysql"){
					//Testing the easy way for insert in MySQL
					$this->con->exec("insert into $table set $data;");
					//$count = $this->con->exec("insert into $table set $data;");
				}
				else{			
					$texto = "Insert Into $table (";
					$texto_extra = ") Values (";
					$texto_close = ")";
					$data_column = explode(",", $data);
					for($x=0;$x<count($data_column);$x++){
						$data_content = explode("=", $data_column[$x]); //0=Field, 1=Value
						if($x==0){ $texto.= $data_content[0]; }
						else{ $texto.= "," . $data_content[0]; }
						if($x==0){ $texto_extra.= $data_content[1]; }
						else{ $texto_extra.= "," . $data_content[1]; }					
					}
					$this->con->exec("$texto $texto_extra $texto_close");
					//$count = $this->con->exec("$texto $texto_extra $texto_close");
				}
				return $this->con->lastInsertId();
				//return $count;
			} catch(PDOException $e) {
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	//Update tables
	function update($table, $data, $condition){
		$count = 0;
		if($this->con!=null){
			try {
				$count = $this->con->exec("update $table set $data where $condition");
				return $count;
			} catch(PDOException $e) {
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	//Delete records from tables
	function delete($table, $condition){
		$count = 0;
		if($this->con!=null){
			try {
				$count = $this->con->exec("delete from $table where $condition");
				return $count;
			} catch(PDOException $e) {
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	//Get latest specified id from specified table
	function getLatestId($db_table, $table_field){
		$sql_statement = "";
		$dbtype = $this->database_type;
		
		if($dbtype=="sqlsrv" || $dbtype=="mssql" || $dbtype=="ibm" || $dbtype=="dblib" || $dbtype=="odbc" || $dbtype=="sqlite2" || $dbtype=="sqlite3"){
			$sql_statement = "select top 1 $table_field from $db_table order by $table_field desc";
		}
		if($dbtype=="oracle"){
			$sql_statement = "select $table_field from $db_table where ROWNUM<=1 order by $table_field desc";
		}
		if($dbtype=="ifmx" || $dbtype=="fbd"){
			$sql_statement = "select first 1 $table_field from $db_table order by $table_field desc";
		}
		if($dbtype=="mysql"){
			$sql_statement = "select $table_field from $db_table order by $table_field desc limit 1";
		}
		if($dbtype=="pg"){
			$sql_statement = "select $table_field from $db_table order by $table_field desc limit 1 offset 0";
		}
		
		if($this->con!=null){
			try {
				$latest_value = 0;
				$rows = $this->con->query($sql_statement);
				foreach($rows as $row){
					$latest_value = $row[$table_field];
				}
				$rows = null;
				return $latest_value;
			} catch(PDOException $e) {
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	//Get all tables from specified database
	function ShowTables($database){
		$complete = "";		
		$sql_statement = "";		
		$dbtype = $this->database_type;
		
		if($dbtype=="sqlsrv" || $dbtype=="mssql" || $dbtype=="ibm" || $dbtype=="dblib" || $dbtype=="odbc" || $dbtype=="sqlite2" || $dbtype=="sqlite3"){
			$sql_statement = "select name from sysobjects where xtype='U'";
		}
		if($dbtype=="oracle"){
			//If the query statement fail, try with uncomment the next line:
			//$sql_statement = "SELECT table_name FROM tabs";
			$sql_statement = "SELECT table_name FROM cat";
		}
		if($dbtype=="ifmx" || $dbtype=="fbd"){
			$sql_statement = "SELECT RDB$RELATION_NAME FROM RDB$RELATIONS WHERE RDB$SYSTEM_FLAG = 0 AND RDB$VIEW_BLR IS NULL ORDER BY RDB$RELATION_NAME";
		}
		if($dbtype=="mysql"){
			if($database!=""){ $complete = " from $database"; }
			$sql_statement = "show tables $complete";
		}
		if($dbtype=="pg"){
			$sql_statement = "select relname as name from pg_stat_user_tables order by relname";
		}
		
		if($this->con!=null){
			try {
				$this->sql=$sql_statement;
				return $this->con->query($this->sql);			
			} catch(PDOException $e) {
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	//Get all databases from your server
	function ShowDBS(){
		$sql_statement = "";		
		$dbtype = $this->database_type;
		
		if($dbtype=="sqlsrv" || $dbtype=="mssql" || $dbtype=="ibm" || $dbtype=="dblib" || $dbtype=="odbc" || $dbtype=="sqlite2" || $dbtype=="sqlite3"){
			$sql_statement = "SELECT name FROM sys.Databases";
		}
		if($dbtype=="oracle"){
			//If the query statement fail, try with uncomment the next line:
			//$sql_statement = "select * from user_tablespaces";
			$sql_statement = "select * from v$database";
		}
		if($dbtype=="ifmx" || $dbtype=="fbd"){
			$sql_statement = "";
		}
		if($dbtype=="mysql"){
			$sql_statement = "SHOW DATABASES";
		}
		if($dbtype=="pg"){
			$sql_statement = "select datname as name from pg_database";
		}
		
		if($this->con!=null){
			try {
				$this->sql=$sql_statement;
				return $this->con->query($this->sql);			
			} catch(PDOException $e) {
				return false;
			}
		}
		else{
			return false;
		}	
	}
	
	//Disconnect database
	function disconnect(){
		if($this->con){
			$this->con = null;
			return true;
		}else{
			return false;
		}
	}
}
?>