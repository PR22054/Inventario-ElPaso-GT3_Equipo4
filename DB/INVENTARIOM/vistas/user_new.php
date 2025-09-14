<div class="container is-fluid mb-6">
	<h1 class="title">Usuarios</h1>
	<h2 class="subtitle">Nuevo usuario</h2>
</div>
<div class="container pb-6 pt-6">

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/usuario_guardar.php" method="POST" class="FormularioAjax" autocomplete="off" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombres</label>
				  	<input class="input" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Apellidos</label>
				  	<input class="input" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Usuario</label>
				  	<input class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Email</label>
				  	<input class="input" type="email" name="usuario_email" maxlength="70" >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Clave</label>
				  	<input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Repetir clave</label>
				  	<input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
    <div class="column">
        <div class="control">
            <label>Tipo de Usuario</label>
            <div class="user-type-selector">
                <div class="user-option" data-value="admin" onclick="selectUserType(this)">
                    <span class="icon">💻</span>
                    <span>Administrador</span>
                </div>
                <div class="user-option" data-value="empleado" onclick="selectUserType(this)">
                    <span class="icon">👔</span>
                    <span>Empleado</span>
                </div>
                <div class="user-option" data-value="mecanico" onclick="selectUserType(this)">
                    <span class="icon">🔧</span>
                    <span>Mecánico</span>
                </div>
            </div>            
		<input type="hidden" name="usuario_tipo" id="tipoUsuario" required>
        </div>
    </div>	  	
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>

<!-- Estilos de varios -->
<style>
.user-type-selector {
    display: flex;
    gap: 10px;
    margin-top: 8px;
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
    display: block;
    font-size: 1.5em;
    margin-bottom: 5px;
}
</style>

<script>
let selectedType = null;

function selectUserType(element) {
    // Quitar selección anterior
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
</script>