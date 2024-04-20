<?php
require_once('pdo_cnn_class/pdo_database_v2.class.php');
require_once('constant.php');

$limiteRegistros = 1000000;

function getConexionTda(){
	$cnn = new wArLeY_DBMS(BD_TYPE_TDA, BD_HOST_TDA, BD_TDA, BD_USER_TDA, BD_PASSWORD_TDA, BD_PORT_TDA);
	$dbObj = $cnn->Cnxn();
	return $cnn;
}

function closeConexion($cnn){
	$cnn->disconnect();
}
?>