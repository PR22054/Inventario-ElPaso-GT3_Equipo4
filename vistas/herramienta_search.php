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

    // Si se va a eliminar una herramienta, también se procesa aquí arriba.
    if(isset($_GET['tool_id_del'])){ // Cambiado de product_id_del a tool_id_del para ser consistente con "herramienta"
        require_once "./php/herramienta_eliminar.php";
    }

    /* =========== FIN DEL BLOQUE DE PROCESAMIENTO =========== */
?><!DOCTYPE html>
<html>
<head>
    <title>Herramientas</title>
</head>
<body>

<div class="container is-fluid mb-6">
    <div class="contenedor-destacado">
        <h1 class="title">Herramientas</h1>
        <h2 class="subtitle">Buscar herramienta</h2>

        <div class="container pb-6 pt-6">
            <?php
                // El resto de la lógica que solo MUESTRA información va aquí.

                if(!isset($_SESSION['busqueda_herramienta']) && empty($_SESSION['busqueda_herramienta'])){
            ?>
            <div class="columns">
                <div class="column">
                    <form action="" method="POST" autocomplete="off" >
                        <input type="hidden" name="modulo_buscador" value="herramienta">
                        <div class="field is-grouped">
                            <p class="control is-expanded">
                                <input class="input is-rounded" type="text" name="txt_buscador" placeholder="¿Qué estas buscando?" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" >
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
                        <input type="hidden" name="modulo_buscador" value="herramienta">
                        <input type="hidden" name="eliminar_buscador" value="herramienta">
                        <p>Estas buscando <strong>“<?php echo $_SESSION['busqueda_herramienta']; ?>”</strong></p>
                        <br>
                        <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
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

                    // Si tienes una categoría para herramientas, podrías usarla aquí.
                    // $categoria_id = (isset($_GET['category_id'])) ? $_GET['category_id'] : 0;

                    $pagina=limpiar_cadena($pagina);
                    $url="index.php?vista=tool_search&page="; // Cambiado a tool_search
                    $registros=15;
                    $busqueda=$_SESSION['busqueda_herramienta'];

                    # Paginador herramienta #
                    require_once "./php/herramienta_lista.php";
                }
            ?>
        </div>
    </div>
</div>
</body>
</html>