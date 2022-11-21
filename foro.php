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

if (isset($_REQUEST['tema'])) {
    $u = $_SESSION['user'];
    $c = $_SESSION['clave'];
    $t = $_REQUEST['tema'];
    mysqli_query($cont, "INSERT INTO temas VALUES(NULL,'$c','$u','$t',NULL)");
    header("location:foro.php");
}

$qtema = mysqli_query($cont, "SELECT * FROM temas WHERE clave ='" . $_SESSION['clave'] . "' ORDER BY  fecha ASC ");
$ntema = mysqli_num_rows($qtema);
$atema = mysqli_fetch_assoc($qtema);


$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);

$atipo = mysqli_fetch_assoc(mysqli_query($cont, "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'"));


if (isset($_REQUEST['e'])) {
    mysqli_query($cont, "DELETE FROM temas WHERE  idtema=" . $_REQUEST['e']);
    header("location:foro.php");
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

    <h1 style="margin-bottom: -50px;">Foro de Discusión</h1>
    <div class="menu">
        <h1><?php include('includes/menu.php') ?></h1>
    </div>
    <div class="tabla">

        <?php
        if ($atipo['Tipo'] == 'Docente') {
        ?>
            <form action="foro.php" method="post" autocomplete="off" class="formu" style="margin-top: -50px;">
                <textarea class="formu-input" name="tema" placeholder="Nuevo Tema" cols="30" rows="10" required></textarea>
                <input class="formu-button" type="submit" value="Agregar Tema">
            </form>
        <?php
        }
        ?>
        <table>
            <thead>
                <tr>
                    <th>Indice</th>
                    <th>Tema</th>
                    <th>Fecha </th>
                    <th>Numero de Comentarios</th>
                    <?php
                    if ($atipo['Tipo'] == 'Docente') {
                        echo "<th><span>Eliminar</span></th>";
                    } ?>
                    <th>Accion</th>


                </tr>
            </thead>
            <?php
            if ($ntema > 0) {
                $contar =  mysqli_num_rows(mysqli_query($cont, "SELECT * FROM comentario WHERE idtema =".$atema['idtema'])); 
                
                $i = 1;
                do {
                    echo "<tr>";
                    echo "<td> Tema" . $i . "</td>";
                    echo "<td>" . $atema['tema'] . "</td>";
                    echo "<td>" . $atema['fecha'] . "</td>";
                    echo "<td>" . $contar . "</td>";
                    if ($atipo['Tipo'] == 'Docente') {
                        echo "<td><a href='foro.php?ef=" . $atema['idtema'] . "'>Eliminar</a></td>";
                    }
                    echo "<td><a href='comentar.php?idtema=" . $atema['idtema'] . "'>Comentar en Foro</a>";
                    $i++;
                } while ($atema = mysqli_fetch_assoc($qtema));
            } else {

                echo "<tr>";
                echo "<td>Sin Foros de Discusion</td>";
                echo "</tr>";
            }
            

            ?>

            </script>
        </table>
    </div>

</body>

</html>
<?php

mysqli_free_result($qtema);
mysqli_free_result($resultado1);
mysqli_close($cont);

?>