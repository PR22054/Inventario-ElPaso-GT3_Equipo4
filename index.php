<?php
require "./inc/session_start.php";

// Inicia sesion si no lo esta
if (!isset($_SESSION)) session_start();

// Procesa que la alerta de mantenimiento ha sido mostrada
if (!isset($_SESSION['alerta_mantenimiento_mostrada'])) {
    $_SESSION['alerta_mantenimiento_mostrada'] = false;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <?php include "./inc/head.php"; ?>
</head>

<body>
    <?php
    // Define la vista por defecto
    if (!isset($_GET['vista']) || $_GET['vista'] == "") {
        $_GET['vista'] = "login";
    }

    // Comprueba si la vista existe
    if (is_file("./vistas/" . $_GET['vista'] . ".php") && $_GET['vista'] != "login" && $_GET['vista'] != "404") {

        // Cierra la sesion si no es valida
        if ((!isset($_SESSION['id']) || $_SESSION['id'] == "") || (!isset($_SESSION['usuario']) || $_SESSION['usuario'] == "")) {
            include "./vistas/logout.php";
            exit();
        }

        include "./inc/navbar.php";

        // Alerta de stock màximo en productos y herramientas y bienvenida al usuario
        if (isset($_SESSION['usuario']) && !isset($_SESSION['toast_mostrado'])) {
            $_SESSION['toast_mostrado'] = true; // Evita mostrarlo más de una vez por sesión
    ?>
            <div id="toast-bienvenida" style=" position:fixed; top:20px; right:20px; background:#3f3f46; color:#f9fafb; border-left:5px solid #9ca3af; border-radius:12px; padding:1rem 1.5rem; box-shadow:0 4px 15px rgba(143,142,142,0.93); display:flex; align-items:center; gap:10px;font-size:0.95rem; z-index:1000; opacity:0; transform:translateX(100px); animation:slideIn 0.6s forwards;">
                <span style="font-size:1.3rem;">⚠️</span>
                <div>
                    Alerta de Stock <br>
                    ¡Bienvenido <?= htmlspecialchars($_SESSION['usuario']) ?>!<br>
                    Consulta tus productos y herramientas: es posible que algunos hayan alcanzado el stock permitido.
                </div>
            </div>

            <script>
                // Hace desaparecer el mensaje de alerta después de 3 segundos:)
                setTimeout(() => {
                    const toast = document.getElementById("toast-bienvenida");
                    if (toast) {
                        toast.style.transition = "opacity 0.8s, transform 0.8s";
                        toast.style.opacity = "0";
                        toast.style.transform = "translateX(100px)";
                        setTimeout(() => toast.remove(), 800);
                    }
                }, 3000); //Para cambiar el tiempo en pantalla modificamos esta linea
            </script>
            <style>
                /* Animación inicial para hacer que el mensaje de alerta entre deslizándose */
                @keyframes slideIn {
                    from {
                        opacity: 0;
                        transform: translateX(100px);
                    }

                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }
            </style>
            <?php
        }

        // Alerta de herramientas pendientes de mantenimiento(Modificada)
        if (!$_SESSION['alerta_mantenimiento_mostrada']) {
            $_SESSION['alerta_mantenimiento_mostrada'] = true; // se marca como mostrada

            $pdo = new PDO("mysql:host=localhost;dbname=dis115-4;charset=utf8", "root", "");
            //Obtiene de la bd el nombre y codigo de las herramientas que requieren mantenimiento
            $alertas = $pdo->query("
            SELECT herramienta_nombre, herramienta_codigo 
            FROM herramienta 
            WHERE necesita_mantenimiento = 1
        ")->fetchAll(PDO::FETCH_ASSOC);
            //Si existen resultados se muestra la alerta
            if (count($alertas) > 0) {
            ?>
                <div id="alerta-mantenimiento" style="max-width: 700px; margin: 20px auto; text-align: center;position: relative; background-color: #f0f0f0; color: #333; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); padding: 20px; opacity: 0; transform: translateY(20px);  transition: all 0.6s ease;">
                    <strong>⚠️ Herramientas pendientes de mantenimiento:</strong>
                    <ul style="margin-top:10px; list-style:none; padding-left:0;">
                        <?php foreach ($alertas as $a): ?>
                            <li>- <?= htmlspecialchars($a['herramienta_nombre']) ?>
                                (Código: <?= htmlspecialchars($a['herramienta_codigo']) ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <script>
                    // Mostrar alerta después del mensaje de alerta de stock màximo
                    setTimeout(() => {
                        const alerta = document.getElementById("alerta-mantenimiento");
                        if (alerta) {
                            alerta.style.opacity = "1";
                            alerta.style.transform = "translateY(0)";
                        }
                    }, 4800);

                    // Oculta alerta despues de 10 segundos
                    setTimeout(() => {
                        const alerta = document.getElementById("alerta-mantenimiento");
                        if (alerta) {
                            alerta.style.opacity = "0";
                            alerta.style.transform = "translateY(20px)";
                            setTimeout(() => alerta.remove(), 800);
                        }
                    }, 10000);
                </script>
        <?php
        }
    }

        // Muestra mensaje de error en el buscador
        if (isset($_SESSION['buscador_error'])) {
            echo '<div class="notification is-danger is-light" style="max-width:700px; margin:20px auto; text-align:center;">
                <strong>¡Ocurrió un error!</strong><br>'
                . $_SESSION['buscador_error'] .
                '</div>';
            unset($_SESSION['buscador_error']);
        }

        // Incluye la vista seleccionada 
        include "./vistas/" . $_GET['vista'] . ".php";

        // Pie de pagina
        require_once "./inc/footer.php";
        include "./inc/script.php";
    } else {
        // Errores de vista
        if ($_GET['vista'] == "login") {
            include "./vistas/login.php";
        } else {
            include "./vistas/404.php"; //Vista no encontrada 
        }
    }
    ?>
</body>

</html>