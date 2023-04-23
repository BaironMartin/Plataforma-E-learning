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



$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);

$sql = ("SELECT* FROM misclases, usuarios WHERE misclases.usuario=usuarios.Email AND misclases.clave='" . $_SESSION['clave'] . "'");
$resultado = mysqli_query($cont, $sql);
$n = mysqli_num_rows($resultado);
$alumnos = mysqli_fetch_assoc($resultado);

$sql = ("SELECT* FROM clase, usuarios WHERE clase.usuario=usuarios.Email AND clase.clave='" . $_SESSION['clave'] . "'");
$resultado2 = mysqli_query($cont, $sql);
$docente = mysqli_fetch_assoc($resultado2);

$sql = "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'";
$resultad = mysqli_query($cont, $sql);
$as = mysqli_fetch_assoc($resultad);



if (isset($_REQUEST['enviar'])) {
	$u = $_SESSION['user'];
	$c = $_SESSION['clave'];
	$para = $_REQUEST['para'];
	$asunto = $_REQUEST['asunto'];
	$texto = $_REQUEST['texto'];
	$file = $_FILES['file']['name'];

	if ($file == "") {
		$file = "vacio";
	} else {
		$f = $file;
		move_uploaded_file($_FILES['file']['tmp_name'], "archivos/ArchivosEmail/" . $u . $f);
	}

	mysqli_query($cont, "INSERT INTO mensaje VALUES(NULL,'$para','$u',NULL,NULL,'$asunto','$texto','$c','$file')");
	header("location:email.php");
}

if (isset($_REQUEST['enviartodo'])) {
	$u = $_SESSION['user'];
	$c = $_SESSION['clave'];
	
	$asunto = $_REQUEST['asunto'];
	$texto = $_REQUEST['texto'];
	$file = $_FILES['file']['name'];

	$sqlcorreo = ("SELECT* FROM misclases, usuarios WHERE misclases.usuario=usuarios.Email AND misclases.clave='" . $_SESSION['clave'] . "'");
	$resultadocorreo = mysqli_query($cont, $sqlcorreo);
	$ncorreo = mysqli_num_rows($resultadocorreo);
	$alumnoscorreo = mysqli_fetch_assoc($resultadocorreo);

	if ($file == "") {
		$file = "vacio";
	} else {
		$f = $file;
		move_uploaded_file($_FILES['file']['tmp_name'], "archivos/ArchivosEmail/" . $u . $f);
	}
	do {
		$para = $alumnoscorreo['Email'];
		mysqli_query($cont, "INSERT INTO mensaje VALUES(NULL,'$para','$u',NULL,NULL,'$asunto','$texto','$c','$file')");
	} while ($alumnoscorreo = mysqli_fetch_assoc($resultadocorreo));
	header("location:email.php");
}

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


		<form action="crear.php" method="post" enctype="multipart/form-data" autocomplete="off" class="formu" style="margin-top: -50px;">
			<select class="" name="para" <?php if ($as['Tipo'] != 'Docente') { ?>required <?php } ?>>

				<option value="">Seleccione el Destinatario</option>
				<?php
				$emaild = $docente['Email'];
				$nombred = $docente['Nombre'];
				if ($emaild != $_SESSION['user']) {
				?>
					echo "<option value="<?php echo $emaild ?>"><?php echo $nombred ?></option> ";
					<?php
				}
				do {
					$email = $alumnos['Email'];
					$nombre = $alumnos['Nombre'];
					if ($email != $_SESSION['user']) {
					?>
						echo "<option value="<?php echo $email ?>"><?php echo $nombre ?></option> ";
				<?php					}
				} while ($alumnos = mysqli_fetch_assoc($resultado));
				?>

			</select name="para"><br><br>
			<input class="formu-input" type="text" name="asunto" placeholder="Asunto" required> <br><br>
			<textarea id="ckeditor" class="formu-input ckeditor" name="texto" placeholder="Agregar Nuevo Comentario" cols="30" rows="10" required></textarea>
			<br>
			<input type="file" class="formu-input" name="file" value="">
			<br>

			<input class="formu-button" type="submit" name="enviar" value="Enviar">
			<?php
			if ($as['Tipo'] == 'Docente') { ?>
				<input class="formu-button" type="submit" name="enviartodo" value="Enviar A Todos">
			<?php
			}
			?>
		</form>

	</div>


</body>

</html>