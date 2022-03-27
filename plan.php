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

if (isset($_REQUEST['clave']) && !empty($_REQUEST['clave'])) {
    $_SESSION['clave'] = $_REQUEST['clave'];
}

$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);


if (isset($_REQUEST['fe']) && !isset($_REQUEST['modificar'])) {
    $u = $_SESSION['user'];
    $c = $_SESSION['clave'];
    $t = $_REQUEST['titulo'];
    $tx = $_REQUEST['texto'];
    $fe = $_REQUEST['fe'];
    $sql = ("INSERT INTO plan VALUES(NULL,'$u','$c','$t','$tx',NULL,'$fe')");
    mysqli_query($cont, $sql);
    header("location:plan.php");
}
if (isset($_REQUEST['modificar'])) {

    $u = $_SESSION['user'];
    $c = $_SESSION['clave'];
    $t = $_REQUEST['titulo'];
    $tx = $_REQUEST['texto'];
    $fe = $_REQUEST['fe'];
    $sql = ("UPDATE plan SET titulo = '$t', texto = '$tx', fechaentrega = '$fe' WHERE idplan =  " . $_REQUEST['modificar']);
    mysqli_query($cont, $sql);
    header("location:plan.php");
}

if (isset($_REQUEST['e'])) {
    $sql2 = ("DELETE FROM plan WHERE idplan=" . $_REQUEST['e']);
    mysqli_query($cont, $sql2);
}

$sql = ("SELECT* FROM plan WHERE clave='" . $_SESSION['clave'] . "' ORDER BY fecha DESC");
$resultado = mysqli_query($cont, $sql);
$n = mysqli_num_rows($resultado);
$a = mysqli_fetch_assoc($resultado);


if (isset($_REQUEST['ma'])) {
    $sql3 = ("SELECT* FROM plan WHERE idplan=" . $_REQUEST['ma']);
    $mn = mysqli_query($cont, $sql3);
    $mplan = mysqli_fetch_assoc($mn);
}

$sql = "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'";
$resultad = mysqli_query($cont, $sql);
$as = mysqli_fetch_assoc($resultad);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" tipe="text/class" href="css/normalize.css?v=<?php echo (rand()); ?>">
    <link rel="stylesheet" tipe="text/class" href="css/estilos.css?v=<?php echo (rand()); ?>">
    <title>Plataforma E-LEARNING Plan de Estudio</title>
    <link rel="icon" href="img/ico/free bsd.ico">
    <script type="text/javascript" src="js/observacionesbasicas/ckeditor/ckeditor.js"></script>
    
</head>

<body>

    <div class="pegajoso">
        <h2 class="titulo1">PREPARATORIA RANCHO HUMILDE</h2>
        <div class="container2">

            <?php
            if ($as['Tipo'] == 'Docente') {
                echo "<a class=' editar' href='crearClase.php'>Gestionar Clase</a>";
            } else 

            ?>

            <a class=" cerrar" href="inicio.php?cerrar=1">Cerrar Secion</a>
        </div>
    </div>

    
    <br>
    <?php
    echo ("<h1 style='margin-top: -20px'>" . $a1['nombre'] . "</h1>");

    if ($as['Tipo'] == 'Docente') {

    ?>
    <h1>Agregar Actividad</h1>
        <form action="plan.php" method="post" autocomplete="off" class="formu">
            <input class="formu-input" type="text" name="titulo" placeholder="Titulo" <?php if (isset($_REQUEST['ma'])) {echo "value='" . $mplan['titulo'] . "'";} ?> required> <br><br>
            <textarea id="ckeditor" class="formu-input ckeditor" type="text" name="texto" placeholder="Texto" cols='30' rows="10" required><?php if (isset($_REQUEST['ma'])) {echo $mplan['texto'];} ?></textarea><br>
            <input class="formu-input" type="date" name="fe" placeholder="Fecha De Entrega" <?php if (isset($_REQUEST['ma'])) { echo "value='" . $mplan['fechaentrega'] . "'"; } ?> required> <br>
            <?php if (isset($_REQUEST['ma'])) {
                echo "<input type='hidden' name ='modificar' value='" . $_REQUEST['ma'] . "'> ";
            } ?>
            <input class="formu-button" type="submit" <?php if (isset($_REQUEST['ma'])) {echo "value='Guardar'";} else {echo "value='Agregar'";} ?>>
        </form>
    <?php

    }

    ?>


    <hr>
    <h1 style="margin-bottom: -50px">Lista de Actividades</h1>

    <div class="menu">
<h1><?php include('includes/menu.php')?></h1>
    </div>
    <div class="tabla">
        <table>
            <thead>
                <tr>
                    <th>Titulo</th>
                    <th>Observacion</th>
                    <th>Fecha</th>
                    <th>Fecha de Entrega</th>
                    <?php
                    if ($as['Tipo'] == 'Docente') { ?>
                        <th>Eliminar</th>
                        <th>Modificar</th>
                    <?php
                    }
                    ?>


                </tr>
            </thead>

            <?php

            if ($n > 0) {
                do {
                    echo "<tr><td>" . $a['titulo'] . "</td>";
                    echo "<td>" . $a['texto'] . "</td>";
                    echo "<td>" . $a['fecha'] . "</td>";
                    echo "<td>" . $a['fechaentrega'] . "</td>";
                    if ($as['Tipo'] == 'Docente') {
                        echo "<td><a href='plan.php?e=" . $a['idplan'] . "'>Eliminar</a></td>";
                        echo "<td><a href='plan.php?ma=" . $a['idplan'] . "'>Modificar</a></td>";
                    }
                } while ($a = mysqli_fetch_assoc($resultado));
            } else {
                echo "<tr><td>No hay Actividades Agregadas</td></tr>";
            }

            ?>

        </table>
    </div>
    

</body>


</html>