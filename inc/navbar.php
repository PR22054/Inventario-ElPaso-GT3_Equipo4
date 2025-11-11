<?php
// Inicia buffer para evitar problemas con header()
ob_start();

// Conexion a la base de datos
$pdo = new PDO("mysql:host=localhost;dbname=dis115-4;charset=utf8", "root", "");

// Traer herramientas que necesitan mantenimiento
$alertas = $pdo->query("
    SELECT herramienta_nombre, herramienta_codigo 
    FROM herramienta 
    WHERE necesita_mantenimiento = 1
")->fetchAll(PDO::FETCH_ASSOC);

$contador = count($alertas); // num de alertas
?>

<nav class="navbar" role="navigation" aria-label="main navigation">

    <div class="navbar-brand">
        <a class="navbar-item" href="index.php?vista=home">
            <img src="./img/home.png" width="50" height="28">
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">

            <?php if ($_SESSION['usuario_tipo'] === 'admin'): ?>
                <!-- menu para admin -->
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">Usuarios</a>
                    <div class="navbar-dropdown">
                        <a href="index.php?vista=user_new" class="navbar-item">Nuevo</a>
                        <a href="index.php?vista=user_list" class="navbar-item">Lista</a>
                        <a href="index.php?vista=user_search" class="navbar-item">Buscar</a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- menu resto de usuarios -->
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Categorías</a>
                <div class="navbar-dropdown">
                    <a href="index.php?vista=category_new" class="navbar-item">Nueva</a>
                    <a href="index.php?vista=category_list" class="navbar-item">Lista</a>
                    <a href="index.php?vista=category_search" class="navbar-item">Buscar</a>
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Inventario</a>
                <div class="navbar-dropdown">
                    <a href="index.php?vista=product_new" class="navbar-item">Nuevo</a>
                    <a href="index.php?vista=product_list" class="navbar-item">Lista</a>
                    <a href="index.php?vista=product_category" class="navbar-item">Por categoría</a>
                    <a href="index.php?vista=product_search" class="navbar-item"><i class="fas fa-search"></i>&nbsp; Buscar</a>
                    <a href="index.php?vista=product_exit" class="navbar-item"><i class="fas fa-door-open" style="margin-right: 6px;"></i> Registrar salida</a>

                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Herramientas</a>
                <div class="navbar-dropdown">
                    <a href="index.php?vista=herramienta_new" class="navbar-item">Nuevo</a>
                    <a href="index.php?vista=herramienta_list" class="navbar-item">Lista</a>
                    <a href="index.php?vista=herramienta_category" class="navbar-item">Por categoría</a>
                    <a href="index.php?vista=herramienta_search" class="navbar-item">Buscar</a>
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Reportes</a>
                <div class="navbar-dropdown">
                    <a href="index.php?vista=reporteH_new" class="navbar-item">Herramientas</a>
                    <a href="index.php?vista=reporteH_list" class="navbar-item">Herramientas Lista</a>
                    <a href="index.php?vista=reporteP_new" class="navbar-item">Productos</a>
                    <a href="index.php?vista=reporteP_list" class="navbar-item">Productos Lista</a>
                    <a href="index.php?vista=mantenimiento_new" class="navbar-item">Mantenimiento</a>
                    <a href="index.php?vista=mantenimiento_list" class="navbar-item">Mantenimiento Lista</a>
                </div>
            </div>

        </div>

        <div class="navbar-end">
            <!-- bton de notificacion de mantenimiento -->
            <div class="navbar-item">
                <a href="index.php?vista=mantenimiento_alertas" class="button is-rounded btn-notificacion">
                    <span class="icon" style="color: #fff;">
                        <i class="fas fa-bell"></i>
                    </span>
                    <?php if ($contador > 0): ?>
                        <span class="tag" style="background-color: #ff0000; color: #fff;"><?php echo $contador; ?></span>
                    <?php endif; ?>
                </a>
            </div>

            <!-- botones de Mi cuenta y Salir -->
            <div class="navbar-item">
                <div class="buttons">
                    <a href="index.php?vista=user_update&user_id_up=<?php echo $_SESSION['id']; ?>" class="button is-primary is-rounded">
                        Mi cuenta
                    </a>
                    <a href="index.php?vista=logout" class="button is-link is-rounded">
                        Salir
                    </a>

                </div>
            </div>
        </div>
    </div>
</nav>