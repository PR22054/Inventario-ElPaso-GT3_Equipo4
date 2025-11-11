<?php
// ================= INCLUIR FUNCIONES =================
require_once "main.php"; // Incluye limpiar_cadena() y conexión a BD

// Asegúrate de que la solicitud es AJAX y que se han enviado los datos requeridos
if (!isset($_POST['termino']) || empty($_POST['termino']) || !isset($_POST['modulo'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Petición incompleta.']);
    exit();
}

// ================= RECIBIR Y LIMPIAR DATOS =================
$termino = limpiar_cadena($_POST['termino']);
$modulo = limpiar_cadena($_POST['modulo']);

// 1. Validar el término para seguridad (debe coincidir con la validación del frontend)
if (!preg_match("/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ \-']{1,50}$/u", $termino)) {
    http_response_code(200);
    echo json_encode([]); // Devolver array vacío si el término no es válido
    exit();
}

// 2. Mapear modulo, definir selección y WHERE clause
$tabla = "";
$columna_select = ""; // Columnas a devolver (lo que verá el usuario)
$where_clause = ""; // La condición SQL de búsqueda

switch ($modulo) {
    case 'usuario':
        $tabla = "usuario";
        // Selecciona el nombre de usuario y el nombre completo (concatenado)
        $columna_select = "usuario_usuario, CONCAT(usuario_nombre, ' ', usuario_apellido) AS nombre_completo"; 
        
        // La condición de búsqueda incluirá el nombre de usuario, nombre y apellido
        $where_clause = "
            (usuario_usuario LIKE :termino OR
             usuario_nombre LIKE :termino OR 
             usuario_apellido LIKE :termino
            )";
        // Para ordenar, ordenamos por el nombre de usuario o nombre/apellido
        $order_by = "usuario_usuario ASC"; 
        break;
    case 'categoria':
        $tabla = "categoria";
        $columna_select = "categoria_nombre";
        $where_clause = "categoria_nombre LIKE :termino";
        $order_by = "categoria_nombre ASC";
        break;
    case 'herramienta':
        $tabla = "herramienta";
        $columna_select = "herramienta_nombre";
        $where_clause = "herramienta_nombre LIKE :termino";
        $order_by = "herramienta_nombre ASC";
        break;
    case 'producto': 
        $tabla = "producto";
        $columna_select = "producto_nombre";
        $where_clause = "producto_nombre LIKE :termino";
        $order_by = "producto_nombre ASC";
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Módulo no válido.']);
        exit();
}

// ================= CONSULTA A LA BASE DE DATOS =================
$conn = conexion(); // Asume una función para conectar a la BD
$stmt = null;
$resultados = [];

try {
    // Generación de la consulta SQL dinámica
    $sql = "SELECT {$columna_select} FROM {$tabla} WHERE {$where_clause} ORDER BY {$order_by} LIMIT 10";
    
    $stmt = $conn->prepare($sql);
    $busqueda_param = '%' . $termino . '%';
    
    // Asignación de parámetros: solo asignamos :termino si está en la cláusula WHERE (que siempre lo estará en este diseño)
    $stmt->bindParam(":termino", $busqueda_param);
    
    $stmt->execute();

    if ($modulo === 'usuario') {
        $resultados_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Procesar resultados de usuario para devolver solo valores únicos (ej. nombre de usuario y nombre completo)
        $resultados_finales = [];
        foreach ($resultados_raw as $fila) {
            // Anadir el nombre de usuario
            $resultados_finales[] = $fila['usuario_usuario']; 
            
            // Anadir el nombre completo, pero solo si no es vacio (manejo basico de datos vacíos)
            if (!empty(trim($fila['nombre_completo']))) {
                 $resultados_finales[] = trim($fila['nombre_completo']);
            }
        }
        // Devolver solo valores unicos
        $resultados = array_unique($resultados_finales);

    } else {
        // Para categorías, herramientas y productos, devolvemos la columna seleccionada directamente
        $resultados = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    // 3. Devolver resultados en formato JSON
    header('Content-Type: application/json');
    echo json_encode(array_values($resultados)); // array_values para reindexar si se usó array_unique

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de base de datos.']);
} finally {
    if ($stmt) {
        $stmt = null;
    }
    $conn = null;
}