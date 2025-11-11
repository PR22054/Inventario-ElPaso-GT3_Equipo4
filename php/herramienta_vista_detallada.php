<?php
require_once "main.php";

if(isset($_GET['herramienta_id']) && is_numeric($_GET['herramienta_id'])){
    $id = limpiar_cadena($_GET['herramienta_id']);

    $conexion = conexion();

    // Traemos exactamente los campos de la tabla herramienta
    $consulta = $conexion->prepare("SELECT herramienta_id, herramienta_codigo, herramienta_nombre, herramienta_descripcion, herramienta_precio, herramienta_foto, necesita_mantenimiento FROM herramienta WHERE herramienta_id = :id LIMIT 1");
    $consulta->bindParam(":id", $id, PDO::PARAM_INT);
    $consulta->execute();

    if($consulta->rowCount() > 0){
        $herramienta = $consulta->fetch(PDO::FETCH_ASSOC);

        echo '
        <div class="box">
            <h2 class="title is-4">Detalles de la Herramienta</h2>
            <p><strong>ID:</strong> '.$herramienta['herramienta_id'].'</p>
            <p><strong>Código:</strong> '.$herramienta['herramienta_codigo'].'</p>
            <p><strong>Nombre:</strong> '.$herramienta['herramienta_nombre'].'</p>
            <p><strong>Descripción:</strong> '.$herramienta['herramienta_descripcion'].'</p>
            <p><strong>Precio:</strong> $'.$herramienta['herramienta_precio'].'</p>
            
            <p><strong>Foto:</strong><br>';
            if(!empty($herramienta['herramienta_foto']) && is_file("./img/herramienta/".$herramienta['herramienta_foto'])){
                echo '<img src="./img/herramienta/'.$herramienta['herramienta_foto'].'" width="200">';
            }else{
                echo '<img src="./img/herramienta.png" width="200">';
            }
        echo '</p>
            
            <p><strong>¿Necesita Mantenimiento?:</strong> '.($herramienta['necesita_mantenimiento'] == 1 ? "Sí" : "No").'</p>
        </div>
        ';
    }else{
        echo '<p class="has-text-centered">⚠ No se encontró la herramienta solicitada.</p>';
    }

    $conexion = null;

}else{
    echo '<p class="has-text-centered">⚠ Parámetro inválido.</p>';
}
?>
