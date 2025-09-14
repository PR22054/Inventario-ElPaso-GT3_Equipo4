<?php
	$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;
	$tabla="";

	$campos="mantenimiento.mantenimiento_id,mantenimiento.mantenimiento_persona,mantenimiento.mantenimiento_detalles,mantenimiento_fecha2,mantenimiento.herramienta_id,mantenimiento.usuario_id,usuario.usuario_nombre,usuario.usuario_apellido,herramienta_nombre";

	if(isset($busqueda) && $busqueda!=""){

		$consulta_datos="SELECT $campos FROM mantenimiento INNER JOIN herramienta ON mantenimiento.herramienta_id=herramienta.herramienta_id INNER JOIN usuario ON mantenimiento.usuario_id=usuario.usuario_id WHERE mantenimiento.usuario_id LIKE '%$busqueda%' OR mantenimiento.mantenimiento_persona LIKE '%$busqueda%' ORDER BY mantenimiento.mantenimiento_persona ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(mantenimiento_id) FROM mantenimiento WHERE mantenimiento_persona LIKE '%$busqueda%' OR mantenimiento_persona LIKE '%$busqueda%'";

	}elseif($herramienta_id>0){

		$consulta_datos="SELECT $campos FROM mantenimiento INNER JOIN herramienta ON mantenimiento.herramienta_id=herramienta.herramienta_id INNER JOIN usuario ON mantenimiento.usuario_id=usuario.usuario_id WHERE mantenimiento.herramienta_id='$herramienta_id' ORDER BY mantenimiento.mantenimiento_persona ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(mantenimiento_id) FROM mantenimiento WHERE herramienta_id='$herramienta_id'";

	}else{

		$consulta_datos="SELECT $campos FROM mantenimiento INNER JOIN herramienta ON mantenimiento.herramienta_id=herramienta.herramienta_id INNER JOIN usuario ON mantenimiento.usuario_id=usuario.usuario_id ORDER BY mantenimiento.mantenimiento_persona ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(mantenimiento_id) FROM mantenimiento";

	}

	$conexion=conexion();

	$datos = $conexion->query($consulta_datos);
	$datos = $datos->fetchAll();

	$total = $conexion->query($consulta_total);
	$total = (int) $total->fetchColumn();

	$Npaginas =ceil($total/$registros);

	if($total>=1 && $pagina<=$Npaginas){
		$contador=$inicio+1;
		$pag_inicio=$inicio+1;
		foreach($datos as $rows){
			$tabla.='
				<article class="media">
			        <figure class="media-left">';
			           
			   $tabla.='</p>
			        </figure>
			        <div class="media-content">
			            <div class="content">
			              <p>
			                <strong>'.$contador.' - '.$rows['mantenimiento_persona'].'</strong><br>
			                <strong>Herramienta:</strong> '.$rows['herramienta_nombre'].',<strong>Fecha Final:</strong> '.$rows['mantenimiento_fecha2'].',<strong>Persona:</strong> '.$rows['usuario_nombre'].', <strong>Detalles:</strong> '.$rows['mantenimiento_detalles'].', <strong>REGISTRADO POR:</strong> '.$rows['usuario_nombre'].' '.$rows['usuario_apellido'].'
			              </p>
			            </div>
			            <div class="has-text-right">			                
			                <a href="index.php?vista=mantenimiento_update&mantenimiento_id_up='.$rows['mantenimiento_id'].'" class="button is-success is-rounded is-small">Actualizar</a>
			                <a href="'.$url.$pagina.'&mantenimiento_id_del='.$rows['mantenimiento_id'].'" class="button is-danger is-rounded is-small">Eliminar</a>
			            </div>
			        </div>
			    </article>

			    <hr>
            ';
            $contador++;
		}
		$pag_final=$contador-1;
	}else{
		if($total>=1){
			$tabla.='
				<p class="has-text-centered" >
					<a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
						Haga clic acá para recargar el listado
					</a>
				</p>
			';
		}else{
			$tabla.='
				<p class="has-text-centered" >No hay registros en el sistema</p>
			';
		}
	}

	if($total>0 && $pagina<=$Npaginas){
		$tabla.='<p class="has-text-right">Mostrando mantenimientos <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
	}

	$conexion=null;
	echo $tabla;

	if($total>=1 && $pagina<=$Npaginas){
		echo paginador_tablas($pagina,$Npaginas,$url,7);
	}
?>