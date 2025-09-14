<div class="container is-fluid mb-6">
	<h1 class="title">Categorías</h1>
	<h2 class="subtitle">Actualizar categoría</h2>
</div>

<div class="container pb-6 pt-6">
	<?php
		include "./inc/btn_back.php";

		require_once "./php/main.php";

		$id = (isset($_GET['category_id_up'])) ? $_GET['category_id_up'] : 0;
		$id=limpiar_cadena($id);

		/*== Verificando categoria ==*/
    	$check_categoria=conexion();
    	$check_categoria=$check_categoria->query("SELECT * FROM categoria WHERE categoria_id='$id'");

        if($check_categoria->rowCount()>0){
        	$datos=$check_categoria->fetch();
	?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/categoria_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off" >

		<input type="hidden" name="categoria_id" value="<?php echo $datos['categoria_id']; ?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="categoria_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}" maxlength="50" required value="<?php echo $datos['categoria_nombre']; ?>" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Descripción</label>
				  	<input class="input" type="text" name="categoria_ubicacion" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}" maxlength="150" value="<?php echo $datos['categoria_ubicacion']; ?>" >
				</div>
		  	</div>
			
		</div>
		<div class="columns">
			<div class="column">
        		<div class="control">
            	<label>Tipo de Categoria</label>
            		<div class="category-type-selector">
                		<div class="category-option" data-value="producto" onclick="selectCategoryType(this)">
                    		<span class="icon">🔩</span>
                    		<span>Productos</span>
                		</div>
						<div class="category-option" data-value="herramienta" onclick="selectCategoryType(this)">
							<span class="icon">🔧</span>
							<span>Herramientas</span>
						</div>  
						<div class="category-option" data-value="ambas" onclick="selectCategoryType(this)">
							<span class="icon">🔩/🔧</span>
							<span>Herramientas y Productos</span>
						</div>               
            		</div>            
					<input type="hidden" name="categoria_tipo" id="tipoCategoria" required>
        		</div>
			</div>
		</div>


		<p class="has-text-centered">
			<button type="submit" class="button is-success is-rounded">Actualizar</button>
		</p>
		</div>
	</form>
	<?php 
		}else{
			include "./inc/error_alert.php";
		}
		$check_categoria=null;
	?>
</div>

<!-- Estilos de varios -->
<style>
.category-type-selector {
    display: flex;
    gap: 10px;
    margin-top: 8px;
}

.category-option {
    flex: 1;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    background: #f8f9fa;
}

.category-option:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.category-option.selected {
    border-color: #2196F3;
    background: #e3f2fd;
    box-shadow: 0 2px 8px rgba(33,150,243,0.1);
}

.category-option .icon {
    display: block;
    font-size: 1.5em;
    margin-bottom: 5px;
}
</style>

<script>
// Función faltante para manejar la selección
let selectedCategory = null;

function selectCategoryType(element) {
    // Quitar selección anterior
    if(selectedCategory) {
        selectedCategory.classList.remove('selected');
    }
    
    // Marcar nueva selección
    element.classList.add('selected');
    selectedCategory = element;
    
    // Actualizar campo oculto
    const value = element.getAttribute('data-value');
    document.getElementById('tipoCategoria').value = value;
}
</script>