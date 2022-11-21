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

$atipo = mysqli_fetch_assoc(mysqli_query($cont, "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'"));

if (isset($_REQUEST['e'])) {
    echo $_REQUEST['e'];
    $sql2 = ("DELETE FROM misclases WHERE idmiclase=" . $_REQUEST['e']);
    mysqli_query($cont, $sql2);
    header("location:participantes.php");
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
                    <?php if ($atipo['Tipo'] == 'Docente') {
                        echo "<th>Eliminar</th>";
                    } ?>
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
                    echo "<td>" . $alumnos['Email'] . "</td>";
                    if ($atipo['Tipo'] == 'Docente') {
                        echo "<td><a href='participantes.php?e=" . $alumnos['idmiclase'] . "'>Eliminar</a></td></tr>";
                    };
                } while ($alumnos = mysqli_fetch_assoc($resultado));
            } else {
                echo "<tr><td>No hay Alumnos Inscritos</td></tr>";
            }
            ?>
        </table>
    </div>
</body>


</html>
<?php

mysqli_free_result($resultado1);
mysqli_free_result($resultado);
mysqli_close($cont);

?>