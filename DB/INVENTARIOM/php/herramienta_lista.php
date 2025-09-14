<?php
	$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0; 
	$tabla="";

	$campos="herramienta.herramienta_id,herramienta.herramienta_codigo,herramienta.herramienta_nombre,herramienta.herramienta_precio,herramienta.herramienta_stock,herramienta.herramienta_foto,herramienta.categoria_id,herramienta.usuario_id,categoria.categoria_id,categoria.categoria_nombre,usuario.usuario_id,usuario.usuario_nombre,usuario.usuario_apellido";

	if(isset($busqueda) && $busqueda!=""){

		$consulta_datos="SELECT $campos FROM herramienta INNER JOIN categoria ON herramienta.categoria_id=categoria.categoria_id INNER JOIN usuario ON herramienta.usuario_id=usuario.usuario_id WHERE herramienta.herramienta_codigo LIKE '%$busqueda%' OR herramienta.herramienta_nombre LIKE '%$busqueda%' ORDER BY herramienta.herramienta_nombre ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(herramienta_id) FROM herramienta WHERE herramienta_codigo LIKE '%$busqueda%' OR herramienta_nombre LIKE '%$busqueda%'";

	}elseif($categoria_id>0){

		$consulta_datos="SELECT $campos FROM herramienta INNER JOIN categoria ON herramienta.categoria_id=categoria.categoria_id INNER JOIN usuario ON herramienta.usuario_id=usuario.usuario_id WHERE herramienta.categoria_id='$categoria_id' ORDER BY herramienta.herramienta_nombre ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(herramienta_id) FROM herramienta WHERE categoria_id='$categoria_id'";

	}else{

		$consulta_datos="SELECT $campos FROM herramienta INNER JOIN categoria ON herramienta.categoria_id=categoria.categoria_id INNER JOIN usuario ON herramienta.usuario_id=usuario.usuario_id ORDER BY herramienta.herramienta_nombre ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(herramienta_id) FROM herramienta";

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
			        <figure class="media-left">
			            <p class="image is-64x64">';
			            if(is_file("./img/herramienta/".$rows['herramienta_foto'])){
			            	$tabla.='<img src="./img/herramienta/'.$rows['herramienta_foto'].'">';
			            }else{
			            	$tabla.='<img src="./img/herramienta.png">';
			            }
			   $tabla.='</p>
			        </figure>
			        <div class="media-content">
			            <div class="content">
			              <p>
			                <strong>'.$contador.' - '.$rows['herramienta_nombre'].'</strong><br>
			                <strong>ASIGNACION:</strong> '.$rows['herramienta_codigo'].', <strong>PRECIO:</strong> $'.$rows['herramienta_precio'].', <strong>STOCK:</strong> '.$rows['herramienta_stock'].', <strong>CATEGORIA:</strong> '.$rows['herramienta_nombre'].', <strong>REGISTRADO POR:</strong> '.$rows['usuario_nombre'].' '.$rows['usuario_apellido'].'
			              </p>
			            </div>
			            <div class="has-text-right">
			                <a href="index.php?vista=herramienta_img&herramienta_id_up='.$rows['herramienta_id'].'" class="button is-link is-rounded is-small">Imagen</a>
			                <a href="index.php?vista=herramienta_update&herramienta_id_up='.$rows['herramienta_id'].'" class="button is-success is-rounded is-small">Actualizar</a>
			                <a href="'.$url.$pagina.'&herramienta_id_del='.$rows['herramienta_id'].'" class="button is-danger is-rounded is-small">Eliminar</a>
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
		$tabla.='<p class="has-text-right">Mostrando Herramientas <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
	}

	$conexion=null;
	echo $tabla;

	if($total>=1 && $pagina<=$Npaginas){
		echo paginador_tablas($pagina,$Npaginas,$url,7);
	}