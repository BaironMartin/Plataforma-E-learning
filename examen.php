<?php
date_default_timezone_set('America/Bogota');
include('includes/conectar.php');
include('includes/secionesUser.php');

if (!isset($_SESSION['clave'])) {
    header("Location: error.php");
}
if (isset($_REQUEST['idexamen'])) {
    $_SESSION['idexamen'] = $_REQUEST['idexamen'];
}
if (!isset($_SESSION['clave'])) {
    header("Location: error.php");
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

function generaBandera()
{
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $longitudCadena = strlen($cadena);
    $pass = "";
    $longitudPass = 20;
    for ($i = 1; $i <= $longitudPass; $i++) {
        $pos = rand(0, $longitudCadena - 1);
        $pass .= substr($cadena, $pos, 1);
    }
    return $pass;
}

$sqlTipo = "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'";
$resultadoTipo = mysqli_query($cont, $sqlTipo);
$aTipo = mysqli_fetch_assoc($resultadoTipo);

$sql = ("SELECT* FROM clase WHERE clave='" . $_SESSION['clave'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);



if (isset($_REQUEST['exam'])) {
    $name = $_REQUEST['exam'];
    $imagen = $_REQUEST['imagen'];
    $fe = $_REQUEST['fe'];
    $clave = $_SESSION['clave'];
    $u = $_SESSION['user'];
    $ex = 'Examen';
    $p = $_REQUEST['periodo'];
    $bandera = generaBandera();

    $sqltar = ("SELECT* FROM misclases, usuarios WHERE misclases.usuario=usuarios.Email AND misclases.clave='" . $_SESSION['clave'] . "'");
    $resultadotar = mysqli_query($cont, $sqltar);
    $ntar = mysqli_num_rows($resultadotar);
    $alumnostar = mysqli_fetch_assoc($resultadotar);


    $sql1 = ("INSERT INTO examen VALUES(NULL,'$name','$clave','$u','$imagen','$p','$fe ',NULL,'$bandera')");
    $sql44 = ("INSERT INTO plan VALUES(NULL,'$u','$clave','$name','$ex','$p',NULL,'$fe','$bandera')");

    mysqli_query($cont, $sql1);
    mysqli_query($cont, $sql44);

    $sqlban = ("SELECT* FROM plan WHERE bandera='" . $bandera . "'");
    $resultadoban = mysqli_query($cont, $sqlban);
    $nban = mysqli_num_rows($resultadoban);
    $aban = mysqli_fetch_assoc($resultadoban);

    $id = $aban['idplan'];

    do {
        $al = $alumnostar['Email'];
        mysqli_query($cont, "INSERT INTO tareas VALUE (NULL,'$name','$al','$clave',NULL,'$ex','$p',0,'$id')");
    } while ($alumnostar = mysqli_fetch_assoc($resultadotar));
    header("location:examen.php");
}

$sql = ("SELECT* FROM examen WHERE clave='" . $_SESSION['clave'] . "'");
$resultado = mysqli_query($cont, $sql);
$n = mysqli_num_rows($resultado);
$a = mysqli_fetch_assoc($resultado);

if (isset($_REQUEST['e'])) {

    $sql = ("SELECT* FROM examen WHERE id='" . $_REQUEST['e'] . "'");
    $resultado4 = mysqli_query($cont, $sql);
    $n4 = mysqli_num_rows($resultado4);
    $a4 = mysqli_fetch_assoc($resultado4);

    $sql = ("SELECT* FROM plan WHERE bandera='" . $a4['bandera'] . "'");
    $resultado2 = mysqli_query($cont, $sql);
    $n2 = mysqli_num_rows($resultado2);
    $a2 = mysqli_fetch_assoc($resultado2);
    $id = intval($a2['idplan']);

    $sqpreg = ("SELECT* FROM preguntas WHERE idp='" . $_REQUEST['e'] . "'");
    $resultadopreg = mysqli_query($cont, $sqpreg);
    $npreg = mysqli_num_rows($resultadopreg);
    $apreg = mysqli_fetch_assoc($resultadopreg);

    $sqtar = ("SELECT* FROM tareas WHERE idplan='" . $id . "'");
    $resultadotar = mysqli_query($cont, $sqtar);
    $ntar = mysqli_num_rows($resultadotar);
    $atar = mysqli_fetch_assoc($resultadotar);


    do {
        $idt = ($atar['idtarea']);
        mysqli_query($cont, "DELETE FROM tareas WHERE  idtarea=" . $idt);
    } while ($atar = mysqli_fetch_assoc($resultadotar));

    do {
        $ide = ($apreg['id']);
        mysqli_query($cont, "DELETE FROM preguntas WHERE  id=" . $ide);
    } while ($apreg = mysqli_fetch_assoc($resultadopreg));

    mysqli_query($cont, ("DELETE FROM examen WHERE id=" . $_REQUEST['e']));
    mysqli_query($cont, ("DELETE FROM `plan` WHERE `plan`.`idplan` = $id"));
    header("location:examen.php");
}

if (isset($_REQUEST['tareaone'])) {

    $sql = ("SELECT* FROM plan WHERE idplan='" . $_SESSION['idexamen'] . "'");
    $resultadoplan = mysqli_query($cont, $sql);
    $nplan = mysqli_num_rows($resultadoplan);
    $aplan = mysqli_fetch_assoc($resultadoplan);
    $nam = $aplan['titulo'];
    $ex = 'Examen';
    $ids = $_SESSION['idexamen'];
    $iper = $aplan['periodo'];

    $u = $_SESSION['user'];
    $c = $_SESSION['clave'];
    $idt = $_SESSION['idexamen'];


    mysqli_query($cont, "INSERT INTO tareas VALUE (NULL,'$nam','$u','$c',NULL,'$ex','$iper',0,'$ids')");
    header("location:examen.php");
}

include('includes/encabezado.php');
?>

<body>

    <?php
    include('includes/header.php');
    ?>


    <br>
    <?php
    echo ("<h1 style='margin-top: -20px'>" . $a1['nombre'] . "</h1>");

    if ($aTipo['Tipo'] == 'Docente') {



    ?>
        <h1>Examen</h1>

        <form action="examen.php" method="post" autocomplete="off" class="formu">
            <input class="formu-input" type="text" name="exam" placeholder="Nombre del examen" required>
            <input class="formu-input" type="text" name="imagen" placeholder="enlace de la imagen" required>
            <input class="formu-input" type="date" name="fe" placeholder="Fecha De Entrega" required>
            <select class="" name='periodo' id="periodo" required>
                <option value="">Periodo</option>
                <option value="I">Primer Periodo</option>
                <option value="II">Segundo Periodo</option>
                <option value="III">Tercer Periodo</option>
                <option value="IV">Cuarto Periodo</option>
            </select>
            <input class="formu-button" type="submit" value="Crear Examen">

        </form>
        <hr>

    <?php
    }

    ?>
    <h1>Lista de Examenes</h1>

    <div class="menu">
        <h1><?php include('includes/menu.php') ?></h1>
    </div>
    <div class="tabla">
        <?php
        if (($aTipo['Tipo'] == 'Docente')) {
            if ($n > 0) {
                echo "<div class='page-content'>";
                do {
                    $name = $a['nombre'];
                    echo "<div class='product-container'>";
                    echo "<h3>" . substr($name, 0, 20), '...' . "</h3><br>";
                    echo "<img src='" . $a['imagen'] . "' />";
                    echo "<div class='container2'>";
                    echo "<p>Clave Unica: " . $a['clave']  . "</p><br>";
                    echo "<p>Fecha De Cierre: " . $a['cierre']  . "</p><br>";
                    if ($aTipo['Tipo'] == "Docente") {
                        echo "<button ><a class='cerrar' href='examen.php?e=" . $a['id'] . "'>Eliminar</a></button>";
                    }
                    echo "<button ><a class='editar' href='preguntas.php?example=" . $a['id'] . "'>Ver Examen</a></button>";
                    echo "</div><br>";
                    echo "</div>";
                } while ($a = mysqli_fetch_assoc($resultado));
            } else {
                echo "<div class='contenedor_interno'>";
                echo "<artricle>";
                echo "<h3>Sin Examenes</h3>";
                echo "</artricle>";
                echo "</div>";
            }
        } else {

            $sqltt = ("SELECT* FROM plan WHERE idplan = '" . $_SESSION['idexamen'] . "'");
            $resultadott = mysqli_query($cont, $sqltt);
            $ntt = mysqli_num_rows($resultadott);
            $att = mysqli_fetch_assoc($resultadott);

            $vt = $att['bandera'];


            $sql = ("SELECT* FROM examen WHERE clave='" . $_SESSION['clave'] . "' AND bandera = '" . $vt . "'");
            $resultado = mysqli_query($cont, $sql);
            $n = mysqli_num_rows($resultado);
            $a = mysqli_fetch_assoc($resultado);

            $sql = ("SELECT* FROM tareas WHERE usuario='" . $_SESSION['user'] . "'AND idplan='" . $_SESSION['idexamen'] . "'");
            $resultadoexistar = mysqli_query($cont, $sql);
            $nexistar = mysqli_num_rows($resultadoexistar);
            $aexistar = mysqli_fetch_assoc($resultadoexistar);
            if ($nexistar > 0 && ($aTipo['Tipo'] != 'Docente')) {

                if ($n > 0) {
                    echo "<h3 style='background:white;color:red;text-align:center;'>⚠️Advertencia⚠️<br>
                    recuerde que una vez ingresado al examen tendrá como tiempo máximo 15 minutos<br>En caso de que este tiempo expiden el formulario se cerrará automáticamente.
                    </h3>";
                    echo "<div class='page-content'>";

                    do {
                        $fecha_actual = (date("Y-m-d", time()));
                        $fecha_entrada = (date($a['cierre']));
                        $name = $a['nombre'];
                        echo "<div class='product-container'>";
                        echo "<h3>" . substr($name, 0, 20), '...' . "</h3><br>";
                        echo "<img src='" . $a['imagen'] . "' />";
                        echo "<div class='container2'>";
                        echo "<p>Clave Unica: " . $a['clave']  . "</p><br>";
                        echo "<p>Fecha De Creacion: " . $a['cierre']  . "</p><br>";
                        if (($fecha_actual > $fecha_entrada)) {
                            echo "<button ><a class='editar'>Examen cerrado</a></button>";
                        } else {
                            echo "<button ><a class='editar' href='preguntas.php?example=" . $a['id'] . "'>Ver Examen</a></button>";
                        }
                        echo "</div><br>";
                        echo "</div>";
                    } while ($a = mysqli_fetch_assoc($resultado));
                } else {
                    echo "<div class='contenedor_interno'>";
                    echo "<artricle>";
                    echo "<h3>Sin Examenes</h3>";
                    echo "</artricle>";
                    echo "</div>";
                }
            } else { ?>
                <h3>Por motivos ajenos a la institución usted ingresó luego del inicio de clases por lo que es necesario que se inscriba de manera manual a esta tarea.</h3>

                <form action="examen.php" method="post" autocomplete="off" class="formu">
                    <input class="formu-button" type="submit" name="tareaone" value="ingresar a tarea">
                </form>
        <?php
            }
        }

        echo "</div>";

        ?>

    </div>
</body>

</html>
<?php

mysqli_free_result($resultado1);
mysqli_free_result($resultado);

mysqli_free_result($resultadoTipo);
mysqli_close($cont);

?>