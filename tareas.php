<?php
include('includes/conectar.php');
include('includes/secionesUser.php');

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}
if (!isset($_SESSION['clave'])) {
    header("Location: error.php");
}

$qtareas = mysqli_query($cont, "SELECT * FROM plan WHERE clave ='" . $_SESSION['clave'] . "' ORDER BY  fecha ASC ");
$ntares = mysqli_num_rows($qtareas);
$atareas = mysqli_fetch_assoc($qtareas);


$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);

$sqlTipo = "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'";
$resultadoTipo = mysqli_query($cont, $sqlTipo);
$aTipo = mysqli_fetch_assoc($resultadoTipo);




include('includes/encabezado.php');
?>



<body>
    <?php
    include('includes/header.php');
    ?>
    <?php
    echo ("<h1 > "  . $a1['nombre'] . "</h1><br>");

    ?>
    <hr>

    <h1 style="margin-bottom: -50px;">Entrega de Actividades</h1>
    <div class="menu">
        <h1><?php include('includes/menu.php') ?></h1>
    </div>
    <div class="tabla">

        <table>
            <thead>
                <tr>
                    <th>Titulo</th>
                    <th>Fecha Entrega</th>
                    <th>Accion</th>


                </tr>
            </thead>
            <?php
            if ($ntares > 0) {
                do {
                    echo "<tr>";
                    echo "<td>" . $atareas['titulo'] . "</td>";
                    echo "<td>" . $atareas['fechaentrega'] . "</td>";
                    $text=trim(($atareas['texto']));
                    // if ($text == "Examen" || $text == "<p>Examen</p>"){

                    //     echo "<td><a href='examen.php?idexamen=" . $atareas['idplan'] . "'>Resolver Examen</a>";                        
                    // } 
                    // else {
                        echo "<td><a href='entregatarea.php?tarea=" . $atareas['idplan'] . "'>Entregar Actividad</a>";
                    //}
                } while ($atareas = mysqli_fetch_assoc($qtareas));
            } else {

                echo "<tr>";
                echo "<td>Sin Actividaes Pendientes</td>";
                echo "</tr>";
            }

            ?>

            </script>
        </table>
    </div>

</body>

</html>
<?php

mysqli_free_result($qtareas);
mysqli_free_result($resultado1);
mysqli_close($cont);

?>