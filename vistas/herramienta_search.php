<?php
    /* =========== INICIO DEL BLOQUE DE PROCESAMIENTO =========== */
    // Este bloque de PHP DEBE ser lo primero en el archivo.
    require_once "./php/main.php";

    // Procesar filtros si se envían
    if(isset($_POST['aplicar_filtros'])){
        $_SESSION['filtro_ordenar_asignacion'] = limpiar_cadena($_POST['ordenar_asignacion'] ?? '');
        $_SESSION['filtro_ordenar_nombre'] = limpiar_cadena($_POST['ordenar_nombre'] ?? '');
        $_SESSION['filtro_ordenar_precio'] = limpiar_cadena($_POST['ordenar_precio'] ?? '');
        $_SESSION['filtro_stock'] = limpiar_cadena($_POST['filtro_stock'] ?? '');
    }

    // Limpiar filtros
    if(isset($_POST['limpiar_filtros'])){
        unset($_SESSION['filtro_ordenar_asignacion']);
        unset($_SESSION['filtro_ordenar_nombre']);
        unset($_SESSION['filtro_ordenar_precio']);
        unset($_SESSION['filtro_stock']);
    }

    // Si se está enviando el formulario de búsqueda, se procesa aquí.
    if(isset($_POST['modulo_buscador'])){
        require_once "./php/buscador.php";
    }

    // Si se va a eliminar una herramienta, se procesa aquí arriba.
    if(isset($_GET['herramienta_id_del'])){ 
        require_once "./php/herramienta_eliminar.php";
    }

    /* =========== FIN DEL BLOQUE DE PROCESAMIENTO =========== */
?><!DOCTYPE html>
<html>
<head>
    <title>Herramientas</title>
    <style>
        .filtros-container {
            background-color: #f5f5f5;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .filtros-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .filtro-grupo label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #363636;
        }
        .filtro-grupo select {
            width: 100%;
        }
        .botones-filtros {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }
    </style>
