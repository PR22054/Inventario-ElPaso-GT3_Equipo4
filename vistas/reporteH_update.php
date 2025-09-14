<div class="container is-fluid mb-6">
	<h1 class="title">Reporte</h1>
	<h2 class="subtitle">Actualizar Reporte de Productos</h2>
</div>

<div class="container pb-6 pt-6">
	<?php
		include "./inc/btn_back.php";

		require_once "./php/main.php";

		$id = (isset($_GET['reporteh_id_up'])) ? $_GET['reporteh_id_up'] : 0;
		$id=limpiar_cadena($id);

		/*== Verificando producto ==*/
    	$check_reporteh=conexion();
    	$check_reporteh=$check_reporteh->query("SELECT * FROM reporteh WHERE reporteh_id='$id'");

        if($check_reporteh->rowCount()>0){
        	$datos=$check_reporteh->fetch();
	?>

	<div class="form-rest mb-6 mt-6"></div>
	
	<h2 class="title has-text-centered"><?php echo $datos['reporteh_persona']; ?></h2>

	<form action="./php/reporteh_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off" >

		<input type="hidden" name="reporteh_id" value="<?php echo $datos['reporteh_id']; ?>" required >

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
						<label>Herramienta</label><br>
						<div class="select is-rounded">
							<select name="reporteH_producto">
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