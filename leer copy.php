<?php
include('includes/conectar.php');
include('includes/secionesUser.php');

if (!isset($_SESSION['clave'])) {
	header("Location: error.php");
}

if (isset($_REQUEST['cerrar'])) {
	session_destroy();
	header("location:index.php");
}
$sql = "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'";
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);


$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);



$id = $_GET['id'];
$sql = "SELECT * FROM mensaje WHERE de='" . $_SESSION['user'] . "' and ID='" . $id . "'";
$res = mysqli_query($cont, $sql);
$row = mysqli_fetch_assoc($res);
include('includes/encabezado.php')
?>

<body>
	<?php
	include('includes/header.php');


	?>
	<?php
	echo ("<h1 > "  . $a1['nombre'] . "</h1><br>");

	?>
	<hr>

	<h1 style="margin-bottom: -50px;">Correo Interno</h1>
	<div class="menu">
		<h1><?php include('includes/menu.php') ?></h1>
	</div>
	<div class="tabla">
		<header>
			<input id="btn-menu" type="checkbox">
			<label for="btn-menu"><img src="img/588a64e0d06f6719692a2d10.png" class="imgg"></label>
			<nav class="menuh">
				<ul>
					<li><a href="listar.php">mensajes recibidos </a> </li>
					<li><a href="enviados.php">mensajes enviados</a> </li>
					<li><a href="crear.php">Crear mensajes</a></li>
				</ul>
			</nav>
		</header>
		<br>
		<div class='contenedor_interno'>
			<h3><strong>Para:</strong></h3>
			<p><?php echo $row['para'] ?></p>
			<h3><strong>Fecha:</strong> </h3>
			<p><?php echo $row['fecha'] ?>
			<h3><strong>Asunto:</strong></h3>
			<p><?php echo $row['asunto'] ?></p><br>
			<h3><strong>Mensaje:</strong></h3>
			<div >
				<h4>🗎 archivos adjuntos</h4>
				<?php
				if (empty($row['file']) || $row['file'] == "vacio") {
				?>
					<p><?php echo $row['file']; ?> </p>
				<?php
				} else {

				?>
					<p><?php echo $row['file']; ?> <?php echo "<a href='archivos/archivosEmail/" . $row['de'] . $row['file'] . "'>Descargas</a>"; ?></p>
			</div>
		<?php } ?>
		<br>
		<div><?php echo $row['texto'] ?></div>

		</div>


	</div>

</body>

</html>
<?php


mysqli_free_result($resultado1);
mysqli_free_result($resultado);
mysqli_free_result($res);
mysqli_close($cont);

?>