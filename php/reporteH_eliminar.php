<?php
	/*== Almacenando datos ==*/
    $reporteh_id_del=limpiar_cadena($_GET['reporteh_id_del']);

    /*== Verificando producto ==*/
    $check_reporteh=conexion();
    $check_reporteh=$check_reporteh->query("SELECT * FROM reporteh WHERE reporteh_id='$reporteh_id_del'");

    if($check_reporteh->rowCount()==1){

    	$datos=$check_reporteh->fetch();

    	$eliminar_reporteh=conexion();
    	$eliminar_reporteh=$eliminar_reporteh->prepare("DELETE FROM reporteh WHERE reporteh_id=:id");

    	$eliminar_reporteh->execute([":id"=>$reporteh_id_del]);

    	if($eliminar_reporteh->rowCount()==1){

    	

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
	    $eliminar_reporteh=null;
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El PRODUCTO que intenta eliminar no existe
            </div>
        ';
    }
    $check_reporteh=null;