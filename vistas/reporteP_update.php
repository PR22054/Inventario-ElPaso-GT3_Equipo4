<div class="container is-fluid mb-6">
	<h1 class="title">Reporte</h1>
	<h2 class="subtitle">Actualizar Reporte de Productos</h2>
</div>

<div class="container pb-6 pt-6">
	<?php
		include "./inc/btn_back.php";

		require_once "./php/main.php";

		$id = (isset($_GET['reportep_id_up'])) ? $_GET['reportep_id_up'] : 0;
		$id=limpiar_cadena($id);

		/*== Verificando producto ==*/
    	$check_reportep=conexion();
    	$check_reportep=$check_reportep->query("SELECT * FROM reportep WHERE reportep_id='$id'");

        if($check_reportep->rowCount()>0){
        	$datos=$check_reportep->fetch();
	?>

	<div class="form-rest mb-6 mt-6"></div>
	
	<h2 class="title has-text-centered"><?php echo $datos['reportep_persona']; ?></h2>

	<form action="./php/reportep_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off" >

		<input type="hidden" name="reportep_id" value="<?php echo $datos['reportep_id']; ?>" required >

		<div class="columns">
					<div class="column">
						<label>Usuario</label><br>
						<div class="select is-rounded">
							<select name="reporteP_persona">
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
							<select name="reporteP_producto">
								<option value="" selected="">Seleccione una opción</option>
								<?php
								$categorias = conexion();
								$categorias = $categorias->query("SELECT * FROM producto");
								if ($categorias->rowCount() > 0) {
									$categorias = $categorias->fetchAll();
									foreach ($categorias as $row) {
										echo '<option value="' . $row['producto_id'] . '" >' . $row['producto_nombre'] . '</option>';
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
							<input class="input" type="text" name="reporteP_detalles"
								pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,150}" maxlength="70" required>
						</div>
					</div>
					<div class="column">
						<div class="control">
							<label>Cantidad</label>
							<input class="input" type="text" name="reporteP_cantidad" pattern="[0-9]{1,25}" maxlength="25"
								required>
						</div>
					</div>
					 <div class="column">
                    <div class="select is-rounded">
                        <label for="reporteP_tipo">Tipo de Incidencia</label>
                        <select class="select" id="reporteP_tipo" name="reporteP_tipo" required>
                            <option value="">Seleccione una opción</option>
                            <option value="robo">Robo</option>
                            <option value="rota">Rota</option>
                        </select>
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
		$check_reportep=null;
	?>
</div>