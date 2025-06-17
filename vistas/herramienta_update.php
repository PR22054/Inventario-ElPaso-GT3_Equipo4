<div class="container is-fluid mb-6">
	<div class="contenedor-destacado">
		<h1 class="title">Herramientas</h1>
		<h2 class="subtitle">Actualizar herramienta</h2>

		<div class="container pb-6 pt-6">
			<?php
				include "./inc/btn_back.php";

				require_once "./php/main.php";

				$id = (isset($_GET['herramienta_id_up'])) ? $_GET['herramienta_id_up'] : 0;
				$id=limpiar_cadena($id);

				/*== Verificando producto ==*/
				$check_herramienta=conexion();
				$check_herramienta=$check_herramienta->query("SELECT * FROM herramienta WHERE herramienta_id='$id'");

				if($check_herramienta->rowCount()>0){
					$datos=$check_herramienta->fetch();
			?>

			<div class="form-rest mb-6 mt-6"></div>
			
			<h2 class="title has-text-centered"><?php echo $datos['herramienta_nombre']; ?></h2>

			<form action="./php/herramienta_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off" >

				<input type="hidden" name="herramienta_id" value="<?php echo $datos['herramienta_id']; ?>" required >

				<div class="columns">
					<div class="column">
						<div class="control">
							<label>Asignación</label>
							<input class="input" type="text" name="herramienta_codigo" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70" required value="<?php echo $datos['herramienta_codigo']; ?>" >
						</div>
					</div>
					<div class="column">
						<div class="control">
							<label>Nombre herramienta</label>
							<input class="input" type="text" name="herramienta_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" required value="<?php echo $datos['herramienta_nombre']; ?>" >
						</div>
					</div>
				</div>
				<div class="columns">
					<div class="column">
						<div class="control">
							<label>Precio</label>
							<input class="input" type="text" name="herramienta_precio" pattern="[0-9.]{1,25}" maxlength="25" required value="<?php echo $datos['herramienta_precio']; ?>" >
						</div>
					</div>
					<div class="column">
						<div class="control">
							<label>Stock</label>
							<input class="input" type="text" name="herramienta_stock" pattern="[0-9]{1,25}" maxlength="25" required value="<?php echo $datos['herramienta_stock']; ?>" >
						</div>
					</div>
					<div class="column">
						<label>Categoría</label><br>
						<div class="select is-rounded">
							<select name="herramienta_categoria" >
								<?php
									$categorias=conexion();
									$categorias = $categorias->query("SELECT * FROM categoria WHERE categoria_tipo = 'ambas' OR categoria_tipo = 'herramienta'");
									if($categorias->rowCount()>0){
										$categorias=$categorias->fetchAll();
										foreach($categorias as $row){
											if($datos['categoria_id']==$row['categoria_id']){
												echo '<option value="'.$row['categoria_id'].'" selected="" >'.$row['categoria_nombre'].' (Actual)</option>';
											}else{
												echo '<option value="'.$row['categoria_id'].'" >'.$row['categoria_nombre'].'</option>';
											}
										}
									}
									$categorias=null;
								?>
							</select>
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
				$check_herramienta=null;
			?>
		</div>
	</div>
</div>