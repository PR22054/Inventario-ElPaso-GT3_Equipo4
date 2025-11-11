<?php
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";

$campos = "herramienta.herramienta_id,herramienta.herramienta_codigo,herramienta.herramienta_nombre,herramienta.herramienta_precio,herramienta.herramienta_stock,herramienta.stock_maximo,herramienta.stock_minimo,herramienta.herramienta_foto,herramienta.categoria_id,herramienta.usuario_id,categoria.categoria_id,categoria.categoria_nombre,usuario.usuario_id,usuario.usuario_nombre,usuario.usuario_apellido";

// ============== CONSTRUCCIÓN DE LA CONSULTA CON FILTROS ==============
$where_conditions = [];
$order_by = "herramienta.herramienta_nombre ASC"; // Orden por defecto

// Condición de búsqueda
if (isset($busqueda) && $busqueda != "") {
	$where_conditions[] = "(herramienta.herramienta_codigo LIKE '%$busqueda%' OR herramienta.herramienta_nombre LIKE '%$busqueda%')";
}

// Condición de categoría (desde vista de categoría)
if (isset($categoria_id) && $categoria_id > 0) {
	$where_conditions[] = "herramienta.categoria_id='$categoria_id'";
}

// ============== FILTRO DE STOCK ==============
if (isset($_SESSION['filtro_stock']) && $_SESSION['filtro_stock'] != "") {
	$filtro_stock = $_SESSION['filtro_stock'];
	switch ($filtro_stock) {
		case 'sin_stock':
			$where_conditions[] = "herramienta.herramienta_stock = 0";
			break;
		case 'bajo':
			$where_conditions[] = "herramienta.herramienta_stock > 0 AND herramienta.herramienta_stock < 5";
			break;
		case 'normal':
			$where_conditions[] = "herramienta.herramienta_stock >= 5";
			break;
	}
}

// ============== ORDEN (PRIORIDAD: El último filtro aplicado) ==============
// Se evalúan en orden de prioridad: precio > nombre > asignación

if (isset($_SESSION['filtro_ordenar_precio']) && $_SESSION['filtro_ordenar_precio'] != "") {
	$orden_precio = ($_SESSION['filtro_ordenar_precio'] == 'ASC') ? 'ASC' : 'DESC';
	$order_by = "herramienta.herramienta_precio $orden_precio";
} elseif (isset($_SESSION['filtro_ordenar_nombre']) && $_SESSION['filtro_ordenar_nombre'] != "") {
	$orden_nombre = ($_SESSION['filtro_ordenar_nombre'] == 'ASC') ? 'ASC' : 'DESC';
	$order_by = "herramienta.herramienta_nombre $orden_nombre";
} elseif (isset($_SESSION['filtro_ordenar_asignacion']) && $_SESSION['filtro_ordenar_asignacion'] != "") {
	$orden_asignacion = ($_SESSION['filtro_ordenar_asignacion'] == 'ASC') ? 'ASC' : 'DESC';
	$order_by = "herramienta.herramienta_codigo $orden_asignacion";
}

// ============== CONSTRUCCIÓN FINAL DE LA CONSULTA ==============
$where_clause = "";
if (count($where_conditions) > 0) {
	$where_clause = "WHERE " . implode(" AND ", $where_conditions);
}

$consulta_datos = "SELECT $campos FROM herramienta 
				   LEFT JOIN categoria ON herramienta.categoria_id=categoria.categoria_id 
				   LEFT JOIN usuario ON herramienta.usuario_id=usuario.usuario_id 
				   $where_clause 
				   ORDER BY $order_by 
				   LIMIT $inicio,$registros";

// Consulta para contar total de registros
$consulta_total = "SELECT COUNT(herramienta_id) FROM herramienta 
				   LEFT JOIN categoria ON herramienta.categoria_id=categoria.categoria_id 
				   $where_clause";

// ============== EJECUCIÓN DE CONSULTAS ==============
$conexion = conexion();

$datos = $conexion->query($consulta_datos);

// Verificar si la consulta fue exitosa
if ($datos === false) {
    echo '<p class="has-text-centered has-text-danger">Error en la consulta de datos</p>';
    $datos = [];
} else {
    $datos = $datos->fetchAll();
}


