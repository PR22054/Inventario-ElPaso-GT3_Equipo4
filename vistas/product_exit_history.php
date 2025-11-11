<?php
require_once "./php/main.php";
$pdo = conexion();

/*== Consultar salidas ==*/
$salidas = $pdo->query("
    SELECT s.salida_id, p.producto_nombre, s.cantidad, s.orden_trabajo, 
           s.tipo_salida, u.usuario_nombre, u.usuario_apellido, s.fecha_salida
    FROM salida_producto s
    INNER JOIN producto p ON s.producto_id = p.producto_id
    INNER JOIN usuario u ON s.usuario_id = u.usuario_id
    ORDER BY s.fecha_salida DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container is-fluid mb-6" style="position: relative; z-index: 1;">
    <div class="contenedor-destacado" >

        <!-- Botón volver -->
        <div style="text-align: right; margin-bottom: 1rem;">
            <button onclick="history.back()" 
                style="background-color: black; border: none; border-radius: 4px; padding: 0.4rem 0.6rem; cursor: pointer;">
                <span class="icon" style="color: white; font-size: 1rem;">
                    <i class="fas fa-arrow-left"></i>
                </span>
            </button>
        </div>

        <h1 class="title">Inventario</h1>
        <h2 class="subtitle">Historial de salidas de productos</h2>

        <div class="container pb-6 pt-6">
            <?php if (!empty($salidas)): ?>
                <table class="table is-bordered is-striped is-fullwidth" style="background: white; border-radius: 6px; overflow: hidden;">
                    <thead>
                        <tr style="background-color: #070707;">
                            <th style="color: white;">ID</th>
                            <th style="color: white;">Producto</th>
                            <th style="color: white;">Cantidad</th>
                            <th style="color: white;">Tipo de salida</th>
                            <th style="color: white;">Usuario</th>
                            <th style="color: white;">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salidas as $s): ?>
                        <tr>
                            <td><?= htmlspecialchars($s['salida_id']) ?></td>
                            <td><?= htmlspecialchars($s['producto_nombre']) ?></td>
                            <td><?= htmlspecialchars($s['cantidad']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($s['tipo_salida'])) ?></td>
                            <td><?= htmlspecialchars($s['usuario_nombre'] . " " . $s['usuario_apellido']) ?></td>
                            <td><?= htmlspecialchars(date("d/m/Y", strtotime($s['fecha_salida']))) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align:center; font-style:italic; color:#555; padding:2rem; background: rgba(255,255,255,0.9); border-radius: 8px; box-shadow: 0 1px 6px rgba(0,0,0,0.05);">
                    No hay registros de salidas
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
label {
    color: #090909;
    font-weight: 600;
}
.table th, .table td {
    text-align: center;
}
.table thead th {
    color: white; 
}
.table tbody tr:hover {
    background-color: #f0f0f0; 
}
</style>
