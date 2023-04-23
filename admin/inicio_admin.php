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




$sql = "SELECT * FROM admin_p WHERE email ='" . $_SESSION['user_admin'] . "'";
$resultado = mysqli_query($cont, $sql);
$a = mysqli_fetch_assoc($resultado);

if (isset($_REQUEST['nom']) && !empty($_REQUEST['nom'])) {
    $n = $_REQUEST['nom'];
    $ap = $_REQUEST['ape'];
    $mail = $_REQUEST['mail'];
    $pas = $_REQUEST['pass'];
    $car = $_REQUEST['car'];
    $cel = $_REQUEST['cel'];
    $edad = $_REQUEST['edad'];
    $fp = $_FILES['perfil']['name'];
    $fb = $_FILES['bane']['name'];


    $pas = hash('sha512', $pas);
    $id = $mail;
    $sql = "SELECT* FROM admin_p WHERE email='$id'";
    $resultado = mysqli_query($cont, $sql);
    if (mysqli_num_rows($resultado) == 0) {

        mysqli_query($cont, "INSERT INTO admin_p VALUE (null,'$n','$ap','$mail','$pas','$car','$cel','$edad','$fp','$fb')");
        move_uploaded_file($_FILES['perfil']['tmp_name'], "archivos/perfil/" . $n . $fp);
        move_uploaded_file($_FILES['bane']['tmp_name'], "archivos/baner/" . $n . $fb);
        header("Location: inicio_admin.php");
    } else {
        echo "M.toast({html: 'I am a toast!'})";
        header("Location: errors/errorlogin2.php");
    }


    mysqli_free_result($resultado);
    mysqli_close($cont);
}



include('includes/header.php');
include('includes/menu.php');

?>




<div class="container">
    <h1 class="center green-text Underline">Registro de Directivos</h1>
    <div class="row green-text ">
        <form class="col s12" action="inicio_admin.php" enctype="multipart/form-data" method="post">
            <div class="row">
                <div class="input-field col s6">
                    <i class="material-icons prefix">account_circle</i>
                    <input id="first_name" type="text" class="validate" name="nom">
                    <label for="first_name">Nombres</label>
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix">account_circle</i>
                    <input id="last_name" type="text" class="validate" name="ape">
                    <label for="last_name">Apellidos</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">email</i>
                    <input id="email" type="email" class="validate" name="mail">
                    <label for="email">Email</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">password</i>
                    <input id="password" type="password" class="validate" name="pass">
                    <label for="password">Password</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">business</i>
                    <input id="cargo" type="text" class="validate" name='car'>
                    <label for="cargo">Cargo</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">phone</i>
                    <input id="icon_telephone" type="tel" class="validate" name="cel">
                    <label for="icon_telephone">Telefono</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">cake</i>
                    <input id="age" type="number" class="validate" name="edad">
                    <label for="age">Edad</label>
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

            <div class="row">
                <div class="file-field input-field">
                    <div class="btn green">
                        <span>Baner</span>
                        <input type="file" name='bane'>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" name='bane' type="text">
                    </div>
                </div>
            </div>
            <div class=" center-align">
                <button class="btn waves-effect waves-light green" type="submit" name="action">Submit
                    <i class="material-icons right">send</i>
                </button>
            </div>

        </form>
    </div>
</div>








<?php
mysqli_free_result($resultado);
mysqli_close($cont);
include('includes/footer.php')

?>

