<div class="container is-fluid mb-6">
	<div class="contenedor-destacado">
		<h1 class="title">Inventario</h1>
		<h2 class="subtitle">Ingreso herramienta</h2>

		<div class="container pb-6 pt-6">
			<?php
			require_once "./php/main.php";
			?>

			<div class="form-rest mb-6 mt-6"></div>

			<form action="./php/herramienta_guardar.php" method="POST" class="FormularioAjax" autocomplete="off"
				enctype="multipart/form-data">
				<div class="columns">
					<div class="column">
						<div class="control">
							<label>Asignación</label>
							<input class="input" type="text" name="herramienta_codigo" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70"
								required>
						</div>
					</div>
					<div class="column">
						<div class="control">
							<label>Nombre herramienta</label>
							<input class="input" type="text" name="herramienta_nombre"
								pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" required>
						</div>
					</div>
				</div>
				<div class="columns">
					<div class="column">
						<div class="control">
							<label>Costo estimado</label>
							<input class="input" type="text" name="herramienta_precio" pattern="[0-9.]{1,25}" maxlength="25"
								required>
						</div>
					</div>
					<div class="column">
						<div class="control">
							<label>Cantidad</label>
							<input class="input" type="text" name="herramienta_stock" pattern="[0-9]{1,25}" maxlength="25"
								required>
						</div>
					</div>
					<div class="column">
						<label>Categoría</label><br>
						<div class="select is-rounded">
							<select name="herramienta_categoria">
								<option value="" selected="">Seleccione una opción</option>
								<?php
								$categorias = conexion();
								$categorias = $categorias->query("SELECT * FROM categoria WHERE categoria_tipo = 'ambas' OR categoria_tipo = 'herramienta'");
								if ($categorias->rowCount() > 0) {
									$categorias = $categorias->fetchAll();
									foreach ($categorias as $row) {
										echo '<option value="' . $row['categoria_id'] . '" >' . $row['categoria_nombre'] . '</option>';
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
						<label>Foto o imagen</label><br>
						<div class="file is-small has-name">
							<label class="file-label">
								<input class="file-input" type="file" name="herramienta_foto" accept=".jpg, .png, .jpeg">
								<span class="file-cta">
									<span class="file-label">Imagen</span>
								</span>
								<span class="file-name">JPG, JPEG, PNG. (MAX 3MB)</span>
							</label>
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