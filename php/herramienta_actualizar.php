<?php
require_once "main.php";

/*== Almacenando datos con validación de existencia ==*/
$id        = isset($_POST['herramienta_id']) ? limpiar_cadena($_POST['herramienta_id']) : '';
$codigo    = isset($_POST['herramienta_codigo']) ? limpiar_cadena($_POST['herramienta_codigo']) : '';
$nombre    = isset($_POST['herramienta_nombre']) ? limpiar_cadena($_POST['herramienta_nombre']) : '';
$descripcion = isset($_POST['herramienta_descripcion']) ? limpiar_cadena($_POST['herramienta_descripcion']) : '';
$precio    = isset($_POST['herramienta_precio']) ? limpiar_cadena($_POST['herramienta_precio']) : '';
$stock     = isset($_POST['herramienta_stock']) ? limpiar_cadena($_POST['herramienta_stock']) : '';
$categoria = isset($_POST['herramienta_categoria']) ? limpiar_cadena($_POST['herramienta_categoria']) : '';

/*== Verificando campos obligatorios ==*/
if ($id == "" || $codigo == "" || $nombre == "" || $descripcion == "" || $precio == "" || $stock == "" || $categoria == "") {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

/*== Verificando existencia de la herramienta ==*/
$check_herramienta = conexion();
$check_herramienta = $check_herramienta->query("SELECT * FROM herramienta WHERE herramienta_id='$id'");

if ($check_herramienta->rowCount() <= 0) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La Herramienta no existe en el sistema
        </div>
    ';
    exit();
} else {
    $datos = $check_herramienta->fetch();
}
$check_herramienta = null;

/*== Verificando integridad de los datos ==*/
if (verificar_datos("[a-zA-Z0-9- ]{1,70}", $codigo)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El CODIGO de BARRAS no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}", $nombre)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El NOMBRE no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos(".{1,300}", $descripcion)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>Error:</strong> 
            La DESCRIPCIÓN no cumple el formato
        </div>
    '; 
    exit();
}

if (verificar_datos("[0-9.]{1,25}", $precio)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El PRECIO no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[0-9]{1,25}", $stock)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El STOCK no coincide con el formato solicitado
        </div>
    ';
    exit();
}

/*== Verificando codigo único ==*/
if ($codigo != $datos['herramienta_codigo']) {
    $check_codigo = conexion();
    $check_codigo = $check_codigo->query("SELECT herramienta_codigo FROM herramienta WHERE herramienta_codigo='$codigo'");
    if ($check_codigo->rowCount() > 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El CODIGO de BARRAS ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
        exit();
    }
    $check_codigo = null;
}

/*== Verificando nombre único ==*/
if ($nombre != $datos['herramienta_nombre']) {
    $check_nombre = conexion();
    $check_nombre = $check_nombre->query("SELECT herramienta_nombre FROM herramienta WHERE herramienta_nombre='$nombre'");
    if ($check_nombre->rowCount() > 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
        exit();
    }
    $check_nombre = null;
}

/*== Verificando categoría ==*/
if ($categoria != $datos['categoria_id']) {
    $check_categoria = conexion();
    $check_categoria = $check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id='$categoria'");
    if ($check_categoria->rowCount() <= 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La categoría seleccionada no existe
            </div>
        ';
        exit();
    }
    $check_categoria = null;
}

/*== Actualizando datos ==*/
$actualizar_producto = conexion();
$actualizar_producto = $actualizar_producto->prepare("UPDATE herramienta SET herramienta_codigo=:codigo, herramienta_nombre=:nombre, herramienta_descripcion=:descripcion, herramienta_precio=:precio, herramienta_stock=:stock, categoria_id=:categoria WHERE herramienta_id=:id");

$marcadores = [
    ":codigo"    => $codigo,
    ":nombre"    => $nombre,
    ":descripcion" => $descripcion,
    ":precio"    => $precio,
    ":stock"     => $stock,
    ":categoria" => $categoria,
    ":id"        => $id
];

if ($actualizar_producto->execute($marcadores)) {
    echo '
        <div class="notification is-info is-light">
            <strong>¡PRODUCTO ACTUALIZADO!</strong><br>
            El producto se actualizo con exito
        </div>
    ';
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo actualizar el producto, por favor intente nuevamente
        </div>
    ';
}

$actualizar_producto = null;
?>
