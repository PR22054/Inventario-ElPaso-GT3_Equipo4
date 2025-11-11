<?php
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";

// Campos para mostrar información completa
$campos = "mantenimiento.mantenimiento_id,
         mantenimiento.mantenimiento_persona,
         mantenimiento.mantenimiento_detalles,
         mantenimiento.mantenimiento_fecha2,
         mantenimiento.herramienta_id,
         mantenimiento.usuario_id,
         usuario.usuario_nombre,
         usuario.usuario_apellido,
         herramienta.herramienta_nombre,
         persona.usuario_nombre as persona_nombre, 
         persona.usuario_apellido as persona_apellido";

if (isset($busqueda) && $busqueda != "") {

    $consulta_datos = "SELECT $campos 
                     FROM mantenimiento 
                     INNER JOIN herramienta ON mantenimiento.herramienta_id=herramienta.herramienta_id 
                     INNER JOIN usuario ON mantenimiento.usuario_id=usuario.usuario_id 
                     LEFT JOIN usuario as persona ON mantenimiento.mantenimiento_persona = persona.usuario_id
                     WHERE mantenimiento.mantenimiento_persona LIKE '%$busqueda%' 
                     OR usuario.usuario_nombre LIKE '%$busqueda%' 
                     OR herramienta.herramienta_nombre LIKE '%$busqueda%'
                     OR persona.usuario_nombre LIKE '%$busqueda%'
                     OR mantenimiento.mantenimiento_detalles LIKE '%$busqueda%'
                     ORDER BY mantenimiento.mantenimiento_persona ASC 
                     LIMIT $inicio,$registros";

    $consulta_total = "SELECT COUNT(mantenimiento_id) 
                     FROM mantenimiento 
                     WHERE mantenimiento_persona LIKE '%$busqueda%' 
                     OR mantenimiento_detalles LIKE '%$busqueda%'";
} elseif ($herramienta_id > 0) {

    $consulta_datos = "SELECT $campos 
                     FROM mantenimiento 
                     INNER JOIN herramienta ON mantenimiento.herramienta_id=herramienta.herramienta_id 
                     INNER JOIN usuario ON mantenimiento.usuario_id=usuario.usuario_id 
                     LEFT JOIN usuario as persona ON mantenimiento.mantenimiento_persona = persona.usuario_id
                     WHERE mantenimiento.herramienta_id='$herramienta_id' 
                     ORDER BY mantenimiento.mantenimiento_persona ASC 
                     LIMIT $inicio,$registros";

    $consulta_total = "SELECT COUNT(mantenimiento_id) 
                     FROM mantenimiento 
                     WHERE herramienta_id='$herramienta_id'";
} else {

    $consulta_datos = "SELECT $campos 
                     FROM mantenimiento 
                     INNER JOIN herramienta ON mantenimiento.herramienta_id=herramienta.herramienta_id 
                     INNER JOIN usuario ON mantenimiento.usuario_id=usuario.usuario_id 
                     LEFT JOIN usuario as persona ON mantenimiento.mantenimiento_persona = persona.usuario_id
                     ORDER BY mantenimiento.mantenimiento_persona ASC 
                     LIMIT $inicio,$registros";

    $consulta_total = "SELECT COUNT(mantenimiento_id) FROM mantenimiento";
}

$conexion = conexion();

$datos = $conexion->query($consulta_datos);
$datos = $datos->fetchAll();

$total = $conexion->query($consulta_total);
$total = (int) $total->fetchColumn();

$Npaginas = ceil($total / $registros);

if ($total >= 1 && $pagina <= $Npaginas) {
    $contador = $inicio + 1;
    $pag_inicio = $inicio + 1;

    foreach ($datos as $rows) {
        // Determinar qué nombre mostrar para la persona
        $nombre_persona = '';
        
        // Si mantenimiento_persona es un ID numérico, usar el nombre de la tabla persona
        if(is_numeric($rows['mantenimiento_persona']) && !empty($rows['persona_nombre'])) {
            $nombre_persona = $rows['persona_nombre'] . ' ' . $rows['persona_apellido'];
        } else {
            // Si es texto, usar directamente el valor
            $nombre_persona = $rows['mantenimiento_persona'];
        }

        // Formatear fecha si existe
        $fecha_formateada = '';
        if(!empty($rows['mantenimiento_fecha2']) && $rows['mantenimiento_fecha2'] != '0000-00-00') {
            $fecha_formateada = date('d/m/Y', strtotime($rows['mantenimiento_fecha2']));
        }

        $tabla .= '
        <article class="media">
            <figure class="media-left">
                <p class="image is-64x64">
                    <!-- Aquí puedes agregar una imagen si lo deseas -->
                </p>
            </figure>
            <div class="media-content">
                <div class="content">
                    <p>
                        <strong>' . $contador . ' - ' . $nombre_persona . '</strong><br>
                        <div class="tags has-addons">
                            <span class="tag is-dark">Herramienta</span>
                            <span class="tag is-link">' . $rows['herramienta_nombre'] . '</span>
                            ' . (!empty($fecha_formateada) ? '<span class="tag is-dark">Fecha Final</span><span class="tag is-info">' . $fecha_formateada . '</span>' : '') . '
                        </div>
                        <div class="field is-grouped is-grouped-multiline" style="margin-top: 0.5rem;">
                            <div class="control">
                                <div class="tags has-addons">
                                    <span class="tag is-dark">Persona del Mantenimiento</span>
                                    <span class="tag is-primary"><strong>' . $nombre_persona . '</strong></span>
                                </div>
                            </div>
                            <div class="control">
                                <div class="tags has-addons">
                                    <span class="tag is-dark">Registrado por</span>
                                    <span class="tag is-success">' . $rows['usuario_nombre'] . ' ' . $rows['usuario_apellido'] . '</span>
                                </div>
                            </div>
                        </div>
                        <div class="box" style="margin-top: 0.5rem; padding: 1rem;">
                            <strong>Detalles:</strong> ' . $rows['mantenimiento_detalles'] . '
                        </div>
                    </p>
                </div>
                <div class="has-text-right">
                    <a href="index.php?vista=mantenimiento_update&mantenimiento_id_up=' . $rows['mantenimiento_id'] . '" class="button is-success is-rounded is-small">
                        <span class="icon is-small"><i class="fas fa-edit"></i></span>
                        <span>Actualizar</span>
                    </a>
                    <a href="' . $url . $pagina . '&mantenimiento_id_del=' . $rows['mantenimiento_id'] . '" class="button is-danger is-rounded is-small">
                        <span class="icon is-small"><i class="fas fa-trash"></i></span>
                        <span>Eliminar</span>
                    </a>
                </div>
            </div>
        </article>
        <hr>
        ';
        $contador++;
    }

    $pag_final = $contador - 1;
} else {
    if ($total >= 1) {
        $tabla .= '
        <p class="has-text-centered">
            <a href="' . $url . '1" class="button is-link is-rounded is-small mt-4 mb-4">
                Haga clic acá para recargar el listado
            </a>
        </p>
        ';
    } else {
        $tabla .= '
        <p class="has-text-centered">No hay registros en el sistema</p>
        ';
    }
}

if ($total > 0 && $pagina <= $Npaginas) {
    $tabla .= '<p class="has-text-right">Mostrando mantenimientos <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
}

$conexion = null;
echo $tabla;

if ($total >= 1 && $pagina <= $Npaginas) {
    echo paginador_tablas($pagina, $Npaginas, $url, 7);
}
?>