<?php

include('../includes/conectar.php');
include('../function/funciones.php');


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
$sql = "SELECT * FROM admin_p WHERE email ='" . $_SESSION['user_admin'] . "'";
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);

if (isset($_REQUEST['mail']) && !empty($_REQUEST['mail'])) {
    $u = $_REQUEST['mail'];
    $p = $_REQUEST['pass'];
    $n = $_REQUEST['name'];
    $cc = $_REQUEST['cc'];
    $f = $_FILES['perfil']['name'];
    $t = $_REQUEST['tipo'];
    $g = $_REQUEST['grado'];

    $p = hash('sha512', $p);
    $id = $u;
    $sql = "SELECT* FROM usuarios WHERE Email='$id'";
    $resultado = mysqli_query($cont, $sql);
    $sql2 = "SELECT* FROM usuarios WHERE cc='$cc'";
    $resultado2 = mysqli_query($cont, $sql2);
    if (mysqli_num_rows($resultado2) == 0) {
        if (mysqli_num_rows($resultado2) == 0) {

            mysqli_query($cont, "INSERT INTO usuarios VALUE ('$u','$p','$n','$cc','$f','$t','$g')");
            move_uploaded_file($_FILES['perfil']['tmp_name'], "../archivos/" . $u . $f);
            header("Location: registrarUsuario_admin.php");
        } else {
            header("Location: errors/errorlogin2.php");
        }
    } else {
        header("Location: errors/errorlogin3.php");
    }

    mysqli_free_result($resultado);
    mysqli_close($cont);
}




include('includes/header.php');
include('includes/menu.php');

?>

<div class="container">
    <h1 class="center green-text Underline">Registro de Usuarios</h1>
    <div class="row green-text ">
        <form class="col s12" action="registrarUsuario_admin.php" enctype="multipart/form-data" method="post">

            <div class="row">
                <div class="input-field col s12">
                    <input id="first_name" type="text" class="validate" name="name">
                    <label for="first_name">Nombres y Apellidos</label>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <input id="email" type="email" class="validate" name="mail">
                        <label for="email">Email</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="password" type="password" class="validate" name="pass">
                        <label for="password">Password</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="icon_telephone" type="tel" class="validate" name="cc">
                        <label for="icon_telephone">Numero de Documento</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <select name='tipo' id="tipo">
                            <option value="" disabled selected>Seleccione una Opcion</option>
                            <option value="Docente">Docente</option>
                            <option value="Estudiante">Estudiante</option>
                        </select>
                        <label>Tipo de Usuario</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <select name='grado' id="grado">
                            <option value="" disabled selected>Seleccione una Opcion</option>
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
                        <label>Tipo de Usuario</label>
                    </div>
                </div>

                <div class="row">
                    <div class="file-field input-field">
                        <div class="green btn">
                            <span>Foto de Perfil</span>
                            <input type="file" name="perfil">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" name='perfil' type="text">
                        </div>
                    </div>
                </div>


                <div class=" center-align">
                    <button class="btn waves-effect waves-light green" type="submit" name="action">Submit
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>









<?php
mysqli_free_result($resultado);
mysqli_close($cont);
include('includes/footer.php');
?>