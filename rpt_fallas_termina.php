<?php	
	set_time_limit(0);
	//error_reporting(0);
	require_once('cnfg/pdo_cnn_class/pdo_database_v2.class.php');
	require_once('cnfg/constant.php');
	require_once('cnfg/conexiones.php');
	require_once('funciones/fn_generales.php');
	$folder_root = dirname(realpath(__FILE__)) . "/";
	$archivo = 'reporte_ordenes_pendientes.csv';
	if (file_exists($archivo)) {
		unlink($archivo);
		//echo "El archivo $archivo existia y fue borrado, ";
	} else {
		//echo "El archivo $archivo no existe, ";
	}
	
	/*datos para poner log con la funcion y */
	$folder_root = dirname(realpath(__FILE__)) . "/";
	define("FILE_LOG",$folder_root.$archivo);
	
		
	$sQl= "SELECT  E.NUM_FOLIO NUM_ORDEN, E.NUM_CLIENTE,D.NOM_ESTATUS ESTATUS_INICIAL,P.FEC_MOVIMIENTO FECHA_COMPRA,C.NOM_ESTATUS ESTATUS_FALLA , 
			(SELECT MAX(FEC_MOVIMIENTO)FROM MOV_ESTATUSPEDIDO A1 WHERE A1.NUM_FOLIO = E.NUM_FOLIO) FEC_ACTUALIZACION 
			FROM CTL_ESTATUSPEDIDO E 
				INNER JOIN CAT_ESTATUSPEDIDOCOPPEL C ON E.IDU_ESTATUSPEDIDO = C.IDU_ESTATUS 
				INNER JOIN CTL_PEDIDOS P ON E.NUM_FOLIO = P.NUM_FOLIO
				INNER JOIN CAT_ESTATUSPEDIDOCOPPEL D ON P.NUM_ESTATUS = D.IDU_ESTATUS 
			WHERE IDU_ESTATUSPEDIDO IN (24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37)
				AND P.NUM_ESTATUS IN ( 1, 3 ) 
				AND P.FEC_MOVIMIENTO > CURRENT_DATE -INTERVAL '14 days'";
			
	//var_dump($sQl);
	$db = new wArLeY_DBMS(BD_TYPE_TDA, BD_HOST_TDA, BD_TDA, BD_USER_TDA, BD_PASSWORD_TDA, BD_PORT_TDA);
	$dbObj = $db->Cnxn();
	
	//var_dump(ejecutarProcesosTda);
	//die('muere pegrrro');
	$resultadoConsulta = $db->query($sQl);
	 //var_dump($resultadoConsulta);
 
		if($resultadoConsulta == false){
			echo $getConexionTda->getError();
			die("error");
		}else{
			$icount = 1;
			$rsEncabezado = 'num_orden'.'|'.'num_cliente'.'|'.'estatus_inicial'.'|'.'fecha_compra'.'|'.'estatus_falla'.'|'.'fec_actualizacion';
			
			setLogData($rsEncabezado);
		}
		
		echo ('<br><table style="border: 2px solid black;"><tr><th style="padding: 15px;">Orden</th><th style="padding: 15px;">Fecha Compra</th><th>Estatus</th><th>Falla</th></tr>');
		//Se recorre la informacion de la consulta
		foreach($resultadoConsulta as $row){
		  //var_dump($row);
			
			$rsEgistro = $row['num_orden'].'|'.$row['num_cliente'].'|'.$row['estatus_inicial'].'|'.$row['fecha_compra'].'|'.$row['estatus_falla'].'|'.$row['fec_actualizacion'];
			
			
		echo ('<style>  td {    padding: 15px;    text-align: left;}</style>');
			echo ('<tr><td>'.$row['num_orden'].'</td><td>'.$row['fecha_compra'].'</td><td>'.$row['estatus_inicial'].'</td><td>'.$row['estatus_falla'].'</td></tr>');
			
			
			//print_r ($rsEgistro);
			
			setLogData($rsEgistro);
			$icount= $icount+1;
			
		}	
	echo '<div style="font-size:18px;"><b>Reporte generado</b> | ';
	 
	echo date('l jS \of F Y h:i ');
	
	//echo'<a href="descarga.php?file=reporte.csv">Descargar archivo</a> ';
	echo'<a  lt="Descargar Reporte csv" style="text-decoration: none;color: #2196f3" href="./reporte_ordenes_pendientes.csv" download> | <img src="download-icon.png"  height="16" width="16"> Descargar reporte csv</a></div><br>';
?>