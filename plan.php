<?php
include('includes/conectar.php');
include('includes/secionesUser.php');


if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

if (isset($_REQUEST['clave']) && !empty($_REQUEST['clave'])) {
    $_SESSION['clave'] = $_REQUEST['clave'];
}
if (!isset($_SESSION['clave'])) {
    header("Location: error.php");
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
    $p = $_REQUEST['periodo'];
    $bandera = generaBandera();


    $sql = ("INSERT INTO plan VALUES(NULL,'$u','$c','$t','$tx','$p',NULL,'$fe','$bandera')");
    mysqli_query($cont, $sql);

    $sqltar = ("SELECT* FROM misclases, usuarios WHERE misclases.usuario=usuarios.Email AND misclases.clave='" . $_SESSION['clave'] . "'");
    $resultadotar = mysqli_query($cont, $sqltar);
    $ntar = mysqli_num_rows($resultadotar);
    $alumnostar = mysqli_fetch_assoc($resultadotar);

    $sqlban = ("SELECT* FROM plan WHERE bandera='" . $bandera . "'");
    $resultadoban = mysqli_query($cont, $sqlban);
    $nban = mysqli_num_rows($resultadoban);
    $aban = mysqli_fetch_assoc($resultadoban);

    $id = $aban['idplan'];

    if ($ntar > 0) {
        do {
            $al = $alumnostar['Email'];
            var_dump($al);
            mysqli_query($cont, "INSERT INTO tareas VALUE (NULL,'','$al','$c',NULL,'','$p',0,'$id')");
        } while ($alumnostar = mysqli_fetch_assoc($resultadotar));
    }

    header("location:plan.php");
}
if (isset($_REQUEST['modificar'])) {

    $u = $_SESSION['user'];
    $c = $_SESSION['clave'];
    $t = $_REQUEST['titulo'];
    $tx = $_REQUEST['texto'];
    $fe = $_REQUEST['fe'];
    $p = $_REQUEST['periodo'];

    $sql = ("SELECT* FROM plan WHERE idplan='" . $_REQUEST['modificar'] . "'");
    $resultadoplan = mysqli_query($cont, $sql);
    $nplan = mysqli_num_rows($resultadoplan);
    $aplan = mysqli_fetch_assoc($resultadoplan);
    $ban = $aplan['bandera'];

    $sql = ("SELECT* FROM examen WHERE bandera='" . $ban . "'");
    $resultado4 = mysqli_query($cont, $sql);
    $n4 = mysqli_num_rows($resultado4);
    $a4 = mysqli_fetch_assoc($resultado4);
    $idexamen = $a4['id'];

 
    $sql = ("UPDATE plan SET titulo = '$t', texto = '$tx', fechaentrega = '$fe',  periodo='$p'  WHERE idplan =  " . $_REQUEST['modificar']);
    mysqli_query($cont, $sql);
    mysqli_query($cont, "UPDATE `examen` SET `cierre`='$fe' WHERE id =". $idexamen);
    header("location:plan.php");
}

if (isset($_REQUEST['e'])) {


    $sql = ("SELECT* FROM plan WHERE idplan='" . $_REQUEST['e'] . "'");
    $resultadoplan = mysqli_query($cont, $sql);
    $nplan = mysqli_num_rows($resultadoplan);
    $aplan = mysqli_fetch_assoc($resultadoplan);
    $ban = $aplan['bandera'];

    $sql = ("SELECT* FROM examen WHERE bandera='" . $ban . "'");
    $resultado4 = mysqli_query($cont, $sql);
    $n4 = mysqli_num_rows($resultado4);
    $a4 = mysqli_fetch_assoc($resultado4);
    $idexamen = $a4['id'];

    $sqtar = ("SELECT* FROM tareas WHERE idplan='" . $_REQUEST['e'] . "'");
    $resultadotar = mysqli_query($cont, $sqtar);
    $ntar = mysqli_num_rows($resultadotar);
    $atar = mysqli_fetch_assoc($resultadotar);

    mysqli_query($cont, ("DELETE FROM examen WHERE id=" . $idexamen));

    do {
        $idt = ($atar['idtarea']);
        mysqli_query($cont, "DELETE FROM tareas WHERE  idtarea=" . $idt);
    } while ($atar = mysqli_fetch_assoc($resultadotar));


    mysqli_query($cont, ("DELETE FROM plan WHERE idplan=" . $_REQUEST['e']));
    header("location:plan.php");
}

