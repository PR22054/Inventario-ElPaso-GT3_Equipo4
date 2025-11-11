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
        <h1 class="title has-text-centered">Mantenimiento</h1>
        <h2 class="subtitle has-text-centered">Actualizar Mantenimiento</h2>

        <?php
        include "./inc/btn_back.php";
        require_once "./php/main.php";

        $id = (isset($_GET['mantenimiento_id_up'])) ? $_GET['mantenimiento_id_up'] : 0;
        $id = limpiar_cadena($id);

        /*== Verificando mantenimiento ==*/
        $check_mantenimiento = conexion();
        $check_mantenimiento = $check_mantenimiento->query("SELECT * FROM mantenimiento WHERE mantenimiento_id='$id'");

        if($check_mantenimiento->rowCount() > 0){
            $datos = $check_mantenimiento->fetch();
            
            // Obtener nombre de la persona asignada
            $nombre_persona = $datos['mantenimiento_persona'];
            if(is_numeric($datos['mantenimiento_persona'])) {
                $persona_query = conexion();
                $persona_query = $persona_query->query("SELECT usuario_nombre, usuario_apellido FROM usuario WHERE usuario_id = '".$datos['mantenimiento_persona']."'");
                if($persona_query->rowCount() > 0){
                    $persona_data = $persona_query->fetch();
                    $nombre_persona = $persona_data['usuario_nombre'] . ' ' . $persona_data['usuario_apellido'];
                }
                $persona_query = null;
            }
        ?>

        <h2 class="title has-text-centered"><?php echo $nombre_persona; ?></h2>

        <form action="./php/mantenimiento_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off">
            
            <!-- ID oculto -->
            <input type="hidden" name="mantenimiento_id" value="<?php echo $datos['mantenimiento_id']; ?>" required>

            <div class="columns is-multiline">
                <!-- Persona Asignada -->
<div class="column is-half">
    <div class="field">
        <label class="label">Persona Asignada</label>
        <div class="control">
            <div class="select is-fullwidth is-rounded">
                <select name="mantenimiento_persona" required>
                    <option value="">Seleccione un usuario</option>
                    <?php
                        $conn = conexion();
                        $usuarios_stmt = $conn->query("SELECT * FROM usuario ORDER BY usuario_nombre");
                        $usuarios = $usuarios_stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach($usuarios as $u){
                            // Asegurar que ambos sean del mismo tipo
                            $persona_actual = (string)$datos['mantenimiento_persona'];
                            $usuario_id = (string)$u['usuario_id'];
                            
                            $selected = ($persona_actual === $usuario_id) ? 'selected' : '';
                            echo '<option value="'.$u['usuario_id'].'" '.$selected.'>'.$u['usuario_nombre'].' '.$u['usuario_apellido'].'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
</div>

                <!-- Herramienta -->
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Herramienta</label>
                        <div class="control">
                            <div class="select is-fullwidth is-rounded">
                                <select name="herramienta_id" required>
                                    <option value="">Seleccione una herramienta</option>
                                    <?php
                                        $herramientas_stmt = $conn->query("SELECT * FROM herramienta ORDER BY herramienta_nombre");
                                        $herramientas = $herramientas_stmt->fetchAll(PDO::FETCH_ASSOC);

                                        foreach($herramientas as $h){
                                            $selected = ($datos['herramienta_id'] == $h['herramienta_id']) ? 'selected' : '';
                                            echo '<option value="'.$h['herramienta_id'].'" '.$selected.'>'.$h['herramienta_nombre'].'</option>';
                                        }

                                        $conn = null;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles -->
                <div class="column is-full">
                    <div class="field">
                        <label class="label">Detalles del Mantenimiento</label>
                        <div class="control">
                            <textarea class="textarea" name="mantenimiento_detalles" maxlength="150" required 
                                placeholder="Describa los detalles del mantenimiento" rows="3"><?php echo $datos['mantenimiento_detalles']; ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Fecha Inicio -->
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Fecha de Inicio</label>
                        <div class="control">
                            <input class="input" type="date" name="mantenimiento_fecha1" required
                                   value="<?php echo $datos['mantenimiento_fecha1']; ?>">
                        </div>
                    </div>
                </div>

                <!-- Fecha Fin -->
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Fecha de Finalización</label>
                        <div class="control">
                            <input class="input" type="date" name="mantenimiento_fecha2"
                                   value="<?php echo $datos['mantenimiento_fecha2']; ?>">
                        </div>
                    </div>
                </div>

                <!-- Información del usuario que registró (solo lectura) -->
                <div class="column is-full">
                    <div class="field">
                        <label class="label">Registrado por</label>
                        <div class="control">
                            <input class="input" type="text" value="<?php 
                                // Obtener nombre del usuario que creó el registro
                                $usuario_creador = conexion();
                                $usuario_creador = $usuario_creador->query("SELECT usuario_nombre, usuario_apellido FROM usuario WHERE usuario_id = '".$datos['usuario_id']."'");
                                if($usuario_creador->rowCount() > 0){
                                    $user_data = $usuario_creador->fetch();
                                    echo $user_data['usuario_nombre'] . ' ' . $user_data['usuario_apellido'];
                                }
                                $usuario_creador = null;
                            ?>" readonly style="background-color: #f5f5f5;">
                        </div>
                        <p class="help">Usuario que creó el registro (no editable)</p>
                    </div>
                </div>
            </div>

            <div class="field is-grouped is-grouped-centered mt-4">
                <div class="control">
                    <button type="submit" class="button is-success is-rounded">Actualizar</button>
                </div>
            </div>

            <div class="form-rest mt-4"></div>
        </form>

        <?php 
        } else {
            include "./inc/error_alert.php";
        }
        $check_mantenimiento = null;
        ?>

    </div>
</div>

<script>
// Validación de fechas
document.addEventListener('DOMContentLoaded', function() {
    const fechaInicio = document.querySelector('input[name="mantenimiento_fecha1"]');
    const fechaFin = document.querySelector('input[name="mantenimiento_fecha2"]');
    
    if (fechaInicio && fechaFin) {
        // Validar que la fecha de fin no sea menor que la de inicio
        fechaInicio.addEventListener('change', function() {
            if (fechaFin.value && this.value > fechaFin.value) {
                fechaFin.value = this.value;
            }
            fechaFin.min = this.value;
        });
        
        fechaFin.addEventListener('change', function() {
            if (fechaInicio.value && this.value < fechaInicio.value) {
                this.value = fechaInicio.value;
            }
        });
    }
});

// Manejo del formulario AJAX
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

<style>
/* Diseños varios */ 
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh; 
}

.main-container {
    width: 100%;
    max-width: 1100px;
    margin: 40px auto; 
    background-color: #fff;
    padding: 50px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.contenedor-destacado .columns {
    margin-left: 0;
    margin-right: 0;
}

.contenedor-destacado .column {
    padding-left: 10px;
    padding-right: 10px;
}

.main-container > div:first-child {
    text-align: right;
    margin-bottom: 1rem;
}

.help {
    font-size: 0.8rem;
    margin-top: 0.25rem;
    color: #7a7a7a;
}

footer {
    margin-top: auto; 
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 1rem 0;
}
</style>