// Notificaciones de editar y eliminar
if (isset($_GET['accion'])) {
	$accion = $_GET['accion'];
	$notificaciones = [
		"eliminado" => ["bg" => "#fee2e2", "color" => "#b91c1c", "border" => "#ef4444", "icon" => "🗑️", "msg" => "Stock máximo eliminado correctamente."],
		"editado" => ["bg" => "#dcfce7", "color" => "#166534", "border" => "#22c55e", "icon" => "⚙️", "msg" => "El Stock máximo de su herramienta ha sido actualizado correctamente."],
		"eliminado_min" => ["bg" => "#fee2e2", "color" => "#b91c1c", "border" => "#ef4444", "icon" => "🗑️", "msg" => "Stock mínimo eliminado correctamente."],
		"editado_min" => ["bg" => "#dcfce7", "color" => "#166534", "border" => "#22c55e", "icon" => "⚙️", "msg" => "El stock mínimo de su herramienta ha sido actualizado correctamente."]
	];
	if (isset($notificaciones[$accion])) {
		$n = $notificaciones[$accion];
		echo '
			<div class="notification" style="background:' . $n['bg'] . '; color:' . $n['color'] . '; border-left:6px solid ' . $n['border'] . '; border-radius:10px; padding:1rem 1.5rem; margin:1.5rem auto; width:90%; max-width:700px; text-align:center; box-shadow:0 2px 10px rgba(0,0,0,0.05); animation:fadeIn 0.4s ease;">
				' . $n['icon'] . ' <strong>' . $n['msg'] . '</strong>
			</div>
			<meta http-equiv="refresh" content="2;url=index.php?vista=herramienta_list">
		';
	}
}

// Clasificación de herramientas fuera de rango
$excedidos = [];
$por_debajo = [];

if (is_array($datos) && count($datos) > 0) {
	foreach ($datos as $check) {
		if (isset($check['herramienta_stock'], $check['stock_maximo']) && $check['stock_maximo'] && $check['herramienta_stock'] > $check['stock_maximo']) {
			$excedidos[] = $check;
		}
		if (isset($check['herramienta_stock'], $check['stock_minimo']) && $check['stock_minimo'] && $check['herramienta_stock'] < $check['stock_minimo']) {
			$por_debajo[] = $check;
		}
	}
}

// Alerta de stock
if (count($excedidos) > 0 || count($por_debajo) > 0) {
	echo '
	<div class="notification" 
		style="background:#f8fafc; border-left:6px solid #6b7280; border-radius:12px; padding:2rem; margin:2rem auto; width:95%; max-width:900px; box-shadow:0 3px 12px rgba(0,0,0,0.06); animation:fadeIn 0.4s ease;">
		<h2 class="title is-5" style="color:#374151; margin-bottom:1rem; text-align:center;">
			⚠️ Alerta de Stock de Herramientas
		</h2>
		<p style="color:#4b5563; text-align:center; margin-bottom:1.5rem;">Algunas herramientas presentan niveles de stock fuera del rango permitido:</p>';

	// Stock que exceden stock máximo:)
	if (count($excedidos) > 0) {
		echo '
		<h3 style="color:#374151; margin-top:1rem;">Herramientas que superan su stock máximo</h3>
		<div class="table-container" style="display:flex; justify-content:center;">
			<table class="table is-striped is-hoverable" style="background:white; border-radius:10px; overflow:hidden; width:90%; max-width:800px; margin-top:0.5rem;">
				<thead style="background:#f5f5f5;">
					<tr style="text-align:center;">
						<th>#</th><th>Herramienta</th><th>Stock Actual</th><th>Stock Máximo</th><th>Acciones</th>
					</tr>
				</thead>
				<tbody>';
		$num = 1;
		foreach ($excedidos as $item) {
			echo '
				<tr style="text-align:center;">
					<td>' . $num++ . '</td>
					<td><strong>' . $item['herramienta_nombre'] . '</strong></td>
					<td>' . $item['herramienta_stock'] . '</td>
					<td>
						<form method="POST" action="" style="display:flex; justify-content:center; align-items:center; gap:8px;">
							<input type="hidden" name="herramienta_id" value="' . $item['herramienta_id'] . '">
							<input class="input is-small" type="number" name="nuevo_stock_maximo" value="' . $item['stock_maximo'] . '" min="0" step="1" style="width:80px; text-align:center;">
					</td>
					<td>
							<button type="submit" name="editar_stock" class="button is-link is-light is-small is-rounded">
								<span class="icon"><i class="fas fa-save"></i></span><span>Editar</span>
							</button>
							<button type="submit" name="eliminar_stock" class="button is-danger is-light is-small is-rounded">
								<span class="icon"><i class="fas fa-trash-alt"></i></span><span>Eliminar</span>
							</button>
						</form>
					</td>
				</tr>';
		}
		echo '</tbody></table></div>';
	}

	// Stock por debajo del mínimo:)
	if (count($por_debajo) > 0) {
		echo '
		<h3 style="color:#374151; margin-top:2rem;">Herramientas por debajo del stock mínimo</h3>
		<div class="table-container" style="display:flex; justify-content:center;">
			<table class="table is-striped is-hoverable" style="background:white; border-radius:10px; overflow:hidden; width:90%; max-width:800px; margin-top:0.5rem;">
				<thead style="background:#f5f5f5;">
					<tr style="text-align:center;">
						<th>#</th><th>Herramienta</th><th>Stock Actual</th><th>Stock Mínimo</th><th>Acciones</th>
					</tr>
				</thead>
				<tbody>';
		$num = 1;
		foreach ($por_debajo as $item) {
			echo '
				<tr style="text-align:center;">
					<td>' . $num++ . '</td>
					<td><strong>' . $item['herramienta_nombre'] . '</strong></td>
					<td>' . $item['herramienta_stock'] . '</td>
					<td>
						<form method="POST" action="" style="display:flex; justify-content:center; align-items:center; gap:8px;">
							<input type="hidden" name="herramienta_id" value="' . $item['herramienta_id'] . '">
							<input class="input is-small" type="number" name="nuevo_stock_minimo" value="' . $item['stock_minimo'] . '" min="0" step="1" style="width:80px; text-align:center;">
					</td>
					<td>
							<button type="submit" name="editar_stock_min" class="button is-link is-light is-small is-rounded">
								<span class="icon"><i class="fas fa-save"></i></span><span>Editar</span>
							</button>
							<button type="submit" name="eliminar_stock_min" class="button is-danger is-light is-small is-rounded">
								<span class="icon"><i class="fas fa-trash-alt"></i></span><span>Eliminar</span>
							</button>
						</form>
					</td>
				</tr>';
		}
		echo '</tbody></table></div>';
	}

	echo '</div>';
}

