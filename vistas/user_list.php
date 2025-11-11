<div class="container is-fluid mb-6">
    <div class="contenedor-destacado">
        <div style="text-align: right; margin-bottom: 1rem;">
            <button onclick="history.back()" 
                style="background-color: black; border: none; border-radius: 4px; padding: 0.4rem; cursor: pointer;">
                <span class="icon" style="color: white; font-size: 1rem;">
                    <i class="fas fa-arrow-left"></i>
                </span>
            </button>
        </div>
        <h1 class="title">Usuarios</h1>
        <h2 class="subtitle">Lista de usuarios</h2>

        <div class="container pb-6 pt-6">  
            <?php
                require_once "./php/main.php";

                # Eliminar usuario #
                if(isset($_GET['user_id_del'])){
                    require_once "./php/usuario_eliminar.php";
                }

                if(!isset($_GET['page'])){
                    $pagina=1;
                }else{
                    $pagina=(int) $_GET['page'];
                    if($pagina<=1){
                        $pagina=1;
                    }
                }

                $pagina=limpiar_cadena($pagina);
                $url="index.php?vista=user_list&page=";
                $registros=15;
                $busqueda="";

                # Paginador usuario #
                require_once "./php/usuario_lista.php";
            ?>
        </div>
    </div>
</div>