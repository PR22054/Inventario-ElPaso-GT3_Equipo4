<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "./php/main.php";
$pdo = conexion();
?>

<div class="container is-fluid mb-6">
    <div class="contenedor-destacado">
        <!-- Botón volver -->
        <div style="text-align: right; margin-bottom: 1rem;">
            <button onclick="history.back()" 
                style="background-color: black; border: none; border-radius: 4px; padding: 0.4rem; cursor: pointer;">
                <span class="icon" style="color: white; font-size: 1rem;">
                    <i class="fas fa-arrow-left"></i>
                </span>
            </button>
        </div>

        <h1 class="title">Inventario</h1>
        <h2 class="subtitle">Registrar salida de repuesto</h2>
        <div id="resultado" style="margin-top:1rem;"></div>

        <div class="container pb-6 pt-6">
            <form id="formSalida" action="./php/producto_salida.php" method="POST" autocomplete="off" target="form_salida_frame" onsubmit="return confirmarSalida()">
                
                <div class="columns">
                    <!-- Producto -->
                    <div class="column">
                        <label>Repuesto</label>
                        <div class="select is-rounded is-fullwidth">
                            <select name="producto_id" id="productoSelect" required>
                                <option value="" selected>Seleccione un repuesto</option>
                                <?php
                                $productos = $pdo->query("SELECT producto_id, producto_codigo, producto_nombre, producto_stock FROM producto ORDER BY producto_nombre ASC");
                                foreach ($productos as $p) {
                                    echo "<option value='{$p['producto_id']}' data-stock='{$p['producto_stock']}'>
                                            {$p['producto_codigo']} - {$p['producto_nombre']}
                                          </option>";
                                }
                                ?>
                            </select>
                        </div>
                        <p id="stockInfo" style="margin-top: 0.5rem; font-weight: 600; color: #555;">
                            Stock disponible: -
                        </p>
                    </div>

                    <!-- Cantidad -->
                    <div class="column">
                        <label>Cantidad a retirar</label>
                        <input class="input" type="number" name="cantidad" id="cantidadInput" min="1" required>
                    </div>
                </div>

                <div class="columns">
                    <!-- Usuario -->
                    <div class="column">
                        <label>Usuario</label>
                        <div class="select is-rounded is-fullwidth">
                            <select name="usuario_id" required>
                                <option value="" selected>Seleccione usuario</option>
                                <?php
                                $usuarios = $pdo->query("SELECT usuario_id, usuario_nombre, usuario_apellido FROM usuario");
                                foreach ($usuarios as $u) {
                                    echo "<option value='{$u['usuario_id']}'>{$u['usuario_nombre']} {$u['usuario_apellido']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Tipo de salida -->
                    <div class="column">
                        <label>Tipo de salida</label>
                        <div class="select is-rounded is-fullwidth">
                            <select name="tipo_salida" required>
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="reparacion">Reparación</option>
                                <option value="venta">Venta</option>
                            </select>
                        </div>
                    </div>
                </div>

               <p class="has-text-centered">
                    <button type="submit" class="button is-info is-rounded">
                        <span class="icon is-small"><i class="fas fa-plus"></i></span>
                        <span>Registrar salida</span>
                    </button>
                    <a href="index.php?vista=product_exit_history" class="button is-link is-rounded">
                        <span class="icon is-small"><i class="fas fa-history"></i></span>
                        <span>Ver historial</span>
                    </a>
                </p>

            </form>

            <!-- iframe oculto para procesar el form sin recargar -->
            <iframe name="form_salida_frame" style="display:none;"></iframe>
        </div>
    </div>
</div>

<!-- Modal minimalista -->
<div id="confirmModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background: rgba(0,0,0,0.3); justify-content:center; align-items:center; z-index:1000;">
  <div style="background:#fff; padding:1.5rem 2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.2); 
      max-width:400px; width:90%; text-align:center;">
    <p id="modalBody" style="margin-bottom:1.5rem; font-weight:500; color:#333;"></p>
    <div style="display:flex; justify-content:center; gap:1rem;">
      <button onclick="aceptarSalida()" style="background:#3e95f0; color:#fff; border:none; border-radius:5px; padding:0.5rem 1.2rem; cursor:pointer;">Confirmar</button>
      <button onclick="cerrarModal()" style="background:#e74c3c; color:#fff; border:none; border-radius:5px; padding:0.5rem 1.2rem; cursor:pointer;">Cancelar</button>
    </div>
  </div>
</div>

<script>
// Stock dinámico
const productoSelect = document.getElementById('productoSelect');
const stockInfo = document.getElementById('stockInfo');
const cantidadInput = document.getElementById('cantidadInput');

productoSelect.addEventListener('change', () => {
    const selectedOption = productoSelect.selectedOptions[0];
    const stock = selectedOption.getAttribute('data-stock');
    stockInfo.textContent = `Stock disponible: ${stock}`;
    cantidadInput.max = stock; // opcional
});

// Variables para modal
let productoSeleccionado = '';
let cantidadSeleccionada = '';

// Función de confirmación minimalista
function confirmarSalida() {
    if (!productoSelect.value || !cantidadInput.value) {
        alert('Debe seleccionar un producto y cantidad');
        return false;
    }

    productoSeleccionado = productoSelect.selectedOptions[0].text;
    cantidadSeleccionada = cantidadInput.value;

    document.getElementById('modalBody').textContent = 
        `¿Está seguro que desea registrar la salida de ${cantidadSeleccionada} unidad(es) de:\n${productoSeleccionado}?`;

    document.getElementById('confirmModal').style.display = 'flex';
    return false; 
}

function cerrarModal() {
    document.getElementById('confirmModal').style.display = 'none';
}

function aceptarSalida() {
    document.getElementById('formSalida').submit();
    cerrarModal();
}
</script>

<style>
label {
    color: #000;
    font-weight: 600;
}
</style>
