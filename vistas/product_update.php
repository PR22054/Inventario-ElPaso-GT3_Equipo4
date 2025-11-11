<div class="main-container">
    <div class="contenedor-destacado">
        <div style="text-align: right; margin-bottom: 1rem;">
            <button onclick="history.back()" 
                style="background-color: black; border: none; border-radius: 4px; padding: 0.4rem; cursor: pointer;">
                <span class="icon" style="color: white; font-size: 1rem;">
                    <i class="fas fa-arrow-left"></i>
                </span>
            </button>
        </div>
        <h1 class="title has-text-centered">Productos</h1>
        <h2 class="subtitle has-text-centered">Actualizar producto</h2>

        <?php
        include "./inc/btn_back.php";
        require_once "./php/main.php";

        $id = (isset($_GET['product_id_up'])) ? $_GET['product_id_up'] : 0;
        $id = limpiar_cadena($id);

        $check_producto = conexion();
        $check_producto = $check_producto->query("SELECT * FROM producto WHERE producto_id='$id'");

        if ($check_producto->rowCount() > 0) {
            $datos = $check_producto->fetch();
        ?>

        <h2 class="title has-text-centered"><?php echo $datos['producto_nombre']; ?></h2>

        <form action="./php/producto_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off">
            <input type="hidden" name="producto_id" value="<?php echo $datos['producto_id']; ?>" required>

            <div class="columns is-multiline">
                <div class="column is-half">
                    <div class="field">
                        <label class="label">Asignación</label>
                        <div class="control">
                            <input class="input" type="text" name="producto_codigo" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70" required value="<?php echo $datos['producto_codigo']; ?>">
                        </div>
                    </div>
                </div>

                <div class="column is-half">
                    <div class="field">
                        <label class="label">Nombre artículo</label>
                        <div class="control">
                            <input class="input" type="text" name="producto_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" required value="<?php echo $datos['producto_nombre']; ?>">
                        </div>
                    </div>
                </div>

                <div class="column is-one-third">
                    <div class="field">
                        <label class="label">Precio</label>
                        <div class="control">
                            <input class="input" type="number" name="producto_precio" step="0.01" min="0" max="999999.99" 
                                placeholder="0.00" oninput="formatDecimal(this)" required 
                                value="<?php echo number_format($datos['producto_precio'], 2, '.', ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="column is-one-third">
                    <div class="field">
                        <label class="label">Stock</label>
                        <div class="control">
                            <input class="input" type="number" name="producto_stock" min="0" max="999999" 
                                placeholder="0" onkeypress="return soloNumeros(event)" required 
                                value="<?php echo intval($datos['producto_stock']); ?>">
                        </div>
                    </div>
                </div>

                <div class="column is-one-third">
                    <div class="field">
                        <label class="label">Categoría</label>
                        <div class="control">
                            <div class="select is-fullwidth is-rounded">
                                <select name="producto_categoria">
                                    <?php
                                    $categorias = conexion();
                                    $categorias = $categorias->query("SELECT * FROM categoria WHERE categoria_tipo = 'ambas' OR categoria_tipo = 'producto'");
                                    if ($categorias->rowCount() > 0) {
                                        $categorias = $categorias->fetchAll();
                                        foreach ($categorias as $row) {
                                            if ($datos['categoria_id'] == $row['categoria_id']) {
                                                echo '<option value="' . $row['categoria_id'] . '" selected>' . $row['categoria_nombre'] . ' (Actual)</option>';
                                            } else {
                                                echo '<option value="' . $row['categoria_id'] . '">' . $row['categoria_nombre'] . '</option>';
                                            }
                                        }
                                    }
                                    $categorias = null;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-grouped is-grouped-centered mt-4">
                <div class="control">
                    <button type="submit" class="button is-success is-rounded">Actualizar</button>
                </div>
            </div>
        </form>

        <?php
        } else {
            include "./inc/error_alert.php";
        }
        $check_producto = null;
        ?>

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
/* Diseños varios */ 
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh; 
}

.main-container {
    width: 100%;
    max-width: 1100px;
    margin: 40px auto; 
    background-color: #fff;
    padding: 50px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.contenedor-destacado .columns {
    margin-left: 0;
    margin-right: 0;
}

.contenedor-destacado .column {
    padding-left: 10px;
    padding-right: 10px;
}

.main-container > div:first-child {
    text-align: right;
    margin-bottom: 1rem;
}


footer {
    margin-top: auto; 
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 1rem 0;
}

</style>
