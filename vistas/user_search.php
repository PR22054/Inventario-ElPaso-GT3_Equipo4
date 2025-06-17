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

    // Si se va a eliminar un usuario, también se procesa aquí arriba.
    if(isset($_GET['user_id_del'])){
        require_once "./php/usuario_eliminar.php";
    }

    /* =========== FIN DEL BLOQUE DE PROCESAMIENTO =========== */
?><!DOCTYPE html>
<html>
<head>
    </head>
<body>

<div class="container is-fluid mb-6">
    <div class="contenedor-destacado">
        <h1 class="title">Usuarios</h1>
        <h2 class="subtitle">Buscar usuario</h2>

        <div class="container pb-6 pt-6">
            <?php
                // El resto de la lógica que solo MUESTRA información va aquí.

                if(!isset($_SESSION['busqueda_usuario']) && empty($_SESSION['busqueda_usuario'])){
            ?>
                <div class="columns">
                    <div class="column">
                        <form action="" method="POST" autocomplete="off" >
                            <input type="hidden" name="modulo_buscador" value="usuario">   
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
            <?php 
                } else { 
            ?>
                <div class="columns">
                    <div class="column">
                        <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off" >
                            <input type="hidden" name="modulo_buscador" value="usuario"> 
                            <input type="hidden" name="eliminar_buscador" value="usuario">
                            <p>Estas buscando <strong>“<?php echo $_SESSION['busqueda_usuario']; ?>”</strong></p>
                            <br>
                            <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
                        </form>
                    </div>
                </div>
            <?php
                    // Lógica para mostrar la lista de usuarios
                    if(!isset($_GET['page'])){
                        $pagina=1;
                    }else{
                        $pagina=(int) $_GET['page'];
                        if($pagina<=1){
                            $pagina=1;
                        }
                    }

                    $pagina=limpiar_cadena($pagina);
                    $url="index.php?vista=user_search&page=";
                    $registros=15;
                    $busqueda=$_SESSION['busqueda_usuario'];

                    require_once "./php/usuario_lista.php";
                } 
            ?>
        </div>
    </div>
</div>

</body>
</html>