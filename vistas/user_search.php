<?php
    /* =========== INICIO DEL BLOQUE DE PROCESAMIENTO =========== */
    // Este bloque de PHP DEBE ser lo primero en el archivo.
    require_once "./php/main.php";

    // Si se está enviando el formulario de búsqueda, se procesa aquí.
    if(isset($_POST['modulo_buscador'])){
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
    <title>Buscar Usuario</title>
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
        <h1 class="title">Usuarios</h1>
        <h2 class="subtitle">Buscar usuario</h2>

        <div class="container pb-6 pt-6">
            <?php
                if(!isset($_SESSION['busqueda_usuario']) && empty($_SESSION['busqueda_usuario'])){
            ?>
                <div class="columns">
                    <div class="column">
                        <form action="" method="POST" autocomplete="off" >
                            <input type="hidden" name="modulo_buscador" value="usuario">    
                            <div class="field is-grouped">
                                <p class="control is-expanded" style="position: relative;">
                                    <input class="input is-rounded" type="text" name="txt_buscador" 
                                           id="input_buscador_usuario"
                                           placeholder="¿Qué estás buscando?" 
                                           pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ \-']{1,50}" maxlength="50" >
                                    
                                    <div id="resultados_autocompletado_usuario" class="dropdown-content" 
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
                    const inputBuscador = document.getElementById('input_buscador_usuario');
                    const resultadosContenedor = document.getElementById('resultados_autocompletado_usuario');
                    let timeout = null;
                    const MIN_CARACTERES = 3; 

                    // Evento que se dispara cada vez que el contenido del input cambia
                    inputBuscador.addEventListener('input', function() {
                        const termino = this.value.trim();

                        clearTimeout(timeout);
                        
                        if (termino.length < MIN_CARACTERES) {
                            resultadosContenedor.innerHTML = '';
                            resultadosContenedor.style.display = 'none';
                            return;
                        }

                        // Temporizador para evitar peticiones excesivas (300ms)
                        timeout = setTimeout(() => {
                            // La ruta a tu script de backend para la búsqueda asíncrona
                            fetch('./php/buscador_autocomplete.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                // Enviar el término y el módulo para que el backend sepa dónde buscar
                                body: 'termino=' + encodeURIComponent(termino) + '&modulo=usuario'
                            })
                            .then(response => response.json())
                            .then(data => {
                                resultadosContenedor.innerHTML = ''; // Limpiar resultados
                                
                                if (Array.isArray(data) && data.length > 0) {
                                    data.forEach(item => {
                                        const link = document.createElement('a');
                                        
                                        // Estilo del elemento desplegable
                                        link.href = '#'; 
                                        link.classList.add('dropdown-item');
                                        link.style.display = 'block';
                                        link.style.padding = '8px 10px';
                                        link.style.textDecoration = 'none';
                                        link.style.color = '#363636';
                                        link.textContent = item;

                                        // Al hacer clic, se rellena el input y se envía el formulario
                                        link.addEventListener('click', function(e) {
                                            e.preventDefault();
                                            inputBuscador.value = item;
                                            resultadosContenedor.style.display = 'none';
                                            inputBuscador.closest('form').submit(); 
                                        });

                                        resultadosContenedor.appendChild(link);
                                    });
                                    resultadosContenedor.style.display = 'block'; // Mostrar la barra desplegable
                                } else {
                                    resultadosContenedor.style.display = 'none'; // Ocultar si no hay resultados
                                }
                            })
                            .catch(error => {
                                console.error('Error en la búsqueda de autocompletado:', error);
                                resultadosContenedor.style.display = 'none';
                            });
                        }, 300); 
                    });

                    // Ocultar la barra cuando el usuario hace clic fuera
                    document.addEventListener('click', function(e) {
                        if (e.target !== inputBuscador && e.target.closest('#resultados_autocompletado_usuario') === null) {
                            resultadosContenedor.style.display = 'none';
                        }
                    });
                </script>
            <?php 
                } else { 
            ?>
                <div class="columns">
                    <div class="column">
                        <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off" >
                            <input type="hidden" name="modulo_buscador" value="usuario">    
                            <input type="hidden" name="eliminar_buscador" value="usuario">
                            <p>Estás buscando <strong>“<?php echo $_SESSION['busqueda_usuario']; ?>”</strong></p>
                            <br>
                            <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
                        </form>
                    </div>
                </div>
            <?php
                    // Lógica para mostrar la lista de usuarios paginada
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