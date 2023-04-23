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

$sql1 = ("SELECT * FROM clase WHERE usuario='" . $_SESSION['user'] . "'");
$resultado1 = mysqli_query($cont, $sql1);
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);

function generaPass(){
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $longitudCadena = strlen($cadena);
    $pass = "";
    $longitudPass = 10;
    for ($i = 1; $i <= $longitudPass; $i++) {
        $pos = rand(0, $longitudCadena - 1);
        $pass .= substr($cadena, $pos, 1);
    }
    return $pass;
}

if (isset($_REQUEST['clase'])) {
    $name = $_REQUEST['clase'];
    $imagen = $_REQUEST['imagen'];
    $gra=$_REQUEST['grado'];
    $clave = generaPass();
    $u = $_SESSION['user'];

    $sql1 = ("INSERT INTO clase VALUES(NULL,'$name','$clave','$u',NULL,'$imagen','$gra')");
    mysqli_query($cont, $sql1);
    header("location:docente_clase.php");
}


$query = mysqli_query($cont, ("SELECT * FROM `usuarios` WHERE Email = '" . $_SESSION['user'] . "'"));
$coun = mysqli_num_rows($query);
$content = mysqli_fetch_assoc($query);





if (isset($_REQUEST['e'])) {
    $sql2 = ("DELETE FROM clase WHERE idclase=" . $_REQUEST['e']);
    mysqli_query($cont, $sql2);
    header("location:docente_clase.php");
}

$sql = "SELECT * FROM admin_p WHERE email ='" . $_SESSION['user_admin'] . "'";
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);

include('includes/header.php');
include('includes/menu.php');

?>
<div class="container">
    <h1 class="center green-text Underline">Clases del Docente</h1>
    <hr>
    <h5 class="green-text center">cerar clase al docente <?php echo $content['Nombre']; ?> </h5>

    <form class="col s12" action="docente_clase.php" method="post" autocomplete="off">

        <div class="row">
            <div class="input-field col s12">
                <input name="clase" id="first_name2" type="text" class="validate">
                <label class="active" for="first_name2">Nombre de la Clase</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <input name="imagen" id="first_name2" type="text" class="validate">
                <label class="active" for="first_name2">Enlace de la Imagen</label>
            </div>
        </div>

        <select class="formu-input" name='grado' id="grado">
        <option value="" disabled selected>Choose your option</option>
            <option value="Prescolar">Prescolar</option>
            <option value="primero">primero</option>
            <option value="segundo">segundo</option>
            <option value="tercero">tercero</option>
            <option value="cuarto">cuarto</option>
            <option value="quinto">quinto</option>
            <option value="sexto">sexto</option>
            <option value="septimo">septimo</option>
            <option value="octavo">octavo</option>
            <option value="noveno">noveno</option>
            <option value="decimo">decimo</option>
            <option value="undecimo">undecimo</option>
            <option value="noaplica">No aplica</option>
        </select>



        <div class=" center-align">
            <button class="btn waves-effect waves-light green" type="submit" name="action">Crear Clase
                <i class="material-icons right">send</i>
            </button>
        </div>
    </form>


    <hr><br><br><br><br>

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
                            <p><strong class="red-text">glave: </strong><?php echo $a1['clave']; ?> </p>
                            <p><strong class="green-text">Grado: </strong><?php echo $a1['grado']; ?> </p>
                        </div>
                        <div class="card-action">
                            <?php echo "<a href='docente_clase.php?e=" . $a1['idclase'] . "'>Eliminar</a>'"; ?>
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