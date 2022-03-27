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
$sql = "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'";
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);


$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);

$sql = "SELECT * FROM mensaje WHERE para='" . $_SESSION['user'] ."' AND clave ='". $_SESSION['clave'] . "' and leido IS NULL";
$res = mysqli_query($cont, $sql);
$tot = mysqli_num_rows($res);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" tipe="text/class" href="css/">
    <link rel="stylesheet" tipe="text/class" href="css/estilos.css?v=<?php echo (rand()); ?>">
    <title>Plataforma E-LEARNING Email</title>
    <link rel="icon" href="img/ico/free bsd.ico">
</head>

<body>
    <?php
    include('includes/header.php');


    ?>
    <?php
    echo ("<h1 > "  . $a1['nombre'] . "</h1><br>");

    ?>
    <hr>

    <h1 style="margin-bottom: -50px;">Calificaciones</h1>
    <div class="menu">
    <h1><?php include('includes/menu.php') ?></h1>
    </div>
    <div class="tabla">
        <header>
            <input id="btn-menu" type="checkbox">
            <label for ="btn-menu"><img src="img/588a64e0d06f6719692a2d10.png" class="imgg"></label>
            <nav class="menuh">
                <ul>
                    <li><a href="listar.php">mensajes recibidos </a> </li>
                    <li><a href="enviados.php">mensajes enviados</a> </li>
                    <li><a href="crear.php">Crear mensajes</a></li>
                </ul>
            </nav>
        </header>
        <br>
        <p style="color:white">Hola <strong><?php echo $a['Nombre']; ?></strong>, Usted tiene <?php echo $tot ?> mensajes sin leer.</p>




    </div>

</body>

</html>