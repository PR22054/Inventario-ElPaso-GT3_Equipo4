<div class="main-container">
    <div class="contenedor-destacado">
        <div style="text-align: right; margin-bottom: 1rem;">
            <button onclick="history.back()" 
                style="background-color: black; border: none; border-radius: 4px; padding: 0.4rem; cursor: pointer;">
                <span class="icon" style="color: white; font-size: 1rem;">
                    <i class="fas fa-arrow-left"></i>
                </span>
            </button>
        </div>
        <h1 class="title has-text-centered">Reporte</h1>
        <h2 class="subtitle has-text-centered">Actualizar Reporte de Productos</h2>

        <?php
        include "./inc/btn_back.php";
        require_once "./php/main.php";

        $id = (isset($_GET['reportep_id_up'])) ? $_GET['reportep_id_up'] : 0;
        $id = limpiar_cadena($id);

        $check_reportep = conexion();
        $check_reportep = $check_reportep->query("SELECT * FROM reportep WHERE reportep_id='$id'");

        if($check_reportep->rowCount() > 0){
            $datos = $check_reportep->fetch();
        ?>

        <form action="./php/reporteP_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off">
            <input type="hidden" name="reportep_id" value="<?php echo $datos['reportep_id']; ?>" required>

            <div class="columns is-multiline">
                <!-- Persona del Reporte (Usuario Involucrado) -->
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Persona del Reporte</label>
                        <div class="control">
                            <div class="select is-fullwidth is-rounded">
                                <select name="reporteP_persona" required>
                                    <option value="" selected>Seleccione un usuario</option>
                                    <?php
                                        $usuarios = conexion()->query("SELECT * FROM usuario ORDER BY usuario_nombre")->fetchAll();
                                        foreach ($usuarios as $row) {
                                            $selected = ($datos['reportep_persona'] == $row['usuario_id']) ? 'selected' : '';
                                            echo '<option value="'.$row['usuario_id'].'" '.$selected.'>'.$row['usuario_nombre'].' '.$row['usuario_apellido'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <p class="help">Usuario relacionado con el incidente</p>
                    </div>
                </div>

                <!-- Producto -->
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Producto</label>
                        <div class="control">
                            <div class="select is-fullwidth is-rounded">
                                <select name="reporteP_producto" required>
                                    <option value="" selected>Seleccione un producto</option>
                                    <?php
                                    $productos = conexion();
                                    $productos = $productos->query("SELECT * FROM producto ORDER BY producto_nombre");
                                    if ($productos->rowCount() > 0) {
                                        $productos = $productos->fetchAll();
                                        foreach ($productos as $row) {
                                            $selected = ($datos['producto_id'] == $row['producto_id']) ? 'selected' : '';
                                            echo '<option value="' . $row['producto_id'] . '" '.$selected.'>' . $row['producto_nombre'] . '</option>';
                                        }
                                    }
                                    $productos = null;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles -->
                <div class="column is-full">
                    <div class="field">
                        <label class="label">Detalles</label>
                        <div class="control">
                            <textarea class="textarea" name="reporteP_detalles" maxlength="150" required 
                                placeholder="Ingrese los detalles del reporte" rows="3"><?php echo $datos['reportep_detalles']; ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Cantidad -->
                <div class="column is-one-quarter">
                    <div class="field">
                        <label class="label">Cantidad</label>
                        <div class="control">
                            <input class="input" type="number" name="reporteP_cantidad"
                                   min="1" max="999999" required
                                   value="<?php echo $datos['reportep_cantidad']; ?>">
                        </div>
                    </div>
                </div>

                <!-- Tipo de Incidencia -->
                <div class="column is-one-quarter">
                    <div class="field">
                        <label class="label">Tipo de Incidencia</label>
                        <div class="control">
                            <div class="select is-fullwidth is-rounded">
                                <select name="reporteP_tipo" required>
                                    <option value="" selected>Seleccione una opción</option>
                                    <option value="robo" <?php echo ($datos['reportep_tipo']=='robo')?'selected':''; ?>>Robo</option>
                                    <option value="rota" <?php echo ($datos['reportep_tipo']=='rota')?'selected':''; ?>>Rota</option>
                                    <option value="perdida" <?php echo ($datos['reportep_tipo']=='perdida')?'selected':''; ?>>Pérdida</option>
                                    <option value="averia" <?php echo ($datos['reportep_tipo']=='averia')?'selected':''; ?>>Avería</option>
                                    <option value="vencido" <?php echo ($datos['reportep_tipo']=='vencido')?'selected':''; ?>>Vencido</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del usuario que registró (solo lectura) -->
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Registrado por</label>
                        <div class="control">
                            <input class="input" type="text" value="<?php 
                                // Obtener nombre del usuario que creó el reporte
                                $usuario_creador = conexion();
                                $usuario_creador = $usuario_creador->query("SELECT usuario_nombre, usuario_apellido FROM usuario WHERE usuario_id = '".$datos['usuario_id']."'");
                                if($usuario_creador->rowCount() > 0){
                                    $user_data = $usuario_creador->fetch();
                                    echo $user_data['usuario_nombre'] . ' ' . $user_data['usuario_apellido'];
                                }
                                $usuario_creador = null;
                            ?>" readonly style="background-color: #f5f5f5;">
                        </div>
                        <p class="help">Usuario que creó el reporte (no editable)</p>
                    </div>
                </div>
            </div>

            <!-- Botón -->
            <div class="field is-grouped is-grouped-centered mt-4">
                <div class="control">
                    <button type="submit" class="button is-success is-rounded">Actualizar</button>
                </div>
            </div>

            <!-- Contenedor para mostrar mensajes -->
            <div class="form-rest mt-4"></div>
        </form>

        <?php
        } else {
            include "./inc/error_alert.php";
        }
        $check_reportep = null;
        ?>

    </div>
</div>

<style>
.main-container {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    padding: 20px;
    background-color: #f5f5f5;
}

.contenedor-destacado {
    width: 100%;
    max-width: 900px;
    background-color: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.contenedor-destacado .columns {
    margin-left: 0;
    margin-right: 0;
}

.contenedor-destacado .column {
    padding-left: 10px;
    padding-right: 10px;
}

.help {
    font-size: 0.8rem;
    margin-top: 0.25rem;
    color: #7a7a7a;
}
</style>

<script>
// Validación de cantidad
document.addEventListener('DOMContentLoaded', function() {
    const cantidadInput = document.querySelector('input[name="reporteP_cantidad"]');
    
    if (cantidadInput) {
        cantidadInput.addEventListener('blur', function() {
            if (this.value < 1) {
                this.value = 1;
            }
            if (this.value > 999999) {
                this.value = 999999;
            }
        });
    }
});

document.querySelector('.FormularioAjax').addEventListener('submit', function(e){
    e.preventDefault();
    let form = e.target;
    let data = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: data
    })
    .then(res => res.text())
    .then(res => {
        document.querySelector('.form-rest').innerHTML = res;
    })
    .catch(err => console.error(err));
});
</script>