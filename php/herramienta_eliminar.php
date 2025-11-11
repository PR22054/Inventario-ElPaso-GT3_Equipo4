<?php
require_once "main.php";

/*== Almacenando datos ==*/
$herramienta_id_del = limpiar_cadena($_GET['herramienta_id_del']); 

/*== Verificando herramienta ==*/
$check_herramienta = conexion();
$check_herramienta = $check_herramienta->prepare("SELECT * FROM herramienta WHERE herramienta_id = :id");
$check_herramienta->execute([":id" => $herramienta_id_del]);

if($check_herramienta->rowCount() == 1){
    $datos = $check_herramienta->fetch();

    /*== Verificar si existen reportes asociados ==*/
    $check_reportes = conexion();
    $check_reportes = $check_reportes->prepare("SELECT COUNT(*) FROM reporteh WHERE herramienta_id = :id");
    $check_reportes->execute([":id" => $herramienta_id_del]);
    
    if($check_reportes->fetchColumn() > 0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡No se puede eliminar!</strong><br>
                La herramienta tiene reportes asociados y no se puede borrar.
            </div>
        ';
        exit();
    }

    /*== Eliminar herramienta ==*/
    $eliminar_herramienta = conexion();
    $eliminar_herramienta = $eliminar_herramienta->prepare("DELETE FROM herramienta WHERE herramienta_id = :id");
    $eliminar_herramienta->execute([":id" => $herramienta_id_del]);

    if($eliminar_herramienta->rowCount() == 1){

        /*== Eliminar foto si existe ==*/
        if(is_file("./img/herramienta/".$datos['herramienta_foto'])){
            chmod("./img/herramienta/".$datos['herramienta_foto'], 0777);
            unlink("./img/herramienta/".$datos['herramienta_foto']);
        }

        echo '
            <div class="notification is-info is-light">
                <strong>¡HERRAMIENTA ELIMINADA!</strong><br>
                Los datos de la herramienta se eliminaron con éxito
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No se pudo eliminar la herramienta, por favor intente nuevamente
            </div>
        ';
    }

    $eliminar_herramienta = null;

}else{
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            La herramienta que intenta eliminar no existe
        </div>
    ';
}

$check_herramienta = null;
$check_reportes = null;
?>
