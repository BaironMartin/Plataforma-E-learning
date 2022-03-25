<?php
include('includes/conectar.php');
session_start();
if (!isset($_SESSION['user'])) {
    header("location:index.php");
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);

$sql = ("SELECT* FROM clase, usuarios WHERE clase.usuario=usuarios.Email AND clase.clave='" . $_SESSION['clave'] . "'");
$resultado = mysqli_query($cont, $sql);
$docente = mysqli_fetch_assoc($resultado);

$sql = ("SELECT* FROM misclases, usuarios WHERE misclases.usuario=usuarios.Email AND misclases.clave='" . $_SESSION['clave'] . "'");
$resultado = mysqli_query($cont, $sql);
$n = mysqli_num_rows($resultado);
$alumnos = mysqli_fetch_assoc($resultado);

?>



Menu: <a href="listar.php">Ver mensajes</a> | <a href="crear.php">Crear mensajes</a> | <a href="cerrar.php">Cerrar sesion</a><br /><br />
Hola <strong><?php echo $_SESSION['user'];?></strong>, Usted tiene <?php echo $tot?> mensajes sin leer.

