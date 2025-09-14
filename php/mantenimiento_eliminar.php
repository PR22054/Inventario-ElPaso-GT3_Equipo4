<?php
	/*== Almacenando datos ==*/
    $mantenimiento_id_del=limpiar_cadena($_GET['mantenimiento_id_del']);

    /*== Verificando producto ==*/
    $check_mantenimiento=conexion();
    $check_mantenimiento=$check_mantenimiento->query("SELECT * FROM mantenimiento WHERE mantenimiento_id='$mantenimiento_id_del'");

    if($check_mantenimiento->rowCount()==1){

    	$datos=$check_mantenimiento->fetch();

    	$eliminar_mantenimiento=conexion();
    	$eliminar_mantenimiento=$eliminar_mantenimiento->prepare("DELETE FROM mantenimiento WHERE mantenimiento_id=:id");

    	$eliminar_mantenimiento->execute([":id"=>$mantenimiento_id_del]);

    	if($eliminar_mantenimiento->rowCount()==1){
	        echo '
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
	    $eliminar_mantenimiento=null;
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El PRODUCTO que intenta eliminar no existe
            </div>
        ';
    }
    $check_mantenimiento=null;
?>