<?php

include('../includes/conectar.php');


session_start();
if (!isset($_SESSION['user_admin'])) {
    header("location:index.php");
} else {
    if ((time() - $_SESSION['time']) > 1800) {
        session_destroy();
        header("location:index.php");
    }
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}
if (isset($_REQUEST['clave']) && !empty($_REQUEST['clave'])) {
    $_SESSION['user'] = $_REQUEST['clave'];
}

$sql = ("SELECT clase.nombre,clase.imagen, misclases.idmiclase, clase.clave, clase.grado FROM clase,misclases WHERE clase.clave = misclases.clave and misclases.usuario= '" . $_SESSION['user'] . "'");
$resultado1 = mysqli_query($cont, $sql);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);

$query = mysqli_query($cont, ("SELECT * FROM `usuarios` WHERE Email = '" . $_SESSION['user'] . "'"));
$coun = mysqli_num_rows($query);
$content = mysqli_fetch_assoc($query);
$grado = $content['grado'];


$sql = "SELECT * FROM clase WHERE grado ='".$grado."'" ;
$resultadoc = mysqli_query($cont, $sql);
$cpountc = mysqli_num_rows($resultadoc);
$ac = mysqli_fetch_assoc($resultadoc);

if (isset($_REQUEST['clases1'])) {
    $sql2 = ("SELECT * FROM clase WHERE clave='" . $_REQUEST['clases1'] . "'");
    $resulado1 = mysqli_query($cont, $sql2);
    $n = mysqli_num_rows($resulado1);
    if ($n > 0) {
        $sql3 = ("SELECT * FROM misclases WHERE clave='" . $_REQUEST['clases1'] . "' AND usuario='" . $_SESSION['user'] . "'");
        $resultado2 = mysqli_query($cont, $sql3);
        $nc = mysqli_num_rows($resultado2);

        if ($nc == 0) {
            $u = $_SESSION["user"];
            $c = $_REQUEST['clases1'];
            $sql = ("INSERT INTO misclases VALUES(NULL,'$u','$c')");
            mysqli_query($cont, $sql);
            header("location:alumno_clase.php");
        } else {
            echo '<script type="text/javascript">alert("Ya te Uniste a esta clase ");
        window.location.href="alumnos_admin.php";
        </script>';
        }
        mysqli_free_result($resultado2);
    } else {
        echo '<script type="text/javascript">alert("La clase no Existe");
        window.location.href="alumnos_admin.php";
        </script>';
    }
    mysqli_free_result($resultado1);
}

if (isset($_REQUEST['e'])) {
    $sql2 = ("DELETE FROM misclases WHERE idmiclase=" . $_REQUEST['e']);
    mysqli_query($cont, $sql2);
    header("location:alumno_clase.php");
}

$sql = "SELECT * FROM admin_p WHERE email ='" . $_SESSION['user_admin'] . "'";
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);

include('includes/header.php');
include('includes/menu.php');

?>
<div class="container">
    <h1 class="center green-text Underline">Clases del Alumno</h1>
    <hr>
    <h5 class="green-text center">registrar al alumno <?php echo $content['Nombre']; ?> a clase</h5>

    <form class="col s12" action="alumno_clase.php"  method="post">
        <div class="input-field col s12">
            <select name="clases1">
                <option value="" disabled selected>Choose your option</option>

                <?php
                do {
                    echo "<option value=" . $ac['clave'] . ">" . $ac['nombre'] . "</option>";
                } while ($ac = mysqli_fetch_assoc($resultadoc));
                ?>
            </select>
            <label>Clases</label>

            <div class=" center-align">
                <button class="btn waves-effect waves-light green" type="submit" name="action">Submit
                    <i class="material-icons right">send</i>
                </button>
            </div>
        </div>
    </form>


    <hr>

    <div class="row">
        <?php
        if ($n1 > 0) {
            do {
                $name = $a1['nombre'];
        ?>


                <div class="col s12 m6 l3">
                    <div class="card">
                        <div class="card-image">
                            <?php
                            echo "<img class='z-depth-5' src=" . $a1['imagen'] . ">";

                            echo "<br><br><br><br><br><span class='card-title black-text'><strong>" . substr($name, 0, 20), "..." . "</strong> </span>";
                            ?>
                        </div>
                        <div class="card-content">
                            <p><strong class="red-text">clave: </strong><?php echo $a1['clave']; ?> </p>
                            <p><strong class="green-text">Grado: </strong><?php echo $a1['grado']; ?> </p>
                        </div>
                        <div class="card-action">
                            <?php echo "<a href='alumno_clase.php?e=" . $a1['idmiclase']."'>Eliminar</a>'";?>
                        </div>
                    </div>
                </div>

        <?php
            } while ($a1 = mysqli_fetch_assoc($resultado1));
        } else {
            echo "No hay clases";
        }

        ?>
    </div>
</div>






<script src="js/buscador.js"></script>

<?php
mysqli_free_result($resultado1);
//mysqli_free_result($resultadc);
mysqli_free_result($query);
mysqli_close($cont);
include('includes/footer.php');
?>