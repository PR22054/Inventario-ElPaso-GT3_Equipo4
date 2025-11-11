<?php
	require_once "./php/main.php";

    $id = (isset($_GET['user_id_up'])) ? $_GET['user_id_up'] : 0;
    $id=limpiar_cadena($id);
?>
<div class="container is-fluid mb-6">
    <div class="contenedor-destacado">
            <?php if($id==$_SESSION['id']){ ?>
                <div style="text-align: right; margin-bottom: 1rem;">
                    <button onclick="history.back()" 
                        style="background-color: black; border: none; border-radius: 4px; padding: 0.4rem; cursor: pointer;">
                        <span class="icon" style="color: white; font-size: 1rem;">
                            <i class="fas fa-arrow-left"></i>
                        </span>
                    </button>
                </div>
                <h1 class="title">Mi cuenta</h1>
                <h2 class="subtitle">Actualizar datos de cuenta</h2>
            <?php }else{ ?>
                <h1 class="title">Usuarios</h1>
                <h2 class="subtitle">Actualizar usuario</h2>
            <?php } ?>

        <div class="container pb-6 pt-6">
            <?php

                include "./inc/btn_back.php";
                
                $check_usuario=conexion();
                $check_usuario=$check_usuario->query("SELECT * FROM usuario WHERE usuario_id='$id'");

                if($check_usuario->rowCount()>0){
                    $datos=$check_usuario->fetch();
            ?>

            <div class="form-rest mb-6 mt-6"></div>

            <form action="./php/usuario_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off" >

                <input type="hidden" name="usuario_id" value="<?php echo $datos['usuario_id']; ?>" required >
                
                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <label>Nombres</label>
                            <input class="input" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required 
                                value="<?php echo $datos['usuario_nombre']; ?>" 
                                oninput="capitalizeFirstLetter(this)" onkeypress="return soloLetras(event)">
                        </div>
                    </div>
                    <div class="column">
                        <div class="control">
                            <label>Apellidos</label>
                            <input class="input" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required 
                                value="<?php echo $datos['usuario_apellido']; ?>" 
                                oninput="capitalizeFirstLetter(this)" onkeypress="return soloLetras(event)">
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <label>Usuario</label>
                            <input class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required value="<?php echo $datos['usuario_usuario']; ?>" >
                        </div>
                    </div>
                    <div class="column">
                        <div class="control">
                            <label>Email</label>
                            <input class="input" type="email" name="usuario_email" maxlength="70" value="<?php echo $datos['usuario_email']; ?>" >
                        </div>
                    </div>
                </div>
                <br><br>
                <p class="has-text-centered">
                    SI desea actualizar la clave de este usuario por favor llene los 2 campos. Si NO desea actualizar la clave deje los campos vacíos.
                </p>
                <br>
                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <label>Clave</label>
                            <div class="field has-addons">
                                <div class="control is-expanded">
                                    <input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" id="clave1">
                                </div>
                                <div class="control">
                                    <button type="button" class="button" onclick="togglePassword('clave1', 'eyeIcon1')">
                                        <span class="icon" id="eyeIcon1">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="control">
                            <label>Repetir clave</label>
                            <div class="field has-addons">
                                <div class="control is-expanded">
                                    <input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" id="clave2">
                                </div>
                                <div class="control">
                                    <button type="button" class="button" onclick="togglePassword('clave2', 'eyeIcon2')">
                                        <span class="icon" id="eyeIcon2">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	
                <?php if ($_SESSION['usuario_tipo'] === 'admin'): ?>		
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Tipo de Usuario</label>
                    <div class="user-type-selector">
                        <div class="user-option <?php echo $datos['usuario_tipo'] === 'admin' ? 'selected' : ''; ?>" data-value="admin" onclick="selectUserType(this)">
                            <span class="icon">💻</span>
                            <span>Administrador</span>
                        </div>
                        <div class="user-option <?php echo $datos['usuario_tipo'] === 'empleado' ? 'selected' : ''; ?>" data-value="empleado" onclick="selectUserType(this)">
                            <span class="icon">👔</span>
                            <span>Empleado</span>
                        </div>
                        <div class="user-option <?php echo $datos['usuario_tipo'] === 'mecanico' ? 'selected' : ''; ?>" data-value="mecanico" onclick="selectUserType(this)">
                            <span class="icon">🔧</span>
                            <span>Mecánico</span>
                        </div>
                    </div>            
                <input type="hidden" name="usuario_tipo" id="tipoUsuario" value="<?php echo $datos['usuario_tipo']; ?>" required>
                </div>
            </div>
        </div>	
        <?php endif; ?>  


        <?php if ($_SESSION['usuario_tipo'] === 'mecanico' || $_SESSION['usuario_tipo'] === 'empleado'): ?>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Tipo de Usuario</label>
                    <div class="user-type-display">
                        <div class="user-badge <?php echo htmlspecialchars($datos['usuario_tipo']); ?>">
                            <span class="icon">
                                <?php 
                                    switch($datos['usuario_tipo']) {
                                        case 'admin': echo '💻'; break;
                                        case 'empleado': echo '👔'; break;
                                        case 'mecanico': echo '🔧'; break;
                                    }
                                ?>
                            </span>
                            <span class="label">
                                <?php 
                                    echo match($datos['usuario_tipo']) {
                                        'admin' => 'Administrador',
                                        'empleado' => 'Empleado',
                                        'mecanico' => 'Mecánico'
                                    };
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>  

                <br><br><br>
                <p class="has-text-centered">
                    Para poder actualizar los datos de este usuario por favor ingrese su USUARIO y CLAVE con la que ha iniciado sesión
                </p>
                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <label>Usuario</label>
                            <input class="input" type="text" name="administrador_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
                        </div>
                    </div>
                    <div class="column">
                        <div class="control">
                            <label>Clave</label>
                            <div class="field has-addons">
                                <div class="control is-expanded">
                                    <input class="input" type="password" name="administrador_clave" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required id="adminClave">
                                </div>
                                <div class="control">
                                    <button type="button" class="button" onclick="togglePassword('adminClave', 'eyeIcon3')">
                                        <span class="icon" id="eyeIcon3">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="has-text-centered">
                    <button type="submit" class="button is-success is-rounded">Actualizar</button>
                </p>
            </form>
            <?php 
                }else{
                    include "./inc/error_alert.php";
                }
                $check_usuario=null;
            ?>
        </div>
    </div>
