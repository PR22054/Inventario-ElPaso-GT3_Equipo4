<?php
	$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;
	$tabla="";

	$campos="reportep.reportep_id,reportep.reportep_tipo,reportep.reportep_persona,reportep.reportep_detalles,reportep.reportep_cantidad,reportep.producto_id,reportep.usuario_id,usuario.usuario_nombre,usuario.usuario_apellido";

	if(isset($busqueda) && $busqueda!=""){

		$consulta_datos="SELECT $campos FROM reportep INNER JOIN producto ON reportep.producto_id=producto.producto_id INNER JOIN usuario ON reportep.usuario_id=usuario.usuario_id WHERE reportep.usuario_id LIKE '%$busqueda%' OR reportep.reportep_persona LIKE '%$busqueda%' ORDER BY reportep.reportep_persona ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(reportep_id) FROM reportep WHERE reportep_persona LIKE '%$busqueda%' OR reportep_persona LIKE '%$busqueda%'";

	}elseif($producto_id>0){

		$consulta_datos="SELECT $campos FROM reportep INNER JOIN producto ON reportep.producto_id=producto.producto_id INNER JOIN usuario ON reportep.usuario_id=usuario.usuario_id WHERE reportep.producto_id='$producto_id' ORDER BY reportep.reportep_persona ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(reportep_id) FROM reportep WHERE producto_id='$producto_id'";

	}else{

		$consulta_datos="SELECT $campos FROM reportep INNER JOIN producto ON reportep.producto_id=producto.producto_id INNER JOIN usuario ON reportep.usuario_id=usuario.usuario_id ORDER BY reportep.reportep_persona ASC LIMIT $inicio,$registros";

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
			$tabla.='
				<article class="media">
			        <figure class="media-left">';
			           
			   $tabla.='</p>
			        </figure>
			        <div class="media-content">
			            <div class="content">
			              <p>
			                <strong>'.$contador.' - '.$rows['reportep_persona'].'</strong><br>
			                <strong>Tipo:</strong> '.$rows['reportep_tipo'].', <strong>Persona:</strong> '.$rows['usuario_nombre'].', <strong>Cantidad:</strong> '.$rows['reportep_cantidad'].', <strong>Detalles:</strong> '.$rows['reportep_detalles'].', <strong>REGISTRADO POR:</strong> '.$rows['usuario_nombre'].' '.$rows['usuario_apellido'].'
			              </p>
			            </div>
			            <div class="has-text-right">			                
			                <a href="index.php?vista=reporteP_update&reportep_id_up='.$rows['reportep_id'].'" class="button is-success is-rounded is-small">Actualizar</a>
			                <a href="'.$url.$pagina.'&reportep_id_del='.$rows['reportep_id'].'" class="button is-danger is-rounded is-small">Eliminar</a>
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
		$tabla.='<p class="has-text-right">Mostrando productos <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
	}

	$conexion=null;
	echo $tabla;

	if($total>=1 && $pagina<=$Npaginas){
		echo paginador_tablas($pagina,$Npaginas,$url,7);
	}