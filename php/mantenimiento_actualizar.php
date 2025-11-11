<?php
require_once "main.php";

/*== Almacenando id ==*/
$id = limpiar_cadena($_POST['mantenimiento_id'] ?? '');

/*== Verificando mantenimiento ==*/
$check_mantenimiento = conexion();
$check_mantenimiento = $check_mantenimiento->query("SELECT * FROM mantenimiento WHERE mantenimiento_id='$id'");

if($check_mantenimiento->rowCount() <= 0){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El mantenimiento no existe en el sistema
        </div>
    ';
    exit();
} else {
    $datos = $check_mantenimiento->fetch();
}
$check_mantenimiento = null;

/*== Almacenando datos ==*/
$persona     = limpiar_cadena($_POST['mantenimiento_persona'] ?? ''); // Persona del mantenimiento
$herramienta = limpiar_cadena($_POST['herramienta_id'] ?? '');
$detalles    = limpiar_cadena($_POST['mantenimiento_detalles'] ?? '');
$fecha1      = limpiar_cadena($_POST['mantenimiento_fecha1'] ?? '');
$fecha2      = limpiar_cadena($_POST['mantenimiento_fecha2'] ?? '');

/*== Verificando campos obligatorios ==*/
if($persona == "" || $herramienta == "" || $detalles == "" || $fecha1 == ""){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

/*== Verificando integridad de los datos ==*/
if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,150}", $detalles)){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Los DETALLES no coinciden con el formato solicitado
        </div>
    ';
    exit();
}

/*== Verificando formato de fechas ==*/
if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha1)){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La FECHA DE INICIO no tiene un formato válido
        </div>
    ';
    exit();
}

if($fecha2 != "" && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha2)){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La FECHA DE FINALIZACIÓN no tiene un formato válido
        </div>
    ';
    exit();
}

/*== Verificando que la persona del mantenimiento exista ==*/
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

/*== Verificando que la fecha de fin no sea menor que la de inicio ==*/
if($fecha2 != "" && $fecha2 < $fecha1){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La fecha de finalización no puede ser menor que la fecha de inicio
        </div>
    ';
    exit();
}

/*== Actualizando datos ==*/
$conexion = conexion();
$update = $conexion->prepare("
    UPDATE mantenimiento 
    SET mantenimiento_persona = :persona,
        herramienta_id = :herramienta_id,
        mantenimiento_detalles = :detalles,
        mantenimiento_fecha1 = :fecha1,
        mantenimiento_fecha2 = :fecha2
    WHERE mantenimiento_id = :id
");

// Enlazar parámetros
$update->bindParam(":persona", $persona);
$update->bindParam(":herramienta_id", $herramienta);
$update->bindParam(":detalles", $detalles);
$update->bindParam(":fecha1", $fecha1);
$update->bindParam(":fecha2", $fecha2);
$update->bindParam(":id", $id);

// Ejecutar
if($update->execute()){
    echo '
        <div class="notification is-info is-light">
            <strong>¡MANTENIMIENTO ACTUALIZADO!</strong><br>
            El mantenimiento se actualizó con éxito
        </div>
    ';
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo actualizar el mantenimiento, por favor intente nuevamente
        </div>
    ';
}

$conexion = null;
?>