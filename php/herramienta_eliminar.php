<?php
	/*== Almacenando datos ==*/
    $herramienta_id_del=limpiar_cadena($_GET['herramienta_id_del']); 

    /*== Verificando herramienta ==*/
    $check_herramienta=conexion();
    $check_herramienta=$check_herramienta->query("SELECT * FROM herramienta WHERE herramienta_id='$herramienta_id_del'");

    if($check_herramienta->rowCount()==1){

    	$datos=$check_herramienta->fetch();

    	$eliminar_herramienta=conexion();
    	$eliminar_herramienta=$eliminar_herramienta->prepare("DELETE FROM herramienta WHERE herramienta_id=:id");

    	$eliminar_herramienta->execute([":id"=>$herramienta_id_del]);

    	if($eliminar_herramienta->rowCount()==1){

    		if(is_file("./img/herramienta/".$datos['herramienta_foto'])){
    			chmod("./img/herramienta/".$datos['herramienta_foto'], 0777);
				unlink("./img/herramienta/".$datos['herramienta_foto']);
    		}

	        echo '
	            <div class="notification is-info is-light">
	                <strong>¡HERRAMIENTA ELIMINADA!</strong><br>
	                Los datos de la herramienta se eliminaron con exito
	            </div>
	        ';
	    }else{
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                No se pudo eliminar la herramienta, por favor intente nuevamente
	            </div>
	        ';
	    }
	    $eliminar_herramienta=null;
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La herramienta que intenta eliminar no existe
            </div>
        ';
    }
    $check_herramienta=null;