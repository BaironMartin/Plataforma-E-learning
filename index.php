<?php
include('includes/conectar.php');

session_start();
if (isset($_SESSION['user'])) {
    header("Location: inicio.php");
}

if (isset($_REQUEST['u']) && !empty($_REQUEST['u'])) {
    $u = $_POST['u'];
    $p = $_POST['p'];
    $p= hash('sha512',$p);
    $sql = "SELECT * FROM usuarios WHERE Email='$u' AND Clave='$p'";
    $resultado = mysqli_query($cont, $sql);
    if (mysqli_num_rows($resultado) == 1) {
        $_SESSION['user'] = $u;
        header("Location: inicio.php");
    } else {
        echo '<script type="text/javascript">
    alert("Usuario y contrasenia Incorrecto");
    window.location.href="index.php";
    </script>';
    }
    mysqli_free_result($resultado);
    mysqli_close($cont);
}


if (isset($_REQUEST['user']) && !empty($_REQUEST['user'])) {
    $u = $_REQUEST['user'];
    $p = $_REQUEST['pass'];
    $p= hash('sha512',$p);
    $n = $_REQUEST['nombre'];
    $f = $_FILES['photo']['name'];
    $t = $_REQUEST['tipo'];

    $id = $u;
    $sql = "SELECT* FROM usuarios WHERE Email='$id'";
    $resultado = mysqli_query($cont, $sql);
    if (mysqli_num_rows($resultado) == 0) {

        mysqli_query($cont, "INSERT INTO usuarios VALUE ('$u','$p','$n','$f','$t')");
        move_uploaded_file($_FILES['photo']['tmp_name'], "archivos/" . $u . $f);
        header("Location: index.php");
    } else {
        echo '<script type="text/javascript">
    alert("Ya tíenes una cuenta");
    window.location.href="index.php";
    </script>';
    }

    mysqli_free_result($resultado);
    mysqli_close($cont);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" tipe="text/class" href="css/normalize.css">
    <link rel="stylesheet" tipe="text/class" href="css/estilos.css?v=<?php echo (rand()); ?>">
    <title>Plataforma E-LEARNING login and register</title>
    <link rel="icon" href="img/ico/free bsd.ico">
</head>

<body>
    <div class="pegajoso">
        <img src="img/logo.jpg" alt="">
        <h1>PREPARATORIA RANCHO HUMILDE</h1>
    </div>
    <main>
        <div class="contenedor__todo">
            <div class="caja_trasera">
                <div class="caja_trasera_login">
                    <h3>Ya tíenes una cuenta</h3>
                    <p>Iniciar sesión para ingresar a la página</p>
                    <button id="btn_iniciar_secion">Iniciar sesión </button>
                </div>
                <div class="caja_trasera_register">
                    <h3>Aún no tíenes una cuenta</h3>
                    <p>Regístrate para que puedas iniciar secion </p>
                    <button id="btn_Register">Regístrarse </button>
                </div>
            </div>


            <div class="contenedor_login_register">
                <form action="index.php" method="post" class="formulario_login" autocomplete="off">
                    <h2>Iniciar Sesión</h2>
                    <input type="email" name="u" id="" placeholder="Correo Electronico">
                    <input type="password" name="p" id="" placeholder="Contraseña">
                    <button>Entrar</button>
                </form>
                <form action="index.php" method="post" enctype="multipart/form-data" class="formulario_register">
                    <h2>Regístrarse</h2>
                    <input type="email" name="user" id="" placeholder="Correo Electronico">
                    <input type="password" name="pass" id="" placeholder="Contraseña">
                    <input type="text" name="nombre" id="" placeholder="Nombre Completo">
                    <input type="file" name="photo" id="" placeholder="Imagen" require>
                    <select name='tipo' id="tipo">
                        <option value=""></option>
                        <option value="Docente">Docente</option>
                        <option value="Estudiante">Estudiante</option>
                    </select>
                    <br>
                    <input type="submit" value="Registrar">
                </form>
            </div>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>

</html>