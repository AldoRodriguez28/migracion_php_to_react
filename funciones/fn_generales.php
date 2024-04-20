<?php
function setLogData($data_text){
	$data_text = $data_text.PHP_EOL;
	file_put_contents( FILE_LOG , $data_text , FILE_APPEND | LOCK_EX );
}

function setLogDataReporte139($Archivo,$data_text){
	$data_text = $data_text.PHP_EOL;
	file_put_contents( $Archivo , $data_text , FILE_APPEND | LOCK_EX );
}

function ejecutarProcesosTda($sql){
	$response = array('data' => null, 'error' => '');
	$db = new wArLeY_DBMS(BD_TYPE_TDA, BD_HOST_TDA, BD_TDA, BD_USER_TDA, BD_PASSWORD_TDA, BD_PORT_TDA);
	$dbObj = $db->Cnxn();
	if ($db->getError() == '') {
		$rs = $db->query($sql);
		if ($db->getError() != "") {
			$response['error'] = $db->getError();
		}else{
			$rs_final = objectToArray($rs->fetchAll(PDO::FETCH_OBJ));
			$response['data'] =$rs_final[0];
		}
	} else {
		$response['error'] = $db->getError();
	}
	
	$db->disconnect();
	
	return $response;
}
?>