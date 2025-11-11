<?php
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";

$campos = "producto.producto_id,producto.producto_codigo,producto.producto_nombre,producto.producto_precio,producto.producto_stock,producto.stock_maximo,producto.stock_minimo,producto.producto_foto,producto.categoria_id,producto.usuario_id,categoria.categoria_id,categoria.categoria_nombre,usuario.usuario_id,usuario.usuario_nombre,usuario.usuario_apellido";

// ============== CONSTRUCCIÓN DE LA CONSULTA CON FILTROS ==============
$where_conditions = [];
$order_by = "producto.producto_nombre ASC"; // Orden por defecto

// Condición de búsqueda
if (isset($busqueda) && $busqueda != "") {
	$where_conditions[] = "(producto.producto_codigo LIKE '%$busqueda%' OR producto.producto_nombre LIKE '%$busqueda%')";
}

// Condición de categoría (desde vista de categoría)
if (isset($categoria_id) && $categoria_id > 0) {
	$where_conditions[] = "producto.categoria_id='$categoria_id'";
}

// ============== FILTRO DE STOCK ==============
if (isset($_SESSION['filtro_stock']) && $_SESSION['filtro_stock'] != "") {
	$filtro_stock = $_SESSION['filtro_stock'];
	switch ($filtro_stock) {
		case 'sin_stock':
			$where_conditions[] = "producto.producto_stock = 0";
			break;
		case 'bajo':
			$where_conditions[] = "producto.producto_stock > 0 AND producto.producto_stock < 5";
			break;
		case 'normal':
			$where_conditions[] = "producto.producto_stock >= 5";
			break;
	}
}

// ============== ORDEN (PRIORIDAD: El último filtro aplicado) ==============
// Se evalúan en orden de prioridad: precio > nombre > código

if (isset($_SESSION['filtro_ordenar_precio']) && $_SESSION['filtro_ordenar_precio'] != "") {
	$orden_precio = ($_SESSION['filtro_ordenar_precio'] == 'ASC') ? 'ASC' : 'DESC';
	$order_by = "producto.producto_precio $orden_precio";
} elseif (isset($_SESSION['filtro_ordenar_nombre']) && $_SESSION['filtro_ordenar_nombre'] != "") {
	$orden_nombre = ($_SESSION['filtro_ordenar_nombre'] == 'ASC') ? 'ASC' : 'DESC';
	$order_by = "producto.producto_nombre $orden_nombre";
} elseif (isset($_SESSION['filtro_ordenar_codigo']) && $_SESSION['filtro_ordenar_codigo'] != "") {
	$orden_codigo = ($_SESSION['filtro_ordenar_codigo'] == 'ASC') ? 'ASC' : 'DESC';
	$order_by = "producto.producto_codigo $orden_codigo";
}

// ============== CONSTRUCCIÓN FINAL DE LA CONSULTA ==============
$where_clause = "";
if (count($where_conditions) > 0) {
	$where_clause = "WHERE " . implode(" AND ", $where_conditions);
}

$consulta_datos = "SELECT $campos FROM producto 
				   INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id 
				   INNER JOIN usuario ON producto.usuario_id=usuario.usuario_id 
				   $where_clause 
				   ORDER BY $order_by 
				   LIMIT $inicio,$registros";

// Consulta para contar total de registros
$consulta_total = "SELECT COUNT(producto_id) FROM producto 
				   INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id 
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
		"editado" => ["bg" => "#dcfce7", "color" => "#166534", "border" => "#22c55e", "icon" => "⚙️", "msg" => "El Stock máximo de su producto ha sido actualizado correctamente."],
		"eliminado_min" => ["bg" => "#fee2e2", "color" => "#b91c1c", "border" => "#ef4444", "icon" => "🗑️", "msg" => "Stock mínimo eliminado correctamente."],
		"editado_min" => ["bg" => "#dcfce7", "color" => "#166534", "border" => "#22c55e", "icon" => "⚙️", "msg" => "El stock mínimo de su producto ha sido actualizado correctamente."]
	];
	if (isset($notificaciones[$accion])) {
		$n = $notificaciones[$accion];
		echo '
			<div class="notification" style="background:' . $n['bg'] . '; color:' . $n['color'] . '; border-left:6px solid ' . $n['border'] . '; border-radius:10px; padding:1rem 1.5rem; margin:1.5rem auto; width:90%; max-width:700px; text-align:center; box-shadow:0 2px 10px rgba(0,0,0,0.05); animation:fadeIn 0.4s ease;">
				' . $n['icon'] . ' <strong>' . $n['msg'] . '</strong>
			</div>
			<meta http-equiv="refresh" content="2;url=index.php?vista=product_search">
		';
	}
}