$sql = ("SELECT* FROM plan WHERE clave='" . $_SESSION['clave'] . "' ORDER BY fechaentrega DESC");
$resultado = mysqli_query($cont, $sql);
$n = mysqli_num_rows($resultado);
$a = mysqli_fetch_assoc($resultado);


if (isset($_REQUEST['ma'])) {
    $sql3 = ("SELECT* FROM plan WHERE idplan=" . $_REQUEST['ma']);
    $mn = mysqli_query($cont, $sql3);
    $mplan = mysqli_fetch_assoc($mn);
    mysqli_free_result($mn);
}

$sql = "SELECT * FROM usuarios WHERE Email ='" . $_SESSION['user'] . "'";
$resultad = mysqli_query($cont, $sql);
$as = mysqli_fetch_assoc($resultad);
include('includes/encabezado.php')

?>

<body>

    <?php
    include('includes/header.php');
    ?>


    <br>
    <?php
    echo ("<h1 style='margin-top: -20px'>" . $a1['nombre'] . "</h1>");

    if ($as['Tipo'] == 'Docente') {

    ?>
        <h1>Agregar Actividad</h1>
        <form action="plan.php" method="post" autocomplete="off" class="formu">
            <input class="formu-input" type="text" name="titulo" placeholder="Titulo" <?php if (isset($_REQUEST['ma'])) {
                                                                                            echo "value='" . $mplan['titulo'] . "'";
                                                                                        } ?> required> <br><br>
            <textarea id="ckeditor" class="formu-input ckeditor" type="text" name="texto" placeholder="Texto" cols='30' rows="10" required><?php if (isset($_REQUEST['ma'])) {
                                                                                                                                                echo $mplan['texto'];
                                                                                                                                            } ?></textarea><br>
            <input class="formu-input" type="date" name="fe" placeholder="Fecha De Entrega" <?php if (isset($_REQUEST['ma'])) {
                                                                                                echo "value='" . $mplan['fechaentrega'] . "'";
                                                                                            } ?> required> <br>
            <select class="" name='periodo' id="periodo" required>
                <option value="">Periodo</option>
                <option value="I">Primer Periodo</option>
                <option value="II">Segundo Periodo</option>
                <option value="III">Tercer Periodo</option>
                <option value="IV">Cuarto Periodo</option>
            </select>
            <?php if (isset($_REQUEST['ma'])) {
                echo "<input type='hidden' name ='modificar' value='" . $_REQUEST['ma'] . "'> ";
            } ?>
            <input class="formu-button" type="submit" <?php if (isset($_REQUEST['ma'])) {
                                                            echo "value='Guardar'";
                                                        } else {
                                                            echo "value='Agregar'";
                                                        } ?>>
        </form>
    <?php

    }

    ?>


    <hr>
    <h1 style="margin-bottom: -50px">Lista de Actividades</h1>
    <div class="containerclasemenu">
        <div class="menu">
            <h1><?php include('includes/menu.php') ?></h1>
        </div>

        <div class="tabla">
            <table>
                <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Observacion</th>
                        <th>Periodo</th>
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
                        echo "<td>" . $a['periodo'] . "</td>";
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
    </div>

    <?php

    ?>

</body>


</html>


<?php

mysqli_free_result($resultado1);
mysqli_free_result($resultad);
mysqli_free_result($resultado);
mysqli_close($cont);

?>