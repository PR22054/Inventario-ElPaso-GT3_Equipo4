<?php
	require_once "../inc/session_start.php";

	require_once "main.php";

	/*== Almacenando datos ==*/
	$tipo=limpiar_cadena($_POST['reporteH_tipo']);
	$persona=limpiar_cadena($_POST['reporteH_persona']);

	$detalles=limpiar_cadena($_POST['reporteH_detalles']);
	$herramienta=limpiar_cadena($_POST['reporteH_herramienta']);
   


	/*== Verificando campos obligatorios ==*/
    if($tipo=="" || $persona=="" || $detalles=="" || $herramienta=="" ){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }


    /*== Verificando integridad de los datos ==*/
    if(verificar_datos("[a-zA-Z0-9- ]{1,70}",$tipo)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El CODIGO de BARRAS no coincide con el formato solicitado
            </div>
        ';
        exit();
    }



    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,150}",$detalles)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

	/*== Guardando datos ==*/
    $guardar_reporteH=conexion();
    $guardar_reporteH=$guardar_reporteH->prepare("INSERT INTO reporteh(reporteh_tipo,reporteh_persona,reporteh_detalles,herramienta_id,usuario_id) VALUES(:tipo,:persona,:detalles,:herramienta,:usuario)");

    $marcadores=[
        ":tipo"=>$tipo,
        ":persona"=>$persona,
        ":detalles"=>$detalles,        
        ":herramienta"=>$herramienta,
        ":usuario"=>$_SESSION['id']
    ];

    $guardar_reporteH->execute($marcadores);

    if($guardar_reporteH->rowCount()==1){
        echo 's
            <div class="notification is-info is-light">
                <strong>¡REPORTE REGISTRADO!</strong><br>
                El reporte se registro con exito
            </div>
        ';
    }else{
    	
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar el reporte, por favor intente nuevamente
            </div>
        ';
    }
    $guardar_reporteH=null;