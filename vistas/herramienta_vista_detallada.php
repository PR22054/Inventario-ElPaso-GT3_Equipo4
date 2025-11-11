<?php
require_once "./php/main.php";

$id = (isset($_GET['herramienta_id'])) ? limpiar_cadena($_GET['herramienta_id']) : 0;

$check_herramienta = conexion();
$check_herramienta = $check_herramienta->query("SELECT * FROM herramienta WHERE herramienta_id='$id'");

if ($check_herramienta->rowCount() > 0) {
    $datos = $check_herramienta->fetch();
    ?>
    <div class="container" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
        <div style="width: 100%; max-width: 900px;">
                        
            <div class="card" style="background-color: rgba(255, 255, 255, 0.97);">
                        
            
            <div class="card-content">

                    <div style="text-align: right; margin-bottom: 1rem;">
                        <button onclick="history.back()" 
                            style="background-color: black; border: none; border-radius: 4px; padding: 0.4rem; cursor: pointer;">
                            <span class="icon" style="color: white; font-size: 1rem;">
                                <i class="fas fa-arrow-left"></i>
                            </span>
                        </button>
                    </div>

                    <h1 class="title has-text-centered">Vista Detallada</h1>
                    <br>

                    <div class="columns is-vcentered">
                        
                        <!-- Columna Imagen -->
                        <div class="column is-4 has-text-centered">
                            <?php if($datos['herramienta_foto'] != "" && is_file("./img/herramienta/".$datos['herramienta_foto'])): ?>
                                <figure class="image is-128x128 is-inline-block">
                                    <img src="./img/herramienta/<?php echo $datos['herramienta_foto']; ?>" alt="Imagen de la herramienta">
                                </figure>
                            <?php else: ?>
                                <figure class="image is-128x128 is-inline-block">
                                    <img src="./img/no-image.png" alt="Sin imagen">
                                </figure>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Columna Datos -->
                        <div class="column is-8">
                            <p><strong>Código:</strong> <?php echo $datos['herramienta_codigo']; ?></p>
                            <br>
                            <p><strong>Nombre:</strong> <?php echo $datos['herramienta_nombre']; ?></p>
                            <br>
                            <p><strong>Descripción:</strong> <?php echo $datos['herramienta_descripcion']; ?></p>
                            <br>
                            <p><strong>Precio:</strong> $<?php echo $datos['herramienta_precio']; ?></p>
                            <br>
                            <p><strong>Necesita Mantenimiento:</strong> 
                                <?php echo ($datos['necesita_mantenimiento'] == 1) ? "Sí" : "No"; ?>
                            </p>
                        </div>
                        
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php
} else {
    echo '<div class="notification is-warning has-text-centered">⚠ No se encontró la herramienta solicitada.</div>';
}
$check_herramienta = null;
?>
