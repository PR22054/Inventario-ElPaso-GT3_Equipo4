<nav class="navbar is-light" role="navigation" aria-label="main navigation">

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
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          <i class="fa-solid fa-user-gear mr-1"></i> Usuarios
        </a>
        <div class="navbar-dropdown">
          <a href="index.php?vista=user_new" class="navbar-item">
            <i class="fa-solid fa-user-plus mr-1"></i> Nuevo
          </a>
          <a href="index.php?vista=user_list" class="navbar-item">
            <i class="fa-solid fa-list-ul mr-1"></i> Lista
          </a>
          <a href="index.php?vista=user_search" class="navbar-item">
            <i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar
          </a>
        </div>
      </div>
      <?php endif; ?>

      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          <i class="fa-solid fa-layer-group mr-1"></i> Categorías
        </a>
        <div class="navbar-dropdown">
          <a href="index.php?vista=category_new" class="navbar-item">
            <i class="fa-solid fa-plus mr-1"></i> Nueva
          </a>
          <a href="index.php?vista=category_list" class="navbar-item">
            <i class="fa-solid fa-list-ul mr-1"></i> Lista
          </a>
          <a href="index.php?vista=category_search" class="navbar-item">
            <i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar
          </a>
        </div>
      </div>

      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          <i class="fa-solid fa-box-open mr-1"></i> Inventario
        </a>
        <div class="navbar-dropdown">
          <a href="index.php?vista=product_new" class="navbar-item">
            <i class="fa-solid fa-plus mr-1"></i> Nuevo
          </a>
          <a href="index.php?vista=product_list" class="navbar-item">
            <i class="fa-solid fa-list-ul mr-1"></i> Lista
          </a>
          <a href="index.php?vista=product_category" class="navbar-item">
            <i class="fa-solid fa-folder-tree mr-1"></i> Por categoría
          </a>
          <a href="index.php?vista=product_search" class="navbar-item">
            <i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar
          </a>
        </div>
      </div>

      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          <i class="fa-solid fa-screwdriver-wrench mr-1"></i> Herramientas
        </a>
        <div class="navbar-dropdown">
          <a href="index.php?vista=herramienta_new" class="navbar-item">
            <i class="fa-solid fa-plus mr-1"></i> Nuevo
          </a>
          <a href="index.php?vista=herramienta_list" class="navbar-item">
            <i class="fa-solid fa-list-ul mr-1"></i> Lista
          </a>
          <a href="index.php?vista=herramienta_category" class="navbar-item">
            <i class="fa-solid fa-folder-tree mr-1"></i> Por categoría
          </a>
          <a href="index.php?vista=herramienta_search" class="navbar-item">
            <i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar
          </a>
        </div>
      </div>

    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">
          <a href="index.php?vista=user_update&user_id_up=<?php echo $_SESSION['id']; ?>" class="button is-primary is-rounded">
            <i class="fa-solid fa-user mr-1"></i> Mi cuenta
          </a>
          <a href="index.php?vista=logout" class="button is-link is-rounded">
            <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i> Salir
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>

<style>

.navbar .buttons .button {
  background-color: #000;
  color: #fff;
  border: none;
}
.navbar .buttons .button:hover {
  background-color: #222;
  color: #fff;
}
</style>
