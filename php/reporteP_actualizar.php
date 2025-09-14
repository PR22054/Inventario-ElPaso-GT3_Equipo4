<?php
	require_once "main.php";

	/*== Almacenando id ==*/
    $id=limpiar_cadena($_POST['reportep_id']);


    /*== Verificando producto ==*/
	$check_reportep=conexion();
	$check_reportep=$check_reportep->query("SELECT * FROM reportep WHERE reportep_id='$id'");

    if($check_reportep->rowCount()<=0){
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El producto no existe en el sistema
            </div>
        ';
        exit();
    }else{
    	$datos=$check_reportep->fetch();
    }
    $check_reportep=null;


	/*== Almacenando datos ==*/
	$tipo=limpiar_cadena($_POST['reporteP_tipo']);
	$persona=limpiar_cadena($_POST['reporteP_persona']);

	$detalles=limpiar_cadena($_POST['reporteP_detalles']);
	$cantidad=limpiar_cadena($_POST['reporteP_cantidad']);
	$producto=limpiar_cadena($_POST['reporteP_producto']);
   


	/*== Verificando campos obligatorios ==*/
    if($tipo=="" || $persona=="" || $detalles=="" || $cantidad=="" || $producto=="" ){
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

    if(verificar_datos("[0-9.]{1,25}",$cantidad)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El PRECIO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    /*== Actualizando datos ==*/
    $actualizar_reportep=conexion();
    $actualizar_reportep=$actualizar_reportep->prepare("UPDATE reportep SET reportep_persona=:persona,reportep_tipo=:tipo,reportep_detalles=:detalles,reportep_cantidad=:cantidad,producto_id=:producto WHERE reportep_id=:id");

    $marcadores=[
        ":persona"=>$persona,
        ":tipo"=>$tipo,        
        ":detalles"=>$detalles,
        ":cantidad"=>$cantidad,
        ":producto"=>$producto,
        ":id"=>$id
    ];


    if($actualizar_reportep->execute($marcadores)){
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
    $actualizar_reportep=null;