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
                            <input class="input" type="text" name="producto_precio" pattern="[0-9.]{1,25}" maxlength="25" required value="<?php echo $datos['producto_precio']; ?>">
                        </div>
                    </div>
                </div>

                <div class="column is-one-third">
                    <div class="field">
                        <label class="label">Stock</label>
                        <div class="control">
                            <input class="input" type="text" name="producto_stock" pattern="[0-9]{1,25}" maxlength="25" required value="<?php echo $datos['producto_stock']; ?>">
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

<style>
.main-container {
    display: flex;
    justify-content: center;
    align-items: flex-start; /* cambia a center si quieres verticalmente centrado */
    min-height: 100vh;
    padding: 20px;
    background-color: #f5f5f5;
}

.contenedor-destacado {
    width: 100%;
    max-width: 900px;
    background-color: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Ajuste de columnas de Bulma dentro del contenedor */
.contenedor-destacado .columns {
    margin-left: 0;
    margin-right: 0;
}

.contenedor-destacado .column {
    padding-left: 10px;
    padding-right: 10px;
}
</style>
