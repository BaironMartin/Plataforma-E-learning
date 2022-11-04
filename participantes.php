<?php
include('includes/conectar.php');
session_start();
if (!isset($_SESSION['user'])) {
    header("location:index.php");

}
if (!isset($_SESSION['clave'])) {
    header("Location: 404.php");
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" tipe="text/class" href="css/">
    <link rel="stylesheet" tipe="text/class" href="css/estilos.css?v=<?php echo (rand()); ?>">
    <title>Plataforma E-LEARNING Participantes</title>
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
    <h1 style="margin-bottom: -50px;">Lista de Participantes</h1>

    <div class="menu">
        <h1><?php include('includes/menu.php') ?></h1>
    </div>
    <div class="tabla">
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>foto</th>
                    <th>Nombre</th>
                    <th>Email</th>
                </tr>
            </thead>

            <?php
            echo "<tr><td>" . $docente['Tipo'] . "</td> ";
            echo "<td><img src ='archivos/" . $docente['Email'] . "" . $docente['Foto'] . "' class='imgg'></td>";
            echo "<td>" . $docente['Nombre'] . "</td>";
            echo "<td>" . $docente['Email'] . "</td></tr>";

            if ($n > 0) {
                do {
                    echo "<tr><td>" . $alumnos['Tipo'] . "</td> ";
                    echo "<td><img src ='archivos/" . $alumnos['Email'] . "" . $alumnos['Foto'] . "' class='imgg'></td>";
                    echo "<td>" . $alumnos['Nombre'] . "</td>";
                    echo "<td>" . $alumnos['Email'] . "</td></tr>";
                } while ($alumnos = mysqli_fetch_assoc($resultado));
            } else {
                echo "<tr><td>No hay Alumnos Inscritos</td></tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>