// Botones editar/eliminar para stock
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['herramienta_id']) && !empty($_POST['herramienta_id'])) {
		$id = $_POST['herramienta_id'];

		if (isset($_POST['eliminar_stock'])) {
			$sql = $conexion->prepare("UPDATE herramienta SET stock_maximo = NULL WHERE herramienta_id = :id");
			$sql->execute([':id' => $id]);
			header("Location: index.php?vista=herramienta_list&accion=eliminado");
			exit;
		}

		if (isset($_POST['editar_stock'])) {
			$nuevo = intval($_POST['nuevo_stock_maximo'] ?? 0);
			$sql = $conexion->prepare("UPDATE herramienta SET stock_maximo = :nuevo WHERE herramienta_id = :id");
			$sql->execute([':nuevo' => $nuevo, ':id' => $id]);
			header("Location: index.php?vista=herramienta_list&accion=editado");
			exit;
		}

		if (isset($_POST['editar_stock_min'])) {
			$nuevo = intval($_POST['nuevo_stock_minimo'] ?? 0);
			$sql = $conexion->prepare("UPDATE herramienta SET stock_minimo = :nuevo WHERE herramienta_id = :id");
			$sql->execute([':nuevo' => $nuevo, ':id' => $id]);
			header("Location: index.php?vista=herramienta_list&accion=editado_min");
			exit;
		}

		if (isset($_POST['eliminar_stock_min'])) {
			$sql = $conexion->prepare("UPDATE herramienta SET stock_minimo = NULL WHERE herramienta_id = :id");
			$sql->execute([':id' => $id]);
			header("Location: index.php?vista=herramienta_list&accion=eliminado_min");
			exit;
		}
	}
}

// Lista de herramientas
$total = $conexion->query($consulta_total);
$total = (int) $total->fetchColumn();
$Npaginas = ceil($total / $registros);

