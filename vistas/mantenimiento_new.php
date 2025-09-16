<div class="container is-fluid mb-6">
	<div class="contenedor-destacado">
			<h1 class="title">Mantenimientos</h1>
			<h2 class="subtitle">Registro de Mantenimientos</h2>

		<div class="container pb-6 pt-6">
			<?php
			require_once "./php/main.php";
			?>

			<div class="form-rest mb-6 mt-6"></div>

			<form action="./php/mantenimiento_guardar.php" method="POST" class="FormularioAjax" autocomplete="off"
				enctype="multipart/form-data">
				<div class="columns">
					<div class="column">
						<label>Usuario</label><br>
						<div class="select is-rounded">
							<select name="mantenimiento_persona">
								<option value="" selected="">Seleccione un usuario</option>
								<?php
								$categorias = conexion();
								$categorias = $categorias->query("SELECT * FROM usuario");
								if ($categorias->rowCount() > 0) {
									$categorias = $categorias->fetchAll();
									foreach ($categorias as $row) {
										echo '<option value="' . $row['usuario_id'] . '" >' . $row['usuario_nombre'] . '</option>';
									}
								}
								$categorias = null;
								?>
							</select>
						</div>
					</div>
														
					<div class="column">
						<label>Herramientas</label><br>
						<div class="select is-rounded">
							<select name="mantenimiento_herramienta">
								<option value="" selected="">Seleccione una opción</option>
								<?php
								$categorias = conexion();
								$categorias = $categorias->query("SELECT * FROM herramienta");
								if ($categorias->rowCount() > 0) {
									$categorias = $categorias->fetchAll();
									foreach ($categorias as $row) {
										echo '<option value="' . $row['herramienta_id'] . '" >' . $row['herramienta_nombre'] . '</option>';
									}
								}
								$categorias = null;
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="columns">
					<div class="column">
						<div class="control">
							<label>Detalles</label>
							<input class="input" type="text" name="mantenimiento_detalles"
								pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,150}" maxlength="70" required>
						</div>
					</div>			
					 <div class="column">
                     <div class="columns">
                    <div class="column">
                        <div class="control">
                            <label>Fecha de Inicio</label>
                            <input class="input" type="date" name="mantenimiento_fecha1" required>
                        </div>
                    </div>
                    <div class="column">
                        <div class="control">
                            <label>Fecha de Finalización</label>
                            <input class="input" type="date" name="mantenimiento_fecha2">
                        </div>
                    </div>
                </div>
				</div>
				
				<p class="has-text-centered">
					<button type="submit" class="button is-info is-rounded">Guardar</button>
				</p>
			</form>
		</div>
	</div>
</div>

<style>
	
label {
    color: #000; 
    font-weight: 600; 
}

</style>