// Clasificación de productos fuera de rango
$excedidos = [];
$por_debajo = [];

if (is_array($datos) && count($datos) > 0) {
	foreach ($datos as $check) {
		if (isset($check['producto_stock'], $check['stock_maximo']) && $check['stock_maximo'] && $check['producto_stock'] > $check['stock_maximo']) {
			$excedidos[] = $check;
		}
		if (isset($check['producto_stock'], $check['stock_minimo']) && $check['stock_minimo'] && $check['producto_stock'] < $check['stock_minimo']) {
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
			⚠️ Alerta de Stock de Productos
		</h2>
		<p style="color:#4b5563; text-align:center; margin-bottom:1.5rem;">Algunos productos presentan niveles de stock fuera del rango permitido:</p>';

	// Stock que exceden stock máximo
	if (count($excedidos) > 0) {
		echo '
		<h3 style="color:#374151; margin-top:1rem;">Productos que superan su stock máximo</h3>
		<div class="table-container" style="display:flex; justify-content:center;">
			<table class="table is-striped is-hoverable" style="background:white; border-radius:10px; overflow:hidden; width:90%; max-width:800px; margin-top:0.5rem;">
				<thead style="background:#f5f5f5;">
					<tr style="text-align:center;">
						<th>#</th><th>Producto</th><th>Stock Actual</th><th>Stock Máximo</th><th>Acciones</th>
					</tr>
				</thead>
				<tbody>';
		$num = 1;
		foreach ($excedidos as $item) {
			// Verificar que existen todas las claves necesarias
			if (!isset($item['producto_id']) || !isset($item['producto_nombre']) || 
			    !isset($item['producto_stock']) || !isset($item['stock_maximo'])) {
				continue;
			}
			
			echo '
				<tr style="text-align:center;">
					<td>' . $num++ . '</td>
					<td><strong>' . $item['producto_nombre'] . '</strong></td>
					<td>' . $item['producto_stock'] . '</td>
					<td>
						<form method="POST" action="" style="display:flex; justify-content:center; align-items:center; gap:8px;">
							<input type="hidden" name="producto_id" value="' . $item['producto_id'] . '">
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

	// Stock por debajo del mínimo
	if (count($por_debajo) > 0) {
		echo '
		<h3 style="color:#374151; margin-top:2rem;">Productos por debajo del stock mínimo</h3>
		<div class="table-container" style="display:flex; justify-content:center;">
			<table class="table is-striped is-hoverable" style="background:white; border-radius:10px; overflow:hidden; width:90%; max-width:800px; margin-top:0.5rem;">
				<thead style="background:#f5f5f5;">
					<tr style="text-align:center;">
						<th>#</th><th>Producto</th><th>Stock Actual</th><th>Stock Mínimo</th><th>Acciones</th>
					</tr>
				</thead>
				<tbody>';
		$num = 1;
		foreach ($por_debajo as $item) {
			// Verificar que existen todas las claves necesarias
			if (!isset($item['producto_id']) || !isset($item['producto_nombre']) || 
			    !isset($item['producto_stock']) || !isset($item['stock_minimo'])) {
				continue;
			}
			
			echo '
				<tr style="text-align:center;">
					<td>' . $num++ . '</td>
					<td><strong>' . $item['producto_nombre'] . '</strong></td>
					<td>' . $item['producto_stock'] . '</td>
					<td>
						<form method="POST" action="" style="display:flex; justify-content:center; align-items:center; gap:8px;">
							<input type="hidden" name="producto_id" value="' . $item['producto_id'] . '">
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
	if (isset($_POST['producto_id']) && !empty($_POST['producto_id'])) {
		$id = $_POST['producto_id'];

		if (isset($_POST['eliminar_stock'])) {
			$sql = $conexion->prepare("UPDATE producto SET stock_maximo = NULL WHERE producto_id = :id");
			$sql->execute([':id' => $id]);
			header("Location: index.php?vista=product_search&accion=eliminado");
			exit;
		}

		if (isset($_POST['editar_stock'])) {
			$nuevo = intval($_POST['nuevo_stock_maximo'] ?? 0);
			$sql = $conexion->prepare("UPDATE producto SET stock_maximo = :nuevo WHERE producto_id = :id");
			$sql->execute([':nuevo' => $nuevo, ':id' => $id]);
			header("Location: index.php?vista=product_search&accion=editado");
			exit;
		}

		if (isset($_POST['editar_stock_min'])) {
			$nuevo = intval($_POST['nuevo_stock_minimo'] ?? 0);
			$sql = $conexion->prepare("UPDATE producto SET stock_minimo = :nuevo WHERE producto_id = :id");
			$sql->execute([':nuevo' => $nuevo, ':id' => $id]);
			header("Location: index.php?vista=product_search&accion=editado_min");
			exit;
		}

		if (isset($_POST['eliminar_stock_min'])) {
			$sql = $conexion->prepare("UPDATE producto SET stock_minimo = NULL WHERE producto_id = :id");
			$sql->execute([':id' => $id]);
			header("Location: index.php?vista=product_search&accion=eliminado_min");
			exit;
		}
	}
}

$total = $conexion->query($consulta_total);
$total = (int) $total->fetchColumn();

$Npaginas = ceil($total / $registros);

if ($total >= 1 && $pagina <= $Npaginas) {
	$contador = $inicio + 1;
	$pag_inicio = $inicio + 1;
	
	if (is_array($datos) && count($datos) > 0) {
		foreach ($datos as $rows) {
			// Verificar que existen las claves necesarias
			if (!isset($rows['producto_id']) || !isset($rows['producto_nombre'])) {
				continue;
			}

			$estado = '';
			if (isset($rows['stock_maximo']) && $rows['stock_maximo'] && 
			    isset($rows['producto_stock']) && $rows['producto_stock'] > $rows['stock_maximo']) {
				$estado = '<span class="tag is-danger is-light">⚠ Excedido</span>';
			} elseif (isset($rows['stock_minimo']) && $rows['stock_minimo'] && 
			          isset($rows['producto_stock']) && $rows['producto_stock'] < $rows['stock_minimo']) {
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
		if (is_file("./img/producto/" . $rows['producto_foto'])) {
			$tabla .= '<img src="./img/producto/' . $rows['producto_foto'] . '" style="object-fit:cover; border-radius:0;">';
		} else {
			$tabla .= '<img src="./img/producto.png" style="object-fit:cover; border-radius:0;">';
		}
		$tabla .= '
						</figure>
					</div>

					<!-- Datos principales -->
					<div class="column">
						<h2 class="title is-5 mb-1">' . $contador . ' - ' . $rows['producto_nombre'] . '</h2>

						<div class="columns is-multiline is-mobile" style="font-size:0.9rem;">
							<div class="column is-half">
								<strong>Código:</strong> ' . $rows['producto_codigo'] . '<br>
								<strong>Stock:</strong> ' . $rows['producto_stock'] . '<br>
								<strong>Categoría:</strong> ' . $rows['categoria_nombre'] . '
							</div>
							<div class="column is-half">
								<strong>Precio:</strong> $' . $rows['producto_precio'] . '<br>
								<strong>Stock Máximo:</strong> ' . ($rows['stock_maximo'] ?? 'No definido') . '<br>
								<strong>Stock Mínimo:</strong> '.($rows['stock_minimo'] ?? 'No definido').'<br>
								<strong>Estado de Stock:</strong> ' . $estado . '
							</div>
							<div class="column is-full mt-1">
								<strong>Registrado por:</strong> ' . $rows['usuario_nombre'] . ' ' . $rows['usuario_apellido'] . '
							</div>
						</div>
					</div>

					<!-- Botones -->
					<div class="column is-narrow has-text-centered">
						<div class="buttons are-small is-flex is-flex-direction-column is-align-items-center">
							<a href="index.php?vista=product_img&product_id_up=' . $rows['producto_id'] . '" class="button is-info is-light is-rounded mb-2">
								<span class="icon"><i class="fas fa-image"></i></span>
								<span>Imagen</span>
							</a>
							<a href="index.php?vista=product_update&product_id_up=' . $rows['producto_id'] . '" class="button is-success is-light is-rounded mb-2">
								<span class="icon"><i class="fas fa-edit"></i></span>
								<span>Editar</span>
							</a>
							<a href="' . $url . $pagina . '&product_id_del=' . $rows['producto_id'] . '" 
								class="button is-danger is-light is-rounded" onclick="return confirm(\'¿Está seguro que desea eliminar el producto ' . $rows['producto_nombre'] . '?\')">
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
		$tabla .= '<p class="has-text-centered">No hay registros en el sistema</p>';
	}
}
//Muestra cuántos productos se ven y cuántos hay en total.
if ($total > 0 && $pagina <= $Npaginas) {
	$tabla .= '<p class="has-text-right mt-3">
			Mostrando productos <strong>' . $pag_inicio . '</strong> al 
			<strong>' . $pag_final . '</strong> de un total de 
			<strong>' . $total . '</strong>
		</p>';
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