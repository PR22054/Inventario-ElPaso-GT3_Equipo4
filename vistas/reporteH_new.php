<div class="container is-fluid mb-6">
	<div class="contenedor-destacado">
			<h1 class="title">Reportes</h1>
			<h2 class="subtitle">Ingreso Reportes</h2>

		<div class="container pb-6 pt-6">
			<?php
			require_once "./php/main.php";
			?>

			<div class="form-rest mb-6 mt-6"></div>

			<form action="./php/reporteH_guardar.php" method="POST" class="FormularioAjax" autocomplete="off"
				enctype="multipart/form-data">
				<div class="columns">
					<div class="column">
						<label>Usuario</label><br>
						<div class="select is-rounded">
							<select name="reporteH_persona">
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
						<label>Producto</label><br>
						<div class="select is-rounded">
							<select name="reporteH_herramienta">
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
							<input class="input" type="text" name="reporteH_detalles"
								pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,150}" maxlength="70" required>
						</div>
					</div>				
					 <div class="column">
                    <div class="select is-rounded">
                        <label for="reporteH_tipo">Tipo de Incidencia</label>
                        <select class="select" id="reporteH_tipo" name="reporteH_tipo" required>
                            <option value="">Seleccione una opción</option>
                            <option value="robo">Robo</option>
                            <option value="rota">Rota</option>
                        </select>
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