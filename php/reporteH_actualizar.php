<?php
require_once "main.php";

/*== Almacenando id ==*/
$id = limpiar_cadena($_POST['reporteh_id'] ?? '');

/*== Verificando reporte ==*/
$check_reporteh = conexion();
$check_reporteh = $check_reporteh->query("SELECT * FROM reporteh WHERE reporteh_id='$id'");

if($check_reporteh->rowCount() <= 0){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El reporte no existe en el sistema
        </div>
    ';
    exit();
} else {
    $datos = $check_reporteh->fetch();
}
$check_reporteh = null;

/*== Almacenando datos ==*/
$tipo        = limpiar_cadena($_POST['reporteh_tipo'] ?? '');
$persona     = limpiar_cadena($_POST['reporteh_persona'] ?? ''); // Ahora recibe el ID del usuario
$detalles    = limpiar_cadena($_POST['reporteh_detalles'] ?? '');
$herramienta = limpiar_cadena($_POST['herramienta_id'] ?? '');

/*== Verificando campos obligatorios ==*/
if($tipo == "" || $persona == "" || $detalles == "" || $herramienta == ""){
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
            El tipo no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,150}", $detalles)){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Los detalles no coinciden con el formato solicitado
        </div>
    ';
    exit();
}

/*== Verificando que el usuario seleccionado exista ==*/
$check_usuario = conexion();
$check_usuario = $check_usuario->query("SELECT usuario_id, usuario_nombre FROM usuario WHERE usuario_id = '$persona'");

if($check_usuario->rowCount() <= 0){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El usuario seleccionado no existe en el sistema
        </div>
    ';
    exit();
}
$usuario_data = $check_usuario->fetch();
$check_usuario = null;

/*== Verificando que la herramienta exista ==*/
$check_herramienta = conexion();
$check_herramienta = $check_herramienta->query("SELECT herramienta_id FROM herramienta WHERE herramienta_id = '$herramienta'");

if($check_herramienta->rowCount() <= 0){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La herramienta seleccionada no existe en el sistema
        </div>
    ';
    exit();
}
$check_herramienta = null;

/*== Actualizando datos ==*/
$actualizar_reporteh = conexion();
$actualizar_reporteh = $actualizar_reporteh->prepare("
    UPDATE reporteh 
    SET reporteh_tipo = :tipo,
        reporteh_persona = :persona,
        reporteh_detalles = :detalles, 
        herramienta_id = :herramienta
    WHERE reporteh_id = :id
");

$marcadores = [
    ":tipo" => $tipo,
    ":persona" => $persona, // Ahora guarda el ID del usuario
    ":detalles" => $detalles,
    ":herramienta" => $herramienta,
    ":id" => $id
];

if($actualizar_reporteh->execute($marcadores)){
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
$actualizar_reporteh = null;
?>