<?php
	require_once "../inc/session_start.php";
	require_once "main.php";

	/*== Almacenando datos ==*/
	$tipo = limpiar_cadena($_POST['reporteP_tipo']);
	$persona = limpiar_cadena($_POST['reporteP_persona']);
	$usuario_id = limpiar_cadena($_POST['usuario_id']);
	$detalles = limpiar_cadena($_POST['reporteP_detalles']);
	$cantidad = limpiar_cadena($_POST['reporteP_cantidad']);
	$producto = limpiar_cadena($_POST['reporteP_producto']);

	/*== Verificando campos obligatorios ==*/
	if($tipo == "" || $persona == "" || $detalles == "" || $cantidad == "" || $producto == ""){
		echo '
			<div class="notification is-danger is-light">
				<strong>¡Ocurrio un error inesperado!</strong><br>
				No has llenado todos los campos que son obligatorios
			</div>
		';
		exit();
	}

	/*== Verificando que no sea el mismo usuario ==*/
	if($usuario_id == $persona){
		echo '
			<div class="notification is-danger is-light">
				<strong>¡Ocurrio un error inesperado!</strong><br>
				No puedes seleccionarte a ti mismo como usuario involucrado
			</div>
		';
		exit();
	}

	/*== Verificando integridad de los datos ==*/
	if(verificar_datos("[a-zA-Z0-9- ]{1,70}",$tipo)){
		echo '
			<div class="notification is-danger is-light">
				<strong>¡Ocurrio un error inesperado!</strong><br>
				El TIPO no coincide con el formato solicitado
			</div>
		';
		exit();
	}

	if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,150}",$detalles)){
		echo '
			<div class="notification is-danger is-light">
				<strong>¡Ocurrio un error inesperado!</strong><br>
				Los DETALLES no coinciden con el formato solicitado
			</div>
		';
		exit();
	}

	if(verificar_datos("[0-9.]{1,25}",$cantidad)){
		echo '
			<div class="notification is-danger is-light">
				<strong>¡Ocurrio un error inesperado!</strong><br>
				La CANTIDAD no coincide con el formato solicitado
			</div>
		';
		exit();
	}

	/*== Guardando datos ==*/
	$guardar_reporteP = conexion();
	$guardar_reporteP = $guardar_reporteP->prepare("INSERT INTO reportep(reportep_tipo, reportep_persona, usuario_id, reportep_detalles, reportep_cantidad, producto_id) VALUES(:tipo, :persona, :usuario_id, :detalles, :cantidad, :producto)");

	$marcadores = [
		":tipo" => $tipo,
		":persona" => $persona,
		":usuario_id" => $usuario_id,
		":detalles" => $detalles,
		":cantidad" => $cantidad,        
		":producto" => $producto
	];

	$guardar_reporteP->execute($marcadores);

	if($guardar_reporteP->rowCount() == 1){
		echo '
			<div class="notification is-info is-light">
				<strong>¡REPORTE REGISTRADO!</strong><br>
				El reporte se registro con exito
			</div>
		';
	}else{
		echo '
			<div class="notification is-danger is-light">
				<strong>¡Ocurrio un error inesperado!</strong><br>
				No se pudo registrar el reporte, por favor intente nuevamente
			</div>
		';
	}
	$guardar_reporteP = null;
?>