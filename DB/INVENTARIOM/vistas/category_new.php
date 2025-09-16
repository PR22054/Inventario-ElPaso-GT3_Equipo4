<div class="container is-fluid mb-6">
	<h1 class="title">Categorías</h1>
	<h2 class="subtitle">Nueva categoría</h2>
</div>

<div class="container pb-6 pt-6">

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/categoria_guardar.php" method="POST" class="FormularioAjax" autocomplete="off">
		<div class="columns">
			<div class="column">
				<div class="control">
					<label>Nombre</label>
					<input class="input" type="text" name="categoria_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}"
						maxlength="50" required>
				</div>
			</div>
			<div class="column">
				<div class="control">
					<label>Descripción</label>
					<input class="input" type="text" name="categoria_ubicacion"
						pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}" maxlength="150">
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
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>

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
let selectedCategory = null; // Variable para guardar la opción seleccionada

function selectCategoryType(element) {
    // 1. Quitar la clase 'selected' de la opción anteriormente seleccionada (si la hay)
    if(selectedCategory) {
        selectedCategory.classList.remove('selected');
    }
    
    // 2. Añadir la clase 'selected' al nuevo elemento que se ha clickeado
    element.classList.add('selected');
    selectedCategory = element;
    
    // 3. Obtener el valor del atributo 'data-value' y actualizar el campo oculto
    const value = element.getAttribute('data-value');
    document.getElementById('tipoCategoria').value = value;
}
</script>