<div class="container is-fluid mb-6">
    <div class="contenedor-destacado">
	    <h1 class="title">Categorías</h1>
	    <h2 class="subtitle">Nueva categoría</h2>

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
                            <span class="icon">⚙️</span>
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
    </div>
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
    font-weight: 600;

    display: flex;
    flex-direction: column;
    align-items: center;
}
   

.category-option:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(33,150,243,0.15);
}

.category-option.selected {
    color: #0d47a1;
    border-color: #2196F3;
    background: #e3f2fd;
    box-shadow: 0 2px 8px rgba(33,150,243,0.1);
}
.category-option .icon {
    display: flex;
    font-size: 1.5em;
    margin-bottom: 6px;
    line-height: 1;
}
label {
    color: #212121; 
    font-weight: 600; 
}
.input {
    border: 2px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.input:focus {
    border-color: #2196F3;
    box-shadow: 0 0 8px rgba(33, 150, 243, 0.2);
}
</style>
