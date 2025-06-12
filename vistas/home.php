<div class="container is-fluid">
	<div class="home-container">
		<h1 class="title">Home</h1>
		<h2 class="subtitle">¡Bienvenido <?php echo $_SESSION['nombre']." ".$_SESSION['apellido']; ?>!</h2>
		<p>Acceda a los productos, herramientas y funciones para la gestión diaria del inventario.</p>
	</div>
</div>
<style>
/* Contenedor general */
.home-container {
   background-color: #f8f9faa1; /* fondo claro */
  border: 1px solid #ccc;
  border-radius: 10px;
  padding: 30px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.8);
  max-width: 90%;
  width: 1000px;
  margin: 60px auto;
  text-align: center;
}
.home-container h1,
.home-container h2,
.home-container p {
  color: black;
  margin-bottom: 10px;
}
</style>