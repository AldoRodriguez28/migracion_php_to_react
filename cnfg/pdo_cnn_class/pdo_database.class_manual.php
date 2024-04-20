<?php
/******************************************
 *
 * PDO Database class manual
 *
 * @author:    	Evert Ulises German Soto
 * @copyright: 	wArLeY996 2011
 *
 ******************************************/

/*************************************************************************************************************************************************
 * DEFINICION DE WIKIPEDIA:
 * La extensión PHP Data Objects (PDO) define un interfaz ligera, para tener acceso a bases de datos en PHP. 
 * Cada controlador de base de datos que implementa la interfaz PDO puede exponer base de datos específicas como funciones de extensión regular. 
 * Tenga en cuenta que no puede realizar las funciones de base de datos utilizando la extensión PDO por sí mismo, 
 * debe utilizar un controlador PDO de base de datos específica para tener acceso a un servidor de base de datos.
 * PDO proporciona una capa de abstracción acceso a datos, que significa que, independientemente de la base de datos que está utilizando, 
 * se utiliza las mismas funciones para realizar consultas y obtener datos. PDO no proporciona una abstracción base de datos; 
 * esto no reescribe SQL o emular características faltantes. Debe usar una capa de abstracción en toda regla, si necesita esto.
 * PDO con PHP 5.1, está disponible como una extensión PECL para PHP 5.0; PDO requiere las características nuevas de OO en el núcleo de PHP 5, 
 * y así no correr con versiones anteriores de PHP. 
 ************************************************************************************************************************************************/

//First you need include the class file
require_once "pdo_database.class.php";

//Intance the class
$db = new wArLeY_DBMS("mysql", "10.33.133.133", "test", "root", "", "");
if($db==false){
	echo "Error: Cant connect to database.";
}

//You can create tables and execute all sql statements
//Examples:

//Droping tables
$db->query('DROP TABLE TB_USERS;');

//Create tables
$query_create_table = <<< EOD
CREATE TABLE TB_USERS (
  ID INTEGER NOT NULL,
  NAME VARCHAR(100) NOT NULL,
  ADDRESS VARCHAR(100) NOT NULL,
  COMPANY VARCHAR(100) NOT NULL
);
EOD;
$db->query($query_create_table);

//Alter tables
$db->query('ALTER TABLE TB_USERS ADD CONSTRAINT INTEG_13 PRIMARY KEY (ID);');

//You can insert data in table with two methods...
//Method 1
$db->query("INSERT INTO TB_USERS (ID, NAME, ADDRESS, COMPANY) VALUES (1, 'Evert Ulises', 'Tetameche #3035 Culiacan Sinaloa', 'Freelancer');");
//Method 2					table			new data [field=data]
$getInsertedId = $db->insert("TB_USERS", "ID=2, NAME='German Soto', 'Tetameche #3035 Culiacan Sin. Mexico', 'Freelancer'");

//If you need get rows from query...
$rs = $db->query("SELECT * FROM TB_USERS");
foreach($rs as $row){
	$tmp_id = $row["ID"];
	$tmp_name = $row["NAME"];
	
	echo "The user ($tmp_id) is named: $tmp_name<br>";
}
//Once that you have execute any query, you can get total rows.
echo "Total rows: " . $db->rowCount() . "<br>";
$rs = null;

//You can delete rows from table with two methods...
//Method 1
$db->query("DELETE FROM TB_USERS WHERE ID=1;");
//Method 2						table		condition without "WHERE"
$getAffectedRows = $db->delete("TB_USERS", "ID=1");

//You can update rows from table with two methods...
//Method 1
$db->query("UPDATE TB_USERS SET COMPANY='Freelancer MX' WHERE ID=2;");
//Method 2						table		set new data [field=data]				condition without "WHERE"
$getAffectedRows = $db->delete("TB_USERS", "NAME='wArLeY996',COMPANY='Freelancer MX'", "ID=2");

//If you need get columns name, you can do it...
$column_array = $db->columns("TB_USERS");
if($column_array!=false){
	foreach($column_array as $column){
		echo "$column<br>";
	}
}
else{
	echo "ERROR";
}
$column_array = null;

//If you need get all tables from you database...
$rs = $db->ShowTables("DB_NAME");  //Depending of your type database you can specify the database
foreach($rs as $row){
	$tmp_table = $row[0];
	
	echo "The table from database is: ($tmp_table)<br>";
}

//If you need get all databases...
$rs = $db->ShowDBS();  //Depending of your type database you can get results
foreach($rs as $row){
	$tmp_table = $row[0];
	
	echo "Database named: ($tmp_table)<br>";
}

//Disconnect from database
$db->disconnect();



/******************************************
 * 
 *		INSTRUCTIONS
 *
 ******************************************/

 #Just in spanish because my english is so poor... :$
 
 
/*******************************************/
#		CONFIGURANDO "mssql" EN WINDOWS		/
/*******************************************/
# Si "php_pdo_mssql" no esta corriendo... 
# Abrir el directorio: C:\xampp\php\ext\php_pdo_mssql_new.dll
# Renombrar el archivo: php_pdo_mssql.dll a php_pdo_mssql_new.dll 
# Abrir el php.ini y agregar la extension:
# extension=php_pdo_mssql_new.dll
# Copiar el archivo "ntwdblib.dll" en el mismo directorio de extensiones y en el system32
# Reiniciar el webserver y listo!

/*******************************************/
#		CONFIGURANDO "sqlsrv" EN WINDOWS	/
/*******************************************/
# Si "php_pdo_sqlsrv" no esta corriendo... abrir el php.ini y agregar la extension:
# extension=php_pdo_sqlsrv_53_ts_vc6.dll
# Agregar el dll del mismo nombre en el directorio: "php/ext/"
# Reiniciar el webserver y listo!
?>