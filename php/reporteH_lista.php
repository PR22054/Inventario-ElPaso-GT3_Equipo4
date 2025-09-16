<?php
	$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;
	$tabla="";

	$campos="reporteh.reporteh_id,reporteh.reporteh_tipo,reporteh.reporteh_persona,reporteh.reporteh_detalles,reporteh.herramienta_id,reporteh.usuario_id,usuario.usuario_nombre,usuario.usuario_apellido";

	if(isset($busqueda) && $busqueda!=""){

		$consulta_datos="SELECT $campos FROM reporteh INNER JOIN herramienta ON reporteh.herramienta_id=herramienta.herramienta_id INNER JOIN usuario ON reporteh.usuario_id=usuario.usuario_id WHERE reporteh.usuario_id LIKE '%$busqueda%' OR reporteh.reporteh_persona LIKE '%$busqueda%' ORDER BY reporteh.reporteh_persona ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(reporteh_id) FROM reporteh WHERE reporteh_persona LIKE '%$busqueda%' OR reporteh_persona LIKE '%$busqueda%'";

	}elseif($producto_id>0){

		$consulta_datos="SELECT $campos FROM reporteh INNER JOIN herramienta ON reporteh.herramienta_id=herramienta.herramienta_id INNER JOIN usuario ON reporteh.usuario_id=usuario.usuario_id WHERE reporteh.herramienta_id='$herramienta_id' ORDER BY reporteh.reporteh_persona ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(reporteh_id) FROM reporteh WHERE herrmaienta_id='$herramienta_id'";

	}else{

		$consulta_datos="SELECT $campos FROM reporteh INNER JOIN herramienta ON reporteh.herramienta_id=herramienta.herramienta_id INNER JOIN usuario ON reporteh.usuario_id=usuario.usuario_id ORDER BY reporteh.reporteh_persona ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(reporteh_id) FROM reporteh";

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
			                <strong>'.$contador.' - '.$rows['reporteh_persona'].'</strong><br>
			                <strong>Tipo:</strong> '.$rows['reporteh_tipo'].', <strong>Persona:</strong> '.$rows['usuario_nombre'].', <strong>Detalles:</strong> '.$rows['reporteh_detalles'].', <strong>REGISTRADO POR:</strong> '.$rows['usuario_nombre'].' '.$rows['usuario_apellido'].'
			              </p>
			            </div>
			            <div class="has-text-right">			                
			                <a href="index.php?vista=reporteH_update&reporteh_id_up='.$rows['reporteh_id'].'" class="button is-success is-rounded is-small">Actualizar</a>
			                <a href="'.$url.$pagina.'&reporteh_id_del='.$rows['reporteh_id'].'" class="button is-danger is-rounded is-small">Eliminar</a>
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