<?php
/*== Almacenando datos ==*/
$product_id_del = limpiar_cadena($_GET['product_id_del']);

/*== Verificando producto ==*/
$check_producto = conexion();
$check_producto = $check_producto->prepare("SELECT * FROM producto WHERE producto_id = :id");
$check_producto->execute([':id' => $product_id_del]);

if ($check_producto->rowCount() == 1) {
    $datos = $check_producto->fetch();

    // Verificar si hay reportes asociados
    $check_reportes = conexion();
    $check_reportes = $check_reportes->prepare("SELECT COUNT(*) FROM reportep WHERE producto_id = :id");
    $check_reportes->execute([':id' => $product_id_del]);
    $reportes_count = $check_reportes->fetchColumn();

    if ($reportes_count > 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡No se puede eliminar el producto!</strong><br>
                Existen reportes asociados a este producto. Elimínelos primero.
            </div>
        ';
    } else {
        $eliminar_producto = conexion();
        $eliminar_producto = $eliminar_producto->prepare("DELETE FROM producto WHERE producto_id = :id");
        $eliminar_producto->execute([':id' => $product_id_del]);

        if ($eliminar_producto->rowCount() == 1) {
            // Eliminar imagen si existe
            if (is_file("./img/producto/" . $datos['producto_foto'])) {
                chmod("./img/producto/" . $datos['producto_foto'], 0777);
                unlink("./img/producto/" . $datos['producto_foto']);
            }

            echo '
                <div class="notification is-info is-light">
                    <strong>¡PRODUCTO ELIMINADO!</strong><br>
                    Los datos del producto se eliminaron con éxito
                </div>
            ';
        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    No se pudo eliminar el producto, por favor intente nuevamente
                </div>
            ';
        }
        $eliminar_producto = null;
    }

} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El PRODUCTO que intenta eliminar no existe
        </div>
    ';
}

$check_producto = null;
?>
