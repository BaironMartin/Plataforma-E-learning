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


function generaPass()
{
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $longitudCadena = strlen($cadena);
    $pass = "";
    $longitudPass = 10;
    for ($i = 1; $i <= $longitudPass; $i++) {
        $pos = rand(0, $longitudCadena - 1);
        $pass .= substr($cadena, $pos, 1);
    }
    return $pass;
}

if (isset($_REQUEST['clase'])) {
    $name = $_REQUEST['clase'];
    $clave = generaPass();
    $u = $_SESSION['user'];
    $sql1 = ("INSERT INTO clase VALUES(NULL,'$name','$clave','$u',NULL)");
    mysqli_query($cont, $sql1);
    header("location:crearClase.php");
}

if (isset($_REQUEST['e'])) {
    $sql2 = ("DELETE FROM clase WHERE idclase=" . $_REQUEST['e']);
    mysqli_query($cont, $sql2);
}

$sql = ("SELECT* FROM clase WHERE usuario='" . $_SESSION['user'] . "'");
$resultado = mysqli_query($cont, $sql);
$n = mysqli_num_rows($resultado);
$a = mysqli_fetch_assoc($resultado);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" tipe="text/class" href="css/">
    <link rel="stylesheet" tipe="text/class" href="css/estilos.css?v=<?php echo (rand()); ?>">
    <title>Plataforma E-LEARNING Gestionar Clase</title>
    <link rel="icon" href="img/ico/free bsd.ico">
</head>

<body>

    <div class="pegajoso">
        <h2 class="titulo1">PREPARATORIA RANCHO HUMILDE</h2>
        <div class="container2">
            <a class=" editar" href="inicio.php">Inicio</a>
            <a class=" cerrar" href="inicio.php?cerrar=1">Cerrar Secion</a>
        </div>
    </div>
    <h1>Gestionar Clases</h1>

    <form action="crearClase.php" method="post" autocomplete="off" class="formu">
        <input class="formu-input" type="text" name="clase" placeholder="Nombre de Clase" required> <br>
        <input class="formu-button" type="submit" value="Crear Clase">
    </form>
    <hr>
    <h1>Lista de Clases</h1>
    <div class="tabla1">
        <table>
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Clave</th>
                <th>Fecha</th>
                <th>Eliminar</th>
                <th>Ver Plan</th>
            </tr>
            </thead>
            
            <?php

            if ($n > 0) {
                do {
                    echo "<tr><td>" . $a['nombre'] . "</td>";
                    echo "<td>" . $a['clave'] . "</td>";
                    echo "<td>" . $a['fecha'] . "</td>";
                    echo "<td><a href='crearClase.php?e=" . $a['idclase'] . "'>Eliminar</a></td>";
                    echo "<td><a href='plan.php?clave=".$a['clave']."'>Ver Plan</a></td></tr>";
                } while ($a = mysqli_fetch_assoc($resultado));
            } else {
                echo "<tr><td>No hay clases creadas</td></tr>";
            }

            ?>

        </table>
    </div>



</body>

</html>