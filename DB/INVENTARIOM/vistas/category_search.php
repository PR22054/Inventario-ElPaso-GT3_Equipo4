<?php
    /* =========== INICIO DEL BLOQUE DE PROCESAMIENTO =========== */
    // Este bloque de PHP DEBE ser lo primero en el archivo.
    // Sin espacios, sin texto, sin HTML antes de él.

    require_once "./php/main.php";

    // Si se está enviando el formulario de búsqueda, se procesa aquí.
    if(isset($_POST['modulo_buscador'])){
        // El script buscador.php se encargará de validar,
        // establecer la sesión y redirigir.
        // Como no hay HTML antes, el header() funcionará.
        require_once "./php/buscador.php";
    }

    // Si se va a eliminar una categoría, también se procesa aquí arriba.
    if(isset($_GET['category_id_del'])){
        require_once "./php/categoria_eliminar.php";
    }

    /* =========== FIN DEL BLOQUE DE PROCESAMIENTO =========== */
?><!DOCTYPE html>
<html>
<head>
    <title>Categorías</title>
</head>
<body>

<div class="container is-fluid mb-6">
    <h1 class="title">Categorías</h1>
    <h2 class="subtitle">Buscar categoría</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        // El resto de la lógica que solo MUESTRA información va aquí.

        if(!isset($_SESSION['busqueda_categoria']) && empty($_SESSION['busqueda_categoria'])){
    ?>
    <div class="columns">
        <div class="column">
            <form action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="categoria">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="¿Qué estás buscando?" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" >
                    </p>
                    <p class="control">
                        <button class="button is-info" type="submit" >Buscar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <?php }else{ ?>
    <div class="columns">
        <div class="column">
            <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="categoria">
                <input type="hidden" name="eliminar_buscador" value="categoria">
                <p>Estás buscando <strong>“<?php echo $_SESSION['busqueda_categoria']; ?>”</strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded">Eliminar búsqueda</button>
            </form>
        </div>
    </div>
    <?php
            if(!isset($_GET['page'])){
                $pagina=1;
            }else{
                $pagina=(int) $_GET['page'];
                if($pagina<=1){
                    $pagina=1;
                }
            }

            $pagina=limpiar_cadena($pagina);
            $url="index.php?vista=category_search&page=";
            $registros=15;
            $busqueda=$_SESSION['busqueda_categoria'];

            # Paginador categoria #
            require_once "./php/categoria_lista.php";
        }
    ?>
</div>

</body>
</html>