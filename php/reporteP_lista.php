<?php
	$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;
	$tabla="";

	// Campos para mostrar información completa
	$campos="reportep.reportep_id, reportep.reportep_tipo, reportep.reportep_persona, reportep.reportep_detalles, 
			 reportep.reportep_cantidad, reportep.producto_id, reportep.usuario_id, 
			 usuario.usuario_nombre, usuario.usuario_apellido, 
			 producto.producto_nombre,
			 persona.usuario_nombre as persona_nombre, persona.usuario_apellido as persona_apellido";

	if(isset($busqueda) && $busqueda!=""){

		$consulta_datos="SELECT $campos FROM reportep 
						INNER JOIN producto ON reportep.producto_id=producto.producto_id 
						INNER JOIN usuario ON reportep.usuario_id=usuario.usuario_id 
						LEFT JOIN usuario as persona ON reportep.reportep_persona = persona.usuario_id
						WHERE reportep.reportep_persona LIKE '%$busqueda%' 
						OR usuario.usuario_nombre LIKE '%$busqueda%' 
						OR producto.producto_nombre LIKE '%$busqueda%'
						OR reportep.reportep_tipo LIKE '%$busqueda%'
						OR persona.usuario_nombre LIKE '%$busqueda%'
						ORDER BY reportep.reportep_persona ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(reportep_id) FROM reportep 
						WHERE reportep_persona LIKE '%$busqueda%' 
						OR reportep_tipo LIKE '%$busqueda%'";

	}elseif($producto_id>0){

		$consulta_datos="SELECT $campos FROM reportep 
						INNER JOIN producto ON reportep.producto_id=producto.producto_id 
						INNER JOIN usuario ON reportep.usuario_id=usuario.usuario_id 
						LEFT JOIN usuario as persona ON reportep.reportep_persona = persona.usuario_id
						WHERE reportep.producto_id='$producto_id' 
						ORDER BY reportep.reportep_persona ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(reportep_id) FROM reportep WHERE producto_id='$producto_id'";

	}else{

		$consulta_datos="SELECT $campos FROM reportep 
						INNER JOIN producto ON reportep.producto_id=producto.producto_id 
						INNER JOIN usuario ON reportep.usuario_id=usuario.usuario_id 
						LEFT JOIN usuario as persona ON reportep.reportep_persona = persona.usuario_id
						ORDER BY reportep.reportep_persona ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(reportep_id) FROM reportep";

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
			// Determinar qué nombre mostrar para la persona
			$nombre_persona = '';
			
			// Si reportep_persona es un ID numérico, usar el nombre de la tabla persona
			if(is_numeric($rows['reportep_persona']) && !empty($rows['persona_nombre'])) {
				$nombre_persona = $rows['persona_nombre'] . ' ' . $rows['persona_apellido'];
			} else {
				// Si es texto, usar directamente el valor
				$nombre_persona = $rows['reportep_persona'];
			}

			$tabla.='
				<article class="media">
			        <figure class="media-left">
			            <p class="image is-64x64">';
			            // Aquí puedes agregar una imagen si lo deseas
			   $tabla.='</p>
			        </figure>
			        <div class="media-content">
			            <div class="content">
			              <p>
			                <strong>'.$contador.' - '.$nombre_persona.'</strong><br>
			                <div class="tags has-addons">
			                	<span class="tag is-dark">Tipo</span>
			                	<span class="tag is-info">'.ucfirst($rows['reportep_tipo']).'</span>
			                	<span class="tag is-dark">Producto</span>
			                	<span class="tag is-link">'.$rows['producto_nombre'].'</span>
			                	<span class="tag is-dark">Cantidad</span>
			                	<span class="tag is-warning">'.$rows['reportep_cantidad'].'</span>
			                </div>
			                <div class="field is-grouped is-grouped-multiline" style="margin-top: 0.5rem;">
			                	<div class="control">
			                		<div class="tags has-addons">
			                			<span class="tag is-dark">Persona del Reporte</span>
			                			<span class="tag is-primary"><strong>'.$nombre_persona.'</strong></span>
			                		</div>
			                	</div>
			                	<div class="control">
			                		<div class="tags has-addons">
			                			<span class="tag is-dark">Registrado por</span>
			                			<span class="tag is-success">'.$rows['usuario_nombre'].' '.$rows['usuario_apellido'].'</span>
			                		</div>
			                	</div>
			                </div>
			                <div class="box" style="margin-top: 0.5rem; padding: 1rem;">
			                	<strong>Detalles:</strong> '.$rows['reportep_detalles'].'
			                </div>
			              </p>
			            </div>
			            <div class="has-text-right">			                
			                <a href="index.php?vista=reporteP_update&reportep_id_up='.$rows['reportep_id'].'" class="button is-success is-rounded is-small">
								<span class="icon is-small"><i class="fas fa-edit"></i></span>
								<span>Actualizar</span>
							</a>
			                <a href="'.$url.$pagina.'&reportep_id_del='.$rows['reportep_id'].'" class="button is-danger is-rounded is-small">
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
		$tabla.='<p class="has-text-right">Mostrando reportes <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
	}

	$conexion=null;
	echo $tabla;

	if($total>=1 && $pagina<=$Npaginas){
		echo paginador_tablas($pagina,$Npaginas,$url,7);
	}
?>