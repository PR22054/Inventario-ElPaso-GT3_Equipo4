<?php
require_once "../inc/session_start.php";
require_once "main.php";

/*== Almacenando datos ==*/
$persona = limpiar_cadena($_POST['mantenimiento_persona']);
$detalles = limpiar_cadena($_POST['mantenimiento_detalles']);
$fecha1 = limpiar_cadena($_POST['mantenimiento_fecha1']);
$fecha2 = limpiar_cadena($_POST['mantenimiento_fecha2']);
$herramienta = limpiar_cadena($_POST['mantenimiento_herramienta']);

/*== Verificando campos obligatorios ==*/
if($persona=="" || $detalles=="" || $fecha1=="" || $herramienta==""){
    echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos obligatorios
          </div>';
    exit();
}

if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,150}", $detalles)){
    echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Los DETALLES no coinciden con el formato solicitado
          </div>';
    exit();
}

/*== Guardando mantenimiento ==*/
$db = conexion();
$stmt = $db->prepare("INSERT INTO mantenimiento(mantenimiento_persona, mantenimiento_detalles, mantenimiento_fecha1, mantenimiento_fecha2, herramienta_id, usuario_id) 
                      VALUES(:persona, :detalles, :fecha1, :fecha2, :herramienta, :usuario)");

$stmt->execute([
    ":persona" => $persona,
    ":detalles" => $detalles,
    ":fecha1" => $fecha1,
    ":fecha2" => $fecha2,
    ":herramienta" => $herramienta,
    ":usuario" => $_SESSION['id']
]);

/*== Marcar la herramienta como ya no necesita mantenimiento ==*/
$db->prepare("UPDATE herramienta SET necesita_mantenimiento=0 WHERE herramienta_id=:herramienta")
   ->execute([":herramienta"=>$herramienta]);

echo '<div class="notification is-info is-light">
        <strong>¡MANTENIMIENTO REGISTRADO!</strong><br>
        El mantenimiento se registró con éxito y la herramienta ya no aparece en la alerta
      </div>';
?>
