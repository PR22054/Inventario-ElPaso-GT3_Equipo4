<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once "main.php";
$pdo = conexion();

/*== Recibir datos ==*/
$producto_id = limpiar_cadena($_POST['producto_id'] ?? '');
$usuario_id  = limpiar_cadena($_POST['usuario_id'] ?? '');
$cantidad    = limpiar_cadena($_POST['cantidad'] ?? '');
$tipo_salida = limpiar_cadena($_POST['tipo_salida'] ?? '');

/*== Validaciones ==*/
if($producto_id=="" || $usuario_id=="" || $cantidad=="" || $tipo_salida==""){
    $mensaje = '<div class="notification is-danger is-light">
                    <strong>¡Error!</strong><br>
                    Complete todos los campos.
                </div>';
    echo "<script>parent.document.getElementById('resultado').innerHTML = `$mensaje`;</script>";
    exit();
}

if(!is_numeric($cantidad) || intval($cantidad)<=0){
    $mensaje = '<div class="notification is-danger is-light">
                    <strong>¡Error!</strong><br>
                    Cantidad inválida.
                </div>';
    echo "<script>parent.document.getElementById('resultado').innerHTML = `$mensaje`;</script>";
    exit();
}

$cantidad = intval($cantidad);

/*== Verificar stock ==*/
$stmt = $pdo->prepare("SELECT producto_stock FROM producto WHERE producto_id=:id");
$stmt->execute([":id"=>$producto_id]);
$producto = $stmt->fetch();

if(!$producto){
    $mensaje = '<div class="notification is-danger is-light">
                    <strong>¡Error!</strong><br>
                    Producto no encontrado.
                </div>';
    echo "<script>parent.document.getElementById('resultado').innerHTML = `$mensaje`;</script>";
    exit();
}

$stock = intval($producto['producto_stock']);
if($cantidad > $stock){
    $mensaje = '<div class="notification is-danger is-light">
                    <strong>¡Error!</strong><br>
                    Stock insuficiente. Disponible: 0
                </div>';
    echo "<script>parent.document.getElementById('resultado').innerHTML = `$mensaje`;</script>";
    exit();
}

/*== Guardar salida ==*/
try {
    $pdo->beginTransaction();

    $insert = $pdo->prepare("INSERT INTO salida_producto (producto_id, usuario_id, tipo_salida, cantidad, fecha_salida)
                             VALUES(:prod, :user, :tipo, :cant, NOW())");
    $insert->execute([
        ":prod" => $producto_id,
        ":user" => $usuario_id,
        ":tipo" => $tipo_salida,
        ":cant" => $cantidad
    ]);

    $update = $pdo->prepare("UPDATE producto SET producto_stock = producto_stock - :cant WHERE producto_id = :id");
    $update->execute([
        ":cant" => $cantidad,
        ":id"   => $producto_id
    ]);

    $pdo->commit();

    $nuevoStock = $stock - $cantidad;

    $mensaje = '<div class="notification is-info is-light">
                    <strong>¡Salida registrada!</strong><br>
                    Se actualizó el stock correctamente.
                </div>';

    echo "<script>
        const doc = parent.document;
        doc.getElementById('resultado').innerHTML = `$mensaje`;

        // Actualizar el stock del option seleccionado
        const select = doc.getElementById('productoSelect');
        const selected = select.querySelector('option[value=\"$producto_id\"]');
        if (selected) {
            selected.setAttribute('data-stock', '$nuevoStock');
        }

        // Resetear formulario
        doc.querySelector('form').reset();

        // Resetear info de stock
        doc.getElementById('stockInfo').textContent = 'Stock disponible: -';
    </script>";

} catch(Exception $e) {
    $pdo->rollBack();
    $mensaje = '<div class="notification is-danger is-light">
                    <strong>¡Error!</strong><br>
                    No se pudo registrar la salida.
                </div>';
    echo "<script>parent.document.getElementById('resultado').innerHTML = `$mensaje`;</script>";
}