</div>

<style>
.user-type-selector {
    display: flex;
    gap: 10px;
    margin-top: 8px;
}

label {
    color: #000; 
    font-weight: 600; 
}

.user-option {
    flex: 1;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100px;
}

.user-option:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.user-option.selected {
    border-color: #2196F3;
    background: #e3f2fd;
    box-shadow: 0 2px 8px rgba(33,150,243,0.1);
}

.user-option .icon {
    display: flex;
    font-size: 1.5em;
    margin-bottom: 5px;
    justify-content: center;
    align-items: center;
    height: 40px
}

.user-type-display {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-top: 8px;
}

.user-type-display .user-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 25px;
    border-radius: 20px;
    font-weight: 500;
    width: 100%;
    max-width: 300px;
    text-align: center;
    transition: none;
}

.user-type-display .user-badge .icon {
    font-size: 1.4em;
    flex-shrink: 0;
}

.user-type-display .user-badge .label {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-type-display .user-badge.admin {
    background: #e3f2fd;
    border: 2px solid #2196F3;
    color: #0d47a1;
}

.user-type-display .user-badge.empleado {
    background: #fff3e0;
    border: 2px solid #ff9800;
    color: #e65100;
}

.user-type-display .user-badge.mecanico {
    background: #fbe9e7;
    border: 2px solid #f44336;
    color: #b71c1c;
}

.field.has-addons .button {
    border: 1px solid #dbdbdb;
    border-left: none;
    background-color: white;
}

.field.has-addons .button:hover {
    background-color: #f5f5f5;
}

.field.has-addons .control:first-child .input {
    border-right: none;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.field.has-addons .control:last-child .button {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.control.is-expanded {
    flex-grow: 1;
    flex-shrink: 1;
}
</style>

<script>
let selectedType = null;

// Selección del tipo de usuario
document.addEventListener('DOMContentLoaded', function() {
    const currentType = document.getElementById('tipoUsuario').value;
    const userOptions = document.querySelectorAll('.user-option');
    
    userOptions.forEach(option => {
        if (option.getAttribute('data-value') === currentType) {
            option.classList.add('selected');
            selectedType = option;
        }
    });
});

function selectUserType(element) {    
    if(selectedType) {
        selectedType.classList.remove('selected');
    }
    
    // Marcar nueva selección
    element.classList.add('selected');
    selectedType = element;
    
    // Actualizar campo oculto
    const value = element.getAttribute('data-value');
    document.getElementById('tipoUsuario').value = value;
}

// Función para poner la primera letra en mayúscula
function capitalizeFirstLetter(input) {
    if (input.value.length === 1) {
        input.value = input.value.toUpperCase();
    } else if (input.value.length > 1) {
        // Primera letra mayuscula
        input.value = input.value.replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });
    }
}

// Función para permitir solo letras y espacios
function soloLetras(event) {
    const charCode = event.keyCode || event.which;
    const charStr = String.fromCharCode(charCode);
        
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]$/.test(charStr)) {
        event.preventDefault();
        return false;
    }
    return true;
}

// Función para mostrar/ocultar contraseña
function togglePassword(passwordId, eyeIconId) {
    const passwordInput = document.getElementById(passwordId);
    const eyeIcon = document.getElementById(eyeIconId);
    const icon = eyeIcon.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>