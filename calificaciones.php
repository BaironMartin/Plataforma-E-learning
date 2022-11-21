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

include('includes/encabezado.php')
?>

<!DOCTYPE html>
<html lang="en">



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
        <?php
        if ($atipo['Tipo'] == 'Docente') { ?>
            <table>
                <thead>
                    <tr>
                        <th>foto</th>
                        <th>Nombre</th>
                        <th>Promedio</th>
                    </tr>
                </thead>

                <?php
                if ($n > 0) {
                    do {
                        echo "<td><img src ='archivos/" . $alumnos['Email'] . "" . $alumnos['Foto'] . "' class='imgg'></td>";
                        echo "<td>" . $alumnos['Nombre'] . "</td>";
                        $promedio = mysqli_fetch_assoc(mysqli_query($cont, "SELECT AVG(evaluacion) as promedio FROM tareas WHERE clave='" . $_SESSION['clave'] . "' AND usuario ='" . $alumnos['Email'] . "'"));
                        echo "<td>" . $promedio['promedio'] . "</td></tr>";
                    } while ($alumnos = mysqli_fetch_assoc($resultado));
                } else {
                    echo "<tr><td>No hay Alumnos Inscritos</td></tr>";
                }
                ?>
            </table>

            <form action="reporte.php" method="post" autocomplete="off" class="formu">
                <input class="formu-button" type="submit" value="Generar Reporte PDF">
            </form>


        <?php
        } else {

            $cal = mysqli_query($cont, "SELECT* FROM tareas,plan WHERE tareas.idplan=plan.idplan and tareas.usuario='" . $_SESSION['user'] . "'  AND tareas.clave='" . $_SESSION['clave'] . "'");
            $ncal = mysqli_num_rows($cal);
            $acal = mysqli_fetch_assoc($cal);
        ?>

            <table>
                <thead>
                    <tr>
                        <th>Num</th>
                        <th>Nombre Tarea</th>
                        <th>Calificacion</th>
                    </tr>
                </thead>
                <?php
                if ($ncal > 0) {

                    $i = 0;
                    $suma = 0;
                    do {
                        echo "<td>Tarea" . $i + 1 . "</td>";
                        echo "<td>" . $acal['texto'] . "</td>";
                        if ($acal['evaluacion'] != "") {
                            echo "<td>" . $acal['evaluacion'] . "</td></tr>";
                            $i++;
                        } else {
                            echo "<td>Por Calificar</td></tr>";
                            $suma += 10;
                            $i += 1;
                        }


                        $suma += $acal['evaluacion'];
                    } while ($acal = mysqli_fetch_assoc($cal));
                } else {
                    echo "<tr><td>No hay Tareas Entregadas</td></tr>";
                }
                ?>
            </table>
            <?php

            if ($ncal > 0) { ?>
                <div class='contenedor_interno'>
                    <artricle>
                        <?php echo "<h3> Promedio " . round($suma / $i) . "</h3>"; ?>
                    </artricle>
                </div>

                <form action="reporteAlumnos.php" method="post" autocomplete="off" class="formu">
                <input class="formu-button" type="submit" value="Generar Reporte PDF">
            </form>

        <?php
            }
            mysqli_free_result($cal);
        } 
?>

            
    </div>

</body>

</html>
<?php


mysqli_free_result($resultado1);
mysqli_free_result($resultado);
mysqli_close($cont);

?>