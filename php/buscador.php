<?php
// ================= INICIAR SESIÓN =================
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ================= INCLUIR FUNCIONES =================
require_once "main.php"; // Aquí ya debe existir limpiar_cadena()

// ================= RECIBIR Y LIMPIAR MÓDULO =================
$modulo_buscador = limpiar_cadena($_POST['modulo_buscador'] ?? '');
$modulos_validos = ["usuario", "categoria", "producto", "herramienta"];

if (!in_array($modulo_buscador, $modulos_validos)) {
    $_SESSION['buscador_error'] = "No se puede procesar la petición";
    header("Location: index.php");
    exit();
}

// ================= MAPEAR MÓDULO A VISTA =================
$modulos_url = [
    "usuario" => "user_search",
    "categoria" => "category_search",
    "producto" => "product_search",
    "herramienta" => "herramienta_search"
];

$modulos_url = $modulos_url[$modulo_buscador];
$modulo_buscador = "busqueda_" . $modulo_buscador;

// ================= PROCESAR BÚSQUEDA =================
if (isset($_POST['txt_buscador'])) {
    $txt = trim(limpiar_cadena($_POST['txt_buscador']));

    if ($txt === "") {
        $_SESSION['buscador_error'] = "Introduce el término de búsqueda";
        header("Location: index.php?vista=$modulos_url");
        exit();
    }

    // Validación mejorada: letras, números, espacios, acentos, ñ, guiones y apóstrofes, máximo 50 caracteres
    if (!preg_match("/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ \-']{1,50}$/u", $txt)) {
        $_SESSION['buscador_error'] = "El término de búsqueda no coincide con el formato solicitado";
        header("Location: index.php?vista=$modulos_url");
        exit();
    }

    $_SESSION[$modulo_buscador] = $txt;
    header("Location: index.php?vista=$modulos_url");
    exit();
}

// ================= ELIMINAR BÚSQUEDA =================
if (isset($_POST['eliminar_buscador'])) {
    unset($_SESSION[$modulo_buscador]);
    header("Location: index.php?vista=$modulos_url");
    exit();
}

