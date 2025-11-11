<?php
require_once "main.php";

/*== Almacenando id ==*/
$id = limpiar_cadena($_POST['reportep_id'] ?? '');

/*== Verificando reporte ==*/
$check_reportep = conexion();
$check_reportep = $check_reportep->query("SELECT * FROM reportep WHERE reportep_id='$id'");

if($check_reportep->rowCount() <= 0){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El reporte no existe en el sistema
        </div>
    ';
    exit();
} else {
    $datos = $check_reportep->fetch();
}
$check_reportep = null;

/*== Almacenando datos ==*/
$tipo        = limpiar_cadena($_POST['reporteP_tipo'] ?? '');
$persona     = limpiar_cadena($_POST['reporteP_persona'] ?? ''); // Persona del reporte
$detalles    = limpiar_cadena($_POST['reporteP_detalles'] ?? '');
$cantidad    = limpiar_cadena($_POST['reporteP_cantidad'] ?? '');
$producto    = limpiar_cadena($_POST['reporteP_producto'] ?? '');

/*== Verificando campos obligatorios ==*/
if($tipo == "" || $persona == "" || $detalles == "" || $cantidad == "" || $producto == ""){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

/*== Verificando integridad de los datos ==*/
if(verificar_datos("[a-zA-Z0-9- ]{1,70}", $tipo)){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El TIPO no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,150}", $detalles)){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Los DETALLES no coinciden con el formato solicitado
        </div>
    ';
    exit();
}

if(!is_numeric($cantidad) || $cantidad < 1 || $cantidad > 999999){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La CANTIDAD debe ser un número entre 1 y 999,999
        </div>
    ';
    exit();
}

/*== Verificando que la persona del reporte exista ==*/
$check_persona = conexion();
$check_persona = $check_persona->query("SELECT usuario_id FROM usuario WHERE usuario_id = '$persona'");

if($check_persona->rowCount() <= 0){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La persona seleccionada no existe en el sistema
        </div>
    ';
    exit();
}
$check_persona = null;

/*== Verificando que el producto exista ==*/
$check_producto = conexion();
$check_producto = $check_producto->query("SELECT producto_id FROM producto WHERE producto_id = '$producto'");

if($check_producto->rowCount() <= 0){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El producto seleccionado no existe en el sistema
        </div>
    ';
    exit();
}
$check_producto = null;

/*== Actualizando datos ==*/
$actualizar_reportep = conexion();
$actualizar_reportep = $actualizar_reportep->prepare("
    UPDATE reportep 
    SET reportep_tipo = :tipo,
        reportep_persona = :persona,
        reportep_detalles = :detalles,
        reportep_cantidad = :cantidad,
        producto_id = :producto
    WHERE reportep_id = :id
");

$marcadores = [
    ":tipo" => $tipo,
    ":persona" => $persona, // Persona del reporte (usuario involucrado)
    ":detalles" => $detalles,
    ":cantidad" => $cantidad,
    ":producto" => $producto,
    ":id" => $id
];

if($actualizar_reportep->execute($marcadores)){
    echo '
        <div class="notification is-info is-light">
            <strong>¡REPORTE ACTUALIZADO!</strong><br>
            El reporte se actualizó con éxito
        </div>
    ';
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo actualizar el reporte, por favor intente nuevamente
        </div>
    ';
}
$actualizar_reportep = null;
?>