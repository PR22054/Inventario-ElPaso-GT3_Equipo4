<?php
// Conexión a la base de datos
$pdo = new PDO("mysql:host=localhost;dbname=dis115-4;charset=utf8", "root", "");

// Traer herramientas que necesitan mantenimiento
$alertas = $pdo->query("
    SELECT herramienta_id, herramienta_nombre, herramienta_codigo 
    FROM herramienta 
    WHERE necesita_mantenimiento = 1
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-container">
    <div class="container is-fluid mb-6">
        <div class="contenedor-destacado"style="width: 100%; text-align: center;">
            <div style="text-align: right; margin-bottom: 1rem;">
            <button onclick="history.back()" 
                style="background-color: black; border: none; border-radius: 4px; padding: 0.4rem; cursor: pointer;">
                <span class="icon" style="color: white; font-size: 1rem;">
                    <i class="fas fa-arrow-left"></i>
                </span>
            </button>
        </div>
            <h1 class="title">Mantenimientos</h1>
            <h2 class="subtitle">Lista de herramientas pendientes de mantenimiento</h2>

            <div class="container pb-6 pt-6">
                <?php if(count($alertas) > 0): ?>
                    <table class="table is-fullwidth is-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Código</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($alertas as $a): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($a['herramienta_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($a['herramienta_codigo']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="has-text-centered">No hay herramientas pendientes de mantenimiento.</p>
                <?php endif; ?>


            <!-- Botónes-->
            <div style="margin-top: 20px;">
                <a href="index.php?vista=mantenimiento_new&herramienta_id" class="button is-dark" style="min-width: 150px;">
                    Ir a Mantenimiento
                </a>
                <a href="index.php?vista=home" class="button is-dark" style="min-width: 150px;">
                    Salir
                </a>
            </div>

                 
            </div>
        </div>
    </div>
</div>
<style>
.mantenimiento-table {
  width: 50%; /* 🔹 Más angosta y centrada */
  margin: 1.5rem auto;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  background-color: #fff;
  transition: all 0.2s ease;
}

.mantenimiento-table thead {
  background-color: #f5f5f5;
}

.mantenimiento-table th {
  font-weight: 600;
  color: #333;
  text-align: center;
  padding: 0.9rem;
}

.mantenimiento-table td {
  text-align: center;
  padding: 0.8rem 1rem;
  color: #444;
  border-bottom: 1px solid #eee;
}

.mantenimiento-table tbody tr:hover {
  background-color: #fafafa;
  transform: scale(1.01);
  transition: all 0.15s ease;
}
</style>