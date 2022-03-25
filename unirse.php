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

//$sql = ("SELECT * FROM usuarios WHERE Email='" . $_SESSION['user'] . "'");
//$user = mysqli_query($cont, $sql);
//$a = mysqli_fetch_assoc($user);

if (isset($_REQUEST['clave'])) {
    $sql2 = ("SELECT * FROM clase WHERE clave='" . $_REQUEST['clave'] . "'");
    $resulado1 = mysqli_query($cont, $sql2);
    $n = mysqli_num_rows($resulado1);
    if ($n > 0) {
        $sql3 = ("SELECT * FROM misclases WHERE clave='" . $_REQUEST['clave'] . "' AND usuario='" . $_SESSION['user'] . "'");
        $resultado2 = mysqli_query($cont, $sql3);
        $nc = mysqli_num_rows($resultado2);

        if ($nc == 0) {
            $u = $_SESSION["user"];
            $c = $_REQUEST['clave'];
            $sql = ("INSERT INTO misclases VALUES(NULL,'$u','$c')");
            mysqli_query($cont, $sql);
            header("location:unirse.php");
        } else {
            echo '<script type="text/javascript">alert("Ya te Uniste a esta clase ");
        window.location.href="unirse.php";
        </script>';
        }
    } else {
        echo '<script type="text/javascript">alert("La clase no Existe");
        window.location.href="unirse.php";
        </script>';
    }
}
$sql = ("SELECT clase.nombre, misclases.idmiclase, clase.clave FROM clase,misclases WHERE clase.clave = misclases.clave and misclases.usuario= '" . $_SESSION['user'] . "'");
$resultado = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado);
$a = mysqli_fetch_assoc($resultado);


if (isset($_REQUEST['e'])) {
    $sql2 = ("DELETE FROM misclases WHERE idmiclase=" . $_REQUEST['e']);
    mysqli_query($cont, $sql2);
}
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
    <link rel="icon" href="/img/ico/free bsd.ico">
</head>

<body>

    <div class="pegajoso">
        <h2 class="titulo1">PREPARATORIA RANCHO HUMILDE</h2>
        <div class="container2">
            <a class=" editar" href="inicio.php">Inicio</a>
            <a class=" cerrar" href="inicio.php?cerrar=1">Cerrar Secion</a>
        </div>
    </div>
    <h1>Unirse a Clases</h1>

    <form action="unirse.php" method="post" autocomplete="off" class="formu">
        <input class="formu-input" type="text" name="clave" placeholder="Clave de Clase" required> <br>
        <input class="formu-button" type="submit" value="Unirse a Clase">
    </form>
    <hr>
    <h1>Mis Clases</h1>
    <div class="containerclasemenu"></div>

    <div class="tabla1" >
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Eliminar</th>
                    <th>Ver Plan</th>
                </tr>
            </thead>

            <?php

            if ($n1 > 0) {
                do {
                    echo "<tr><td>" . $a['nombre'] . "</td>";
                    echo "<td><a href='unirse.php?e=" . $a['idmiclase'] . "'>Eliminar</a></td>";
                    echo "<td><a href='plan.php?clave=" . $a['clave'] . "' target='_blank'>Ver Plan</a></td></tr>";
                } while ($a = mysqli_fetch_assoc($resultado));
            } else {
                echo "<tr><td>No hay clases creadas</td></tr>";
            }

            ?>

        </table>
    </div>
    </div>




</body>

</html>