</head>
<body>

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
        <h1 class="title">Herramientas</h1>
        <h2 class="subtitle">Buscar herramienta</h2>

        <div class="container pb-6 pt-6">
            <?php
                if(!isset($_SESSION['busqueda_herramienta']) && empty($_SESSION['busqueda_herramienta'])){
            ?>
            <div class="columns">
                <div class="column">
                    <form action="" method="POST" autocomplete="off" >
                        <input type="hidden" name="modulo_buscador" value="herramienta">
                        <div class="field is-grouped">
                            <p class="control is-expanded" style="position: relative;">
                                <input class="input is-rounded" type="text" name="txt_buscador" 
                                       id="input_buscador_herramienta"
                                       placeholder="¿Qué estás buscando?" 
                                       pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ \-']{1,50}" maxlength="50" >
                                
                                <div id="resultados_autocompletado" class="dropdown-content" 
                                     style="position: absolute; z-index: 1000; width: 100%; max-height: 200px; overflow-y: auto; background: white; border: 1px solid #dbdbdb; box-shadow: 0 4px 8px rgba(0,0,0,0.1); display: none;">
                                </div>
                            </p>
                            <p class="control">
                                <button class="button is-info" type="submit" >Buscar</button>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            
            <script>
                const inputBuscador = document.getElementById('input_buscador_herramienta');
                const resultadosContenedor = document.getElementById('resultados_autocompletado');
                let timeout = null;
                const MIN_CARACTERES = 3; 

                inputBuscador.addEventListener('input', function() {
                    const termino = this.value.trim();
                    clearTimeout(timeout);
                    
                    if (termino.length < MIN_CARACTERES) {
                        resultadosContenedor.innerHTML = '';
                        resultadosContenedor.style.display = 'none';
                        return;
                    }

                    timeout = setTimeout(() => {
                        fetch('./php/buscador_autocomplete.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'termino=' + encodeURIComponent(termino) + '&modulo=herramienta'
                        })
                        .then(response => response.json())
                        .then(data => {
                            resultadosContenedor.innerHTML = '';
                            
                            if (Array.isArray(data) && data.length > 0) {
                                data.forEach(item => {
                                    const link = document.createElement('a');
                                    link.href = '#'; 
                                    link.classList.add('dropdown-item');
                                    link.style.display = 'block';
                                    link.style.padding = '8px 10px';
                                    link.style.textDecoration = 'none';
                                    link.style.color = '#363636';
                                    link.textContent = item;

                                    link.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        inputBuscador.value = item;
                                        resultadosContenedor.style.display = 'none';
                                        inputBuscador.closest('form').submit(); 
                                    });

                                    resultadosContenedor.appendChild(link);
                                });
                                resultadosContenedor.style.display = 'block';
                            } else {
                                resultadosContenedor.style.display = 'none';
                            }
                        })
                        .catch(error => {
                            console.error('Error en la búsqueda de autocompletado:', error);
                            resultadosContenedor.style.display = 'none';
                        });
                    }, 300); 
                });

                document.addEventListener('click', function(e) {
                    if (e.target !== inputBuscador && e.target.closest('#resultados_autocompletado') === null) {
                        resultadosContenedor.style.display = 'none';
                    }
                });
            </script>

            <!-- SISTEMA DE FILTROS -->
            <div class="filtros-container">
                <h3 class="subtitle is-5" style="margin-bottom: 1rem;">
                    <span class="icon-text">
                        <span class="icon">
                            <i class="fas fa-filter"></i>
                        </span>
                        <span>Filtros de búsqueda</span>
                    </span>
                </h3>
                
                <form action="" method="POST">
                    <div class="filtros-grid">
                        <!-- Filtro: Ordenar por Asignación -->
                        <div class="filtro-grupo">
                            <label>Ordenar por Asignación</label>
                            <div class="select is-fullwidth">
                                <select name="ordenar_asignacion">
                                    <option value="">Sin ordenar</option>
                                    <option value="ASC" <?php echo (isset($_SESSION['filtro_ordenar_asignacion']) && $_SESSION['filtro_ordenar_asignacion']=='ASC') ? 'selected' : ''; ?>>A-Z (0-9)</option>
                                    <option value="DESC" <?php echo (isset($_SESSION['filtro_ordenar_asignacion']) && $_SESSION['filtro_ordenar_asignacion']=='DESC') ? 'selected' : ''; ?>>Z-A (9-0)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filtro: Ordenar por Nombre -->
                        <div class="filtro-grupo">
                            <label>Ordenar por Nombre</label>
                            <div class="select is-fullwidth">
                                <select name="ordenar_nombre">
                                    <option value="">Sin ordenar</option>
                                    <option value="ASC" <?php echo (isset($_SESSION['filtro_ordenar_nombre']) && $_SESSION['filtro_ordenar_nombre']=='ASC') ? 'selected' : ''; ?>>A-Z</option>
                                    <option value="DESC" <?php echo (isset($_SESSION['filtro_ordenar_nombre']) && $_SESSION['filtro_ordenar_nombre']=='DESC') ? 'selected' : ''; ?>>Z-A</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filtro: Ordenar por Precio -->
                        <div class="filtro-grupo">
                            <label>Ordenar por Precio</label>
                            <div class="select is-fullwidth">
                                <select name="ordenar_precio">
                                    <option value="">Sin ordenar</option>
                                    <option value="DESC" <?php echo (isset($_SESSION['filtro_ordenar_precio']) && $_SESSION['filtro_ordenar_precio']=='DESC') ? 'selected' : ''; ?>>Mayor precio</option>
                                    <option value="ASC" <?php echo (isset($_SESSION['filtro_ordenar_precio']) && $_SESSION['filtro_ordenar_precio']=='ASC') ? 'selected' : ''; ?>>Menor precio</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filtro: Stock -->
                        <div class="filtro-grupo">
                            <label>Filtrar por Stock</label>
                            <div class="select is-fullwidth">
                                <select name="filtro_stock">
                                    <option value="">Todos</option>
                                    <option value="sin_stock" <?php echo (isset($_SESSION['filtro_stock']) && $_SESSION['filtro_stock']=='sin_stock') ? 'selected' : ''; ?>>Sin stock (0)</option>
                                    <option value="bajo" <?php echo (isset($_SESSION['filtro_stock']) && $_SESSION['filtro_stock']=='bajo') ? 'selected' : ''; ?>>Stock bajo (menos de 5)</option>
                                    <option value="normal" <?php echo (isset($_SESSION['filtro_stock']) && $_SESSION['filtro_stock']=='normal') ? 'selected' : ''; ?>>Stock normal (5 o más)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="botones-filtros">
                        <button type="submit" name="limpiar_filtros" class="button is-light">
                            <span class="icon">
                                <i class="fas fa-eraser"></i>
                            </span>
                            <span>Limpiar</span>
                        </button>
                        <button type="submit" name="aplicar_filtros" class="button is-info">
                            <span class="icon">
                                <i class="fas fa-check"></i>
                            </span>
                            <span>Aplicar filtros</span>
                        </button>
                    </div>
                </form>
            </div>

            <?php }else{ ?>
            <div class="columns">
                <div class="column">
                    <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off" >
                        <input type="hidden" name="modulo_buscador" value="herramienta">
                        <input type="hidden" name="eliminar_buscador" value="herramienta">
                        <p>Estás buscando <strong>"<?php echo $_SESSION['busqueda_herramienta']; ?>"</strong></p>
                        <br>
                        <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
                    </form>
                </div>
            </div>
            <?php
                }
                
                // Paginación y listado
                if(!isset($_GET['page'])){
                    $pagina=1;
                }else{
                    $pagina=(int) $_GET['page'];
                    if($pagina<=1){
                        $pagina=1;
                    }
                }

                $pagina=limpiar_cadena($pagina);
                $url="index.php?vista=herramienta_search&page=";
                $registros=15;
                $busqueda = isset($_SESSION['busqueda_herramienta']) ? $_SESSION['busqueda_herramienta'] : "";

                # Paginador herramienta #
                require_once "./php/herramienta_lista.php";
            ?>
        </div>
    </div>
</div>
</body>
</html>