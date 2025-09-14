<?php
	require_once "main.php";

	/*== Almacenando id ==*/
    $id=limpiar_cadena($_POST['reporteh_id']);


    /*== Verificando producto ==*/
	$check_reporteh=conexion();
	$check_reporteh=$check_reporteh->query("SELECT * FROM reporteh WHERE reporteh_id='$id'");

    if($check_reporteh->rowCount()<=0){
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El producto no existe en el sistema
            </div>
        ';
        exit();
    }else{
    	$datos=$check_reporteh->fetch();
    }
    $check_reporteh=null;


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

    /*== Actualizando datos ==*/
    $actualizar_reporteh=conexion();
    $actualizar_reporteh=$actualizar_reporteh->prepare("UPDATE reporteh SET reporteh_persona=:persona,reporteh_tipo=:tipo,reporteh_detalles=:detalles,herramienta_id=:herramienta WHERE reporteh_id=:id");

    $marcadores=[
        ":persona"=>$persona,
        ":tipo"=>$tipo,        
        ":detalles"=>$detalles,        
        ":herramienta"=>$herramienta,
        ":id"=>$id
    ];


    if($actualizar_reporteh->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡PRODUCTO ACTUALIZADO!</strong><br>
                El producto se actualizo con exito
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo actualizar el producto, por favor intente nuevamente
            </div>
        ';
    }
    $actualizar_reporteh=null;