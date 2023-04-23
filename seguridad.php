<?php

include('includes/conectar.php');
include('includes/secionesUser.php');

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

$sql = ("SELECT * FROM `seguridad` WHERE user ='" . $_SESSION['user'] . "'");
$res = mysqli_query($cont, $sql);
$nm = mysqli_num_rows($res);
if ($nm == 1) {
    header("location:inicio.php");
}

if (isset($_REQUEST['segur'])) {
    $user = $_SESSION['user'];
    $p1 = $_REQUEST['security_question1'];
    $r1 = $_REQUEST['respuesta1'];
    $p2 = $_REQUEST['security_question2'];
    $r2 = $_REQUEST['respuesta2'];


    $query = "INSERT INTO `seguridad`(`id`, `user`, `p1`, `r1`, `p2`, `r2`)
        VALUES ('null','$user','$p1','$r1','$p2','$r2')";
    mysqli_query($cont, $query);
    header("location:inicio.php");
}


include('includes/encabezado.php')
?>

<body>
    <div class="pegajoso">
        <h2 class="titulo1"><?php include('includes/name.php') ?></h2>
        <div class="container2">
            <a class=" editar" href="inicio.php">Inicio</a>
            <a class=" cerrar" href="inicio.php?cerrar=1">Cerrar Secion</a>
        </div>
    </div>

    <form action="seguridad.php" method="post" class="formu">
        <div>
            <label for="security_question">Pregunta de seguridad:</label>
            <select id="security_question" name="security_question1" required>
                <option value="1">¿Cuál es el nombre de tu mejor amigo de la infancia?</option>
                <option value="2">¿Cuál es el apellido de soltera de tu madre?</option>
                <option value="3">¿Cuál fue tu ciudad natal?</option>
                <option value="4">¿Cuál es tu deporte favorito?</option>
                <option value="5">¿Cuál es tu comida favorita?</option>
                <option value="6">¿Cuál es tu personaje histórico favorito?</option>
                <option value="7">¿Cuál es tu libro favorito?</option>
                <option value="8">¿Cuál es tu canción favorita de la infancia?</option>
                <option value="9">¿Cuál es tu película favorita de terror?</option>
                <option value="10">¿Cuál fue el nombre de tu primer/a novio/a?</option>
            </select>

            <input type="text" class="formu-input" name="respuesta1" required>
        </div>
        <div>
            <label for="security_question">Pregunta de seguridad:</label>
            <select id="security_question" name="security_question2">
                <option value="11">¿Cuál es el nombre de tu mascota?</option>
                <option value="12">¿Cuál es tu color favorito?</option>
                <option value="13">¿Cuál fue tu primer coche?</option>
                <option value="14">¿Cuál es tu película favorita?</option>
                <option value="15">¿Cuál es tu canción favorita?</option>
            </select>

            <input type="text" class="formu-input" name="respuesta2" required>
        </div>

        <div class="form_seleccion">
            <input type="submit" name="segur" VALUE="Guardar" class="formu-button"></input>
        </div>
    </form>




</body>

</html>