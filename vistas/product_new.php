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
		<h1 class="title">Inventario</h1>
		<h2 class="subtitle">Ingreso producto</h2>

		<div class="container pb-6 pt-6">
			<?php
			require_once "./php/main.php";
			?>

			<div class="form-rest mb-6 mt-6"></div>

			<form action="./php/producto_guardar.php" method="POST" class="FormularioAjax" autocomplete="off"
				enctype="multipart/form-data">
				<div class="columns">
					<div class="column">
						<div class="control">
							<label>Asignación</label>
							<input class="input" type="text" name="producto_codigo" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70"
								required>
						</div>
					</div>
					<div class="column">
						<div class="control">
							<label>Nombre artículo</label>
							<input class="input" type="text" name="producto_nombre"
								pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" required>
						</div>
					</div>
				</div>
				<div class="columns">
					<div class="column">
						<div class="control">
							<label>Costo estimado</label>
							<input class="input" type="number" name="producto_precio" step="0.01" min="0" max="999999.99"
								placeholder="0.00" oninput="formatDecimal(this)" required>
						</div>
					</div>
					<div class="column">
						<div class="control">
							<label>Cantidad</label>
							<input class="input" type="number" name="producto_stock" min="0" max="999999"
								placeholder="0" onkeypress="return soloNumeros(event)" required>
						</div>
					</div>

					<div class="column">
						<div class="control">
							<label>Stock Máximo</label>
							<input class="input" type="number" name="producto_stock_maximo" min="0" max="999999"
								placeholder="0" onkeypress="return soloNumeros(event)" required>
						</div>
					</div>

					<div class="column">
						<div class="control">
							<label>Stock Mínimo</label>
							<input class="input" type="number" name="producto_stock_minimo" min="0" max="999999"
								placeholder="0" onkeypress="return soloNumeros(event)" required>
						</div>
					</div>

					<div class="column">
						<label>Categoría</label><br>
						<div class="select is-rounded">
							<select name="producto_categoria">
								<option value="" selected="">Seleccione una opción</option>
								<?php
								$categorias = conexion();
								$categorias = $categorias->query("SELECT * FROM categoria WHERE categoria_tipo = 'ambas' OR categoria_tipo = 'producto'");
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
								<input class="file-input" type="file" name="producto_foto" accept=".jpg, .png, .jpeg">
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

<script>
	// Función para formatear decimal automáticamente
	function formatDecimal(input) {
		// Remover caracteres no numéricos excepto punto decimal
		let value = input.value.replace(/[^\d.]/g, '');

		// Permitir solo un punto decimal
		const parts = value.split('.');
		if (parts.length > 2) {
			value = parts[0] + '.' + parts.slice(1).join('');
		}

		// Limitar a 2 decimales
		if (parts.length === 2) {
			value = parts[0] + '.' + parts[1].slice(0, 2);
		}

		input.value = value;
	}

	// Función para permitir solo números enteros
	function soloNumeros(event) {
		const charCode = event.keyCode || event.which;
		const charStr = String.fromCharCode(charCode);

		if (!/^\d$/.test(charStr)) {
			event.preventDefault();
			return false;
		}
		return true;
	}

	// Validación adicional para evitar números negativos
	document.addEventListener('DOMContentLoaded', function() {
		const numberInputs = document.querySelectorAll('input[type="number"]');

		numberInputs.forEach(input => {
			input.addEventListener('blur', function() {
				if (this.value < 0) {
					this.value = 0;
				}
			});
		});
	});
</script>

<style>
	label {
		color: #000;
		font-weight: 600;
	}
</style>