if ($total >= 1 && $pagina <= $Npaginas) {
	$contador = $inicio + 1;
	$pag_inicio = $inicio + 1;

	if (count($datos) > 0) {
		foreach ($datos as $rows) {
			if (!isset($rows['herramienta_id']) || !isset($rows['herramienta_nombre'])) continue;

			$estado = '';
			if ($rows['herramienta_stock'] > ($rows['stock_maximo'] ?? 999999)) {
				$estado = '<span class="tag is-danger is-light">⚠ Excedido</span>';
			} elseif ($rows['herramienta_stock'] < ($rows['stock_minimo'] ?? 0)) {
				$estado = '<span class="tag is-warning is-light">⬇ Bajo</span>';
			} else {
				$estado = '<span class="tag is-success is-light">✓ Normal</span>';
			}

		$tabla .= '
			<article class="box is-shadowless" style="border:1px solid #eaeaea; border-radius:10px; margin-bottom:1rem; padding:1rem;">
				<div class="columns is-vcentered is-mobile">
					<!-- Imagen -->
					<div class="column is-narrow has-text-centered">
						<figure class="image is-96x96 mx-auto">';
		if (is_file("./img/herramienta/" . $rows['herramienta_foto'])) {
			$tabla .= '<img src="./img/herramienta/' . $rows['herramienta_foto'] . '" style="object-fit:cover; border-radius:0;">';
		} else {
			$tabla .= '<img src="./img/herramienta.png" style="object-fit:cover; border-radius:0;">';
		}
		$tabla .= '
						</figure>
					</div>

					<!-- Datos principales -->
					<div class="column">
						<h2 class="title is-5 mb-1">' . $contador . ' - ' . $rows['herramienta_nombre'] . '</h2>

						<div class="columns is-multiline is-mobile" style="font-size:0.9rem;">
							<div class="column is-half">
								<strong>Asignación:</strong> ' . $rows['herramienta_codigo'] . '<br>
								<strong>Stock:</strong> ' . $rows['herramienta_stock'] . '<br>
								<strong>Categoría:</strong> ' . $rows['categoria_nombre'] . '
							</div>
							<div class="column is-half">
								<strong>Precio:</strong> $' . $rows['herramienta_precio'] . '<br>
								<strong>Stock Máximo:</strong> ' . ($rows['stock_maximo'] ?? 'No definido') . '<br>
								<strong>Stock Mínimo:</strong> ' . ($rows['stock_minimo'] ?? 'No definido') . '<br>
								<strong>Estado de Stock:</strong> ' . $estado . '
							</div>
							<div class="column is-full mt-1">
								<strong>Registrado por:</strong> ' . $rows['usuario_nombre'] . ' ' . $rows['usuario_apellido'] . '
							</div>
						</div>
					</div>

					<!-- Botones alineados -->
					<div class="column is-narrow has-text-centered">
						<div class="buttons are-small is-flex is-flex-direction-column is-align-items-center">
							<a href="index.php?vista=herramienta_img&herramienta_id_up=' . $rows['herramienta_id'] . '" class="button is-info is-light is-rounded mb-2">
								<span class="icon"><i class="fas fa-image"></i></span>
								<span>Imagen</span>
							</a>
							<a href="index.php?vista=herramienta_vista_detallada&herramienta_id=' . $rows['herramienta_id'] . '" class="button is-warning is-light is-rounded mb-2">
								<span class="icon"><i class="fas fa-search"></i></span>
								<span>Ver</span>
							</a>
							<a href="index.php?vista=herramienta_update&herramienta_id_up=' . $rows['herramienta_id'] . '" class="button is-success is-light is-rounded mb-2">
								<span class="icon"><i class="fas fa-edit"></i></span>
								<span>Editar</span>
							</a>
							<a href="' . $url . $pagina . '&herramienta_id_del=' . $rows['herramienta_id'] . '" 
								class="button is-danger is-light is-rounded"
								onclick="return confirm(\'¿Está seguro que desea eliminar la herramienta ' . $rows['herramienta_nombre'] . '?\')">
								<span class="icon"><i class="fas fa-trash-alt"></i></span>
								<span>Eliminar</span>
							</a>
						</div>
					</div>
				</div>
			</article>';
		$contador++;
		}
		$pag_final = $contador - 1;
	} else {
		$tabla .= '<p class="has-text-centered">No se pudieron cargar los datos</p>';
	}
} else {
	if ($total >= 1) {
		$tabla .= '
				<p class="has-text-centered" >
					<a href="' . $url . '1" class="button is-link is-rounded is-small mt-4 mb-4">
						Haga clic acá para recargar el listado
					</a>
				</p>
			';
	} else {
		$tabla .= '<p class="has-text-centered" >No hay registros en el sistema</p>';
	}
}
//Muestra cuántas herramientas se ven y cuántas hay en total.
if ($total > 0 && $pagina <= $Npaginas) {
	$tabla .= '<p class="has-text-right mt-3">Mostrando Herramientas <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
}

$conexion = null;
echo $tabla;
if ($total >= 1 && $pagina <= $Npaginas) {
	echo paginador_tablas($pagina, $Npaginas, $url, 7); //Llama a la función que crea los botones de paginación.
}
?>

<style>
	/*Efecto para que las notificaciones aparezcan suavemente desde arriba, lo usan la alerta y notificaciones.*/
	@keyframes fadeIn {
		from {
			opacity: 0;
			transform: translateY(-6px);
		}
		to {
			opacity: 1;
			transform: translateY(0);
		}
	}
</style>
