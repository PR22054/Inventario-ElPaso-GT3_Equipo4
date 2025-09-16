<?php
	require_once "main.php";

	/*== Almacenando datos ==*/
    $herramienta_id=limpiar_cadena($_POST['img_del_id']);

    /*== Verificando producto ==*/
    $check_herramienta=conexion();
    $check_herramienta=$check_herramienta->query("SELECT * FROM herramienta WHERE herramienta_id='$herramienta_id'");

    if($check_herramienta->rowCount()==1){
    	$datos=$check_herramienta->fetch();
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La imagen de la Herramienta que intenta eliminar no existe
            </div>
        ';
        exit();
    }
    $check_herramienta=null;


    /* Directorios de imagenes */
	$img_dir='../img/herramienta/';

	/* Cambiando permisos al directorio */
	chmod($img_dir, 0777);


	/* Eliminando la imagen */
	if(is_file($img_dir.$datos['herramienta_foto'])){

		chmod($img_dir.$datos['herramienta_foto'], 0777);

		if(!unlink($img_dir.$datos['herramienta_foto'])){
			echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                Error al intentar eliminar la imagen del producto, por favor intente nuevamente
	            </div>
	        ';
	        exit();
		}
	}


	/*== Actualizando datos ==*/
    $actualizar_herramienta=conexion();
    $actualizar_herramienta=$actualizar_herramienta->prepare("UPDATE herramienta SET herramienta_foto=:foto WHERE herramienta_id=:id");

    $marcadores=[
        ":foto"=>"",
        ":id"=>$herramienta_id
    ];

    if($actualizar_herramienta->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡IMAGEN O FOTO ELIMINADA!</strong><br>
                La imagen de la herramienta ha sido eliminada exitosamente, pulse Aceptar para recargar los cambios.

                <p class="has-text-centered pt-5 pb-5">
                    <a href="index.php?vista=herramienta_img&herramienta_id_up='.$herramienta_id.'" class="button is-link is-rounded">Aceptar</a>
                </p">
            </div>
        ';
    }else{
        echo '
            <div class="notification is-warning is-light">
                <strong>¡IMAGEN O FOTO ELIMINADA!</strong><br>
                Ocurrieron algunos inconvenientes, sin embargo la imagen del producto ha sido eliminada, pulse Aceptar para recargar los cambios.

                <p class="has-text-centered pt-5 pb-5">
                    <a href="index.php?vista=product_img&product_id_up='.$herramienta_id.'" class="button is-link is-rounded">Aceptar</a>
                </p">
            </div>
        ';
    }
    $actualizar_herramienta=null;