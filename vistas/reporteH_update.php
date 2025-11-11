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
        <h2 class="subtitle has-text-centered">Actualizar Reporte de Herramientas</h2>

        <?php
        include "./inc/btn_back.php";
        require_once "./php/main.php";

        $id = (isset($_GET['reporteh_id_up'])) ? $_GET['reporteh_id_up'] : 0;
        $id = limpiar_cadena($id);

        $check_reporteh = conexion();
        $check_reporteh = $check_reporteh->query("SELECT * FROM reporteh WHERE reporteh_id='$id'");

        if($check_reporteh->rowCount() > 0){
            $datos = $check_reporteh->fetch();
        ?>

        <form action="./php/reporteh_actualizar.php" method="POST" id="formActualizarReporteh" autocomplete="off">
            <input type="hidden" name="reporteh_id" value="<?php echo $datos['reporteh_id']; ?>" required>

            <div class="columns is-multiline">
                <!-- Persona (Select como en el registro) -->
                <div class="column is-half">
                    <label class="label">Usuario</label>
                    <div class="select is-rounded is-fullwidth">
                        <select name="reporteh_persona" required>
                            <option value="" selected="">Seleccione un usuario</option>
                            <?php
                            $usuarios = conexion();
                            $usuarios = $usuarios->query("SELECT * FROM usuario");
                            if ($usuarios->rowCount() > 0) {
                                $usuarios = $usuarios->fetchAll();
                                foreach ($usuarios as $row) {
                                    // Comparar con el valor actual guardado en reporteh_persona
                                    $selected = ($datos['reporteh_persona'] == $row['usuario_id']) ? 'selected' : '';
                                    echo '<option value="' . $row['usuario_id'] . '" ' . $selected . '>' . $row['usuario_nombre'] . '</option>';
                                }
                            }
                            $usuarios = null;
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Herramienta -->
                <div class="column is-half">
                    <label class="label">Herramienta</label>
                    <div class="select is-fullwidth is-rounded">
                        <select name="herramienta_id" required>
                            <option value="" selected>Seleccione una opción</option>
                            <?php
                            $herramientas = conexion();
                            $herramientas = $herramientas->query("SELECT * FROM herramienta");
                            if ($herramientas->rowCount() > 0) {
                                $herramientas = $herramientas->fetchAll();
                                foreach ($herramientas as $row) {
                                    $selected = ($datos['herramienta_id'] == $row['herramienta_id']) ? 'selected' : '';
                                    echo '<option value="'.$row['herramienta_id'].'" '.$selected.'>'.$row['herramienta_nombre'].'</option>';
                                }
                            }
                            $herramientas = null;
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Detalles -->
                <div class="column is-full">
                    <label class="label">Detalles</label>
                    <div class="control">
                        <textarea class="textarea" name="reporteh_detalles" maxlength="150" required 
                            placeholder="Ingrese los detalles del reporte" rows="3"><?php echo $datos['reporteh_detalles']; ?></textarea>
                    </div>
                </div>

                <!-- Tipo -->
                <div class="column is-half">
                    <label class="label">Tipo de Incidencia</label>
                    <div class="select is-fullwidth is-rounded">
                        <select name="reporteh_tipo" required>
                            <option value="robo" <?php echo ($datos['reporteh_tipo']=='robo')?'selected':''; ?>>Robo</option>
                            <option value="rota" <?php echo ($datos['reporteh_tipo']=='rota')?'selected':''; ?>>Rota</option>
                            <option value="perdida" <?php echo ($datos['reporteh_tipo']=='perdida')?'selected':''; ?>>Pérdida</option>
                            <option value="averia" <?php echo ($datos['reporteh_tipo']=='averia')?'selected':''; ?>>Avería</option>
                            <option value="mantenimiento" <?php echo ($datos['reporteh_tipo']=='mantenimiento')?'selected':''; ?>>Mantenimiento</option>
                        </select>
                    </div>
                </div>

                <!-- Información del usuario que registró (solo lectura) -->
                <div class="column is-half">
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

            <div class="field is-grouped is-grouped-centered mt-4">
                <button type="submit" class="button is-success is-rounded">Actualizar</button>
            </div>

            <div class="form-rest mt-4"></div>
        </form>

        <?php 
        } else {
            include "./inc/error_alert.php";
        }
        $check_reporteh = null;
        ?>

    </div>
</div>

<style>
.label {
    color: #000; 
    font-weight: 600; 
}

.contenedor-destacado {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.select, .input, .textarea {
    border-radius: 6px;
}

.help {
    font-size: 0.8rem;
    margin-top: 0.25rem;
    color: #7a7a7a;
}
</style>

<script>
document.getElementById('formActualizarReporteh').addEventListener('submit', function(e){
    e.preventDefault();
    let form = e.target;
    let data = new FormData(form);

    fetch(form.action, { method: 'POST', body: data })
        .then(res => res.text())
        .then(res => {
            document.querySelector('.form-rest').innerHTML = res;
        })
        .catch(err => console.error(err));
});
</script>