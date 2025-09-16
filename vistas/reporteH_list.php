<div class="container is-fluid mb-6">
    <div class="contenedor-destacado">
            <h1 class="title">Reporte</h1>
            <h2 class="subtitle">Lista de Reportes Productos</h2>
      

        <div class="container pb-6 pt-6">
            <?php
                require_once "./php/main.php";

                # Eliminar producto #
                if(isset($_GET['reporteh_id_del'])){
                    require_once "./php/reporteH_eliminar.php";
                }

                if(!isset($_GET['page'])){
                    $pagina=1;
                }else{
                    $pagina=(int) $_GET['page'];
                    if($pagina<=1){
                        $pagina=1;
                    }
                }

                $herramienta_id = (isset($_GET['herramienta_id'])) ? $_GET['herramienta_id'] : 0;

                $pagina=limpiar_cadena($pagina);
                $url="index.php?vista=reporteH_list&page="; /* <== */
                $registros=15;
                $busqueda="";

                # Paginador producto #
                require_once "./php/reporteH_lista.php";
            ?>
        </div>
    </div>
</div>