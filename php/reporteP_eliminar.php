<?php
	/*== Almacenando datos ==*/
    $reportep_id_del=limpiar_cadena($_GET['reportep_id_del']);

    /*== Verificando producto ==*/
    $check_reportep=conexion();
    $check_reportep=$check_reportep->query("SELECT * FROM reportep WHERE reportep_id='$reportep_id_del'");

    if($check_reportep->rowCount()==1){

    	$datos=$check_reportep->fetch();

    	$eliminar_reportep=conexion();
    	$eliminar_reportep=$eliminar_reportep->prepare("DELETE FROM reportep WHERE reportep_id=:id");

    	$eliminar_reportep->execute([":id"=>$reportep_id_del]);

    	if($eliminar_reportep->rowCount()==1){

    	

	        echo 's
	            <div class="notification is-info is-light">
	                <strong>¡PRODUCTO ELIMINADO!</strong><br>
	                Los datos del producto se eliminaron con exito
	            </div>
	        ';
	    }else{
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                No se pudo eliminar el producto, por favor intente nuevamente
	            </div>
	        ';
	    }
	    $eliminar_reportep=null;
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El PRODUCTO que intenta eliminar no existe
            </div>
        ';
    }
    $check_reportep=null;