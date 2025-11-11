<div class="container is-fluid mb-6">
    <div class="contenedor-destacado">
        <div style="text-align: right; margin-bottom: 1rem;">
            <button onclick="history.back()" 
                style="background-color: black; border: none; border-radius: 4px; padding: 0.4rem; cursor: pointer;">
                <span class="icon" style="color: white; font-size: 1rem;">
                    <i class="fas fa-arrow-left"></i>
                </span>
            </button>
        </div>
        <h1 class="title">Reportes</h1>
        <h2 class="subtitle">Ingreso Reportes Herramientas</h2>

        <div class="container pb-6 pt-6">
            <?php
            require_once "./php/main.php";
            ?>

            <div class="form-rest mb-6 mt-6"></div>

            <form action="./php/reporteH_guardar.php" method="POST" class="FormularioAjax" autocomplete="off"
                enctype="multipart/form-data">
                <div class="columns">
                    <!-- Usuario que reporta (sesión actual - oculto) -->
                    <input type="hidden" name="usuario_id" value="<?php echo $_SESSION['id']; ?>">
                    
                    <!-- Usuario involucrado (seleccionable) -->
                    <div class="column">
                        <label class="label">Usuario Involucrado</label>
                        <div class="select is-rounded is-fullwidth">
                            <select name="reporteH_persona" required>
                                <option value="" selected="">Seleccione un usuario</option>
                                <?php
                                $usuarios = conexion();
                                $usuarios = $usuarios->query("SELECT * FROM usuario WHERE usuario_id != '".$_SESSION['id']."' ORDER BY usuario_nombre");
                                if ($usuarios->rowCount() > 0) {
                                    $usuarios = $usuarios->fetchAll();
                                    foreach ($usuarios as $row) {
                                        echo '<option value="' . $row['usuario_id'] . '">' . $row['usuario_nombre'] . ' ' . $row['usuario_apellido'] . '</option>';
                                    }
                                }
                                $usuarios = null;
                                ?>
                            </select>
                        </div>
                        <p class="help">Seleccione el usuario relacionado con el reporte</p>
                    </div>
                                                    
                    <!-- Herramienta -->
                    <div class="column">
                        <label class="label">Herramienta</label>
                        <div class="select is-rounded is-fullwidth">
                            <select name="reporteH_herramienta" required>
                                <option value="" selected="">Seleccione una herramienta</option>
                                <?php
                                $herramientas = conexion();
                                $herramientas = $herramientas->query("SELECT * FROM herramienta ORDER BY herramienta_nombre");
                                if ($herramientas->rowCount() > 0) {
                                    $herramientas = $herramientas->fetchAll();
                                    foreach ($herramientas as $row) {
                                        echo '<option value="' . $row['herramienta_id'] . '">' . $row['herramienta_nombre'] . '</option>';
                                    }
                                }
                                $herramientas = null;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="columns">
                    <!-- Detalles -->
                    <div class="column">
                        <div class="field">
                            <label class="label">Detalles</label>
                            <div class="control">
                                <textarea class="textarea" name="reporteH_detalles" 
                                    pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,150}" maxlength="150" required 
                                    placeholder="Ingrese los detalles del reporte" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tipo de Incidencia -->
                    <div class="column">
                        <div class="field">
                            <label class="label">Tipo de Incidencia</label>
                            <div class="select is-rounded is-fullwidth">
                                <select name="reporteH_tipo" required>
                                    <option value="" selected="">Seleccione una opción</option>
                                    <option value="robo">Robo</option>
                                    <option value="rota">Rota</option>
                                    <option value="perdida">Pérdida</option>
                                    <option value="averia">Avería</option>
                                    <option value="mantenimiento">Mantenimiento</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del usuario actual -->
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Reportado por</label>
                            <div class="control">
                                <input class="input" type="text" 
                                    value="<?php 
                                        // Mostrar el nombre del usuario actual de la sesión
                                        if(isset($_SESSION['id'])) {
                                            $usuario_actual = conexion();
                                            $usuario_actual = $usuario_actual->query("SELECT usuario_nombre, usuario_apellido FROM usuario WHERE usuario_id = '".$_SESSION['id']."'");
                                            if($usuario_actual->rowCount() > 0){
                                                $user_data = $usuario_actual->fetch();
                                                echo $user_data['usuario_nombre'] . ' ' . $user_data['usuario_apellido'];
                                            }
                                            $usuario_actual = null;
                                        }
                                    ?>" 
                                    readonly style="background-color: #f5f5f5;">
                            </div>
                            <p class="help">Usted está registrando este reporte</p>
                        </div>
                    </div>
                </div>
                
                <div class="field is-grouped is-grouped-centered mt-4">
                    <div class="control">
                        <button type="submit" class="button is-info is-rounded">Guardar Reporte</button>
                    </div>
                </div>
            </form>
        </div>
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