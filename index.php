<?php	
	set_time_limit(0);
	require_once('cnfg/pdo_cnn_class/pdo_database_v2.class.php');
	require_once('cnfg/constant.php');
	require_once('cnfg/conexiones.php');
	require_once('funciones/fn_generales.php');
	/*datos para poner log con la funcion y */
	$folder_root = dirname(realpath(__FILE__)) . "/";
	$random = rand();
	$archivo = 'reporte30dias'.$random;

	foreach (glob("*".$archivo."*") as $filename) {		
		unlink($filename);
	 }
	
	$sQl= "SELECT tipopago,nom_estatus,importe_total,importe_descuento,importe_empleado,idu_pedido,nom_email,num_clientecoppel,num_empleadocoppel,
	nom_cliente,nom_apepaterno,nom_apematerno,num_importetotal,fec_orden::date,num_telefono,opc_celular,num_folio,num_estatus,num_totalarticulosmuebles,
	num_totalarticulosropa,num_totalarticulosmkp,num_importeropa,num_importemuebles,imp_marketplace,imp_tdcmarketplace,num_engancheropa,des_articulo,num_enganchemuebles,num_folioropa,num_foliomuebles,num_facturamkp,fec_movimiento,num_pagorecibido 
	FROM fn_rptplataforma()";
			
	$db = new wArLeY_DBMS(BD_TYPE_TDA, BD_HOST_TDA, BD_TDA, BD_USER_TDA, BD_PASSWORD_TDA, BD_PORT_TDA);
	$dbObj = $db->Cnxn();
	
	$resultadoConsulta = $db->query($sQl);
	
	$totalArchivos = "1";
		if($resultadoConsulta == false){
			echo $getConexionTda->getError();
			die("error");
		}else{
			$icount = 1;
			$rsEncabezado ='#registro'.'|'.'tipopago'.'|'.'nom_estatus'.'|'.'importe_total'.'|'.'importe_descuento'.'|'.'importe_empleado'.'|'.'idu_pedido'.'|'.'nom_email'.'|'.'num_clientecoppel'.'|'.'num_empleadocoppel'.'|'.
						'nom_cliente'.'|'.'nom_apepaterno'.'|'.'nom_apematerno'.'|'.'num_importetotal'.'|'.' fec_orden '.'|'.'num_telefono'.'|'.'opc_celular'.'|'.'num_folio'.'|'.'num_estatus'.'|'.'num_totalarticulosmuebles'.'|'.
						'num_totalarticulosropa'.'|'.'num_totalarticulosmkp'.'|'.'num_importeropa'.'|'.'num_importemuebles'.'|'.'imp_marketplace'.'|'.'imp_pagoinicialmkp'.'|'.'num_engancheropa'.'|'.'des_articulo'.'|'.'num_enganchemuebles'.'|'.'num_folioropa'.'|'.'num_foliomuebles'.'|'.
						'num_facturamkp'.'|'.'fec_movimiento'.'|'.'num_pagorecibido';
			
			setLogDataReporte139($folder_root.$archivo.$totalArchivos.".csv",$rsEncabezado);
		}
		//Se recorre la informacion de la consulta
		$crearArchivo = $icount;
		
		foreach($resultadoConsulta as $row){
			
			$rsEgistro =$icount.'|'.$row['tipopago'].'|'.$row['nom_estatus'].'|'.$row['importe_total'].'|'.$row['importe_descuento'].'|'.$row['importe_empleado'].'|'.$row['idu_pedido'].'|'.$row['nom_email'].'|'.$row['num_clientecoppel'].'|'.$row['num_empleadocoppel'].'|'.
						$row['nom_cliente'].'|'.$row['nom_apepaterno'].'|'.$row['nom_apematerno'].'|'.$row['num_importetotal'].'|'.$row['fec_orden'].'|'.$row['num_telefono'].'|'.$row['opc_celular'].'|'.$row['num_folio'].'|'.$row['num_estatus'].'|'.$row['num_totalarticulosmuebles'].'|'.
						$row['num_totalarticulosropa'].'|'.$row['num_totalarticulosmkp'].'|'.$row['num_importeropa'].'|'.$row['num_importemuebles'].'|'.$row['imp_marketplace'].'|'.$row['imp_tdcmarketplace'].'|'.$row['num_engancheropa'].'|'.$row['des_articulo'].'|'.$row['num_enganchemuebles'].'|'.$row['num_folioropa'].'|'.$row['num_foliomuebles'].'|'.
						$row['num_facturamkp'].'|'.$row['fec_movimiento'].'|'.$row['num_pagorecibido'];
			
			setLogDataReporte139($folder_root.$archivo.$totalArchivos.".csv",$rsEgistro);
			
			if($crearArchivo == $limiteRegistros){ 
				
				echo '<div style="font-size:18px;"><b>Reporte generado '.$totalArchivos.'</b> | ';
				echo'<a  lt="Descargar Reporte csv" style="text-decoration: none;color: #2196f3" href="./'.$archivo.$totalArchivos.'.csv" download><img src="download-icon.png"  height="16" width="16"> Descargar archivo</a></div>';

				$totalArchivos = $totalArchivos+1;
				
				$rsEncabezado ='#registro'.'|'.'tipopago'.'|'.'nom_estatus'.'|'.'importe_total'.'|'.'importe_descuento'.'|'.'importe_empleado'.'|'.'idu_pedido'.'|'.'nom_email'.'|'.'num_clientecoppel'.'|'.'num_empleadocoppel'.'|'.
						'nom_cliente'.'|'.'nom_apepaterno'.'|'.'nom_apematerno'.'|'.'num_importetotal'.'|'.' fec_orden '.'|'.'num_telefono'.'|'.'opc_celular'.'|'.'num_folio'.'|'.'num_estatus'.'|'.'num_totalarticulosmuebles'.'|'.
						'num_totalarticulosropa'.'|'.'num_totalarticulosmkp'.'|'.'num_importeropa'.'|'.'num_importemuebles'.'|'.'imp_marketplace'.'|'.'imp_pagoinicialmkp'.'|'.'num_engancheropa'.'|'.'des_articulo'.'|'.'num_enganchemuebles'.'|'.'num_folioropa'.'|'.'num_foliomuebles'.'|'.
						'num_facturamkp'.'|'.'fec_movimiento'.'|'.'num_pagorecibido';
			
				setLogDataReporte139($folder_root.$archivo.$totalArchivos.".csv",$rsEncabezado);

				
				$crearArchivo=0;
			}
			$icount= $icount+1;
			$crearArchivo = $crearArchivo+1;
		}	
	
	echo '<div style="font-size:18px;"><b>Reporte generado '.$totalArchivos.'</b> | ';
	
	echo'<a  lt="Descargar Reporte csv" style="text-decoration: none;color: #2196f3" href="./'.$archivo.$totalArchivos.'.csv" download><img src="download-icon.png"  height="16" width="16"> Descargar archivo</a></div>';
?>