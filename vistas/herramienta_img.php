<div class="container is-fluid mb-6">
	<div style="text-align: right; margin-bottom: 1rem;">
            <button onclick="history.back()" 
                style="background-color: black; border: none; border-radius: 4px; padding: 0.4rem; cursor: pointer;">
                <span class="icon" style="color: white; font-size: 1rem;">
                    <i class="fas fa-arrow-left"></i>
                </span>
            </button>
        </div>
	<h1 class="title">herramientas</h1>
	<h2 class="subtitle">Actualizar imagen de herramienta</h2>
</div>

<div class="container pb-6 pt-6">
	<?php
		include "./inc/btn_back.php";

		require_once "./php/main.php";

		$id = (isset($_GET['herramienta_id_up'])) ? $_GET['herramienta_id_up'] : 0;

		/*== Verificando producto ==*/
    	$check_herramienta=conexion();
    	$check_herramienta=$check_herramienta->query("SELECT * FROM herramienta WHERE herramienta_id='$id'");

        if($check_herramienta->rowCount()>0){
        	$datos=$check_herramienta->fetch();
	?>

	<div class="form-rest mb-6 mt-6"></div>

	<div class="columns">
		<div class="column is-two-fifths">
			<?php if(is_file("./img/herramienta/".$datos['herramienta_foto'])){ ?>
			<figure class="image mb-6">
			  	<img src="./img/herramienta/<?php echo $datos['herramienta_foto']; ?>">
			</figure>
			<form class="FormularioAjax" action="./php/herramienta_img_eliminar.php" method="POST" autocomplete="off" >

				<input type="hidden" name="img_del_id" value="<?php echo $datos['herramienta_id']; ?>">

				<p class="has-text-centered">
					<button type="submit" class="button is-danger is-rounded">Eliminar imagen</button>
				</p>
			</form>
			<?php }else{ ?>
			<figure class="image mb-6">
			  	<img src="./img/producto.png">
			</figure>
			<?php } ?>
		</div>
		<div class="column">
			<form class="mb-6 has-text-centered FormularioAjax" action="./php/herramienta_img_actualizar.php" method="POST" enctype="multipart/form-data" autocomplete="off" >

				<h4 class="title is-4 mb-6"><?php echo $datos['herramienta_nombre']; ?></h4>
				
				<label>Foto o imagen del herramienta</label><br>

				<input type="hidden" name="img_up_id" value="<?php echo $datos['herramienta_id']; ?>">

				<div class="file has-name is-horizontal is-justify-content-center mb-6">
				  	<label class="file-label">
				    	<input class="file-input" type="file" name="herramienta_foto" accept=".jpg, .png, .jpeg" >
				    	<span class="file-cta">
				      		<span class="file-label">Imagen</span>
				    	</span>
				    	<span class="file-name">JPG, JPEG, PNG. (MAX 3MB)</span>
				  	</label>
				</div>
				<p class="has-text-centered">
					<button type="submit" class="button is-success is-rounded">Actualizar</button>
				</p>
			</form>
		</div>
	</div>
	<?php 
		}else{
			include "./inc/error_alert.php";
		}
		$check_herramienta=null;
	?>
</div>