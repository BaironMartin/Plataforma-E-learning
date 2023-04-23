<?php
include('includes/conectar.php');
include('function/funciones.php');

session_start();
if (isset($_SESSION['user'])) {
    header("Location: inicio.php");
}

if (isset($_REQUEST['u']) && !empty($_REQUEST['u'])) {
    $u = $_POST['u'];
    $p = $_POST['p'];
    echo $u,$p;
    $clave = $_POST['g-recaptcha-response'];
    $secret = '6LcRjHskAAAAABA0ioTMxTx7GwBSq8PfKKZBQcTo';

    if (!$clave) {
        header("Location: errors/errorlogin.php");
    }

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$clave");
    $arr = json_decode($response, true);
    if ($arr['success']) {
        login_Index($u, $p);
    } else {
    }
}


if (isset($_REQUEST['user']) && !empty($_REQUEST['user'])) {
    $u = $_REQUEST['user'];
    $p = $_REQUEST['pass'];
    $n = $_REQUEST['nombre'];
    $cc = $_REQUEST['cc'];
    $f = $_FILES['photo']['name'];
    $t = $_REQUEST['tipo'];
    $g = $_REQUEST['grado'];

    registrer_Index($u, $p, $n, $cc, $f, $t,$g);
}

include('includes/encabezado.php')
?>


<body>
    <div class="pegajoso">
        <img src="img/logo.png" alt="">
        <h1><?php include('includes/name.php') ?></h1>
    </div>
    <br>
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
                    <input type="email" name="u" id="" placeholder="Correo Electronico" required>
                    <input type="password" name="p" id="" placeholder="Contraseña" required>
                    <br><br>
                    <div class="g-recaptcha" data-sitekey="6LcRjHskAAAAAEwUuhbrMYUDI4W2am3GtMfrr4dh"></div>
                    <button>Entrar</button>
                    <br><a href="restaurarPasword.php">Olvide mi Password</a>
                </form>
                <form action="index.php" method="post" enctype="multipart/form-data" class="formulario_register">
                    <h2>Regístrarse</h2>
                    <input type="email" name="user" id="" placeholder="Correo Electronico">
                    <input type="password" name="pass" id="" placeholder="Contraseña">
                    <input type="text" name="nombre" id="" placeholder="Nombre Completo">
                    <input type="number" name="cc" id="" placeholder="Documento de identidad">
                    <input type="file" name="photo" id="" placeholder="Imagen" require><br><br>
                    <select name='tipo' id="tipo">
                        <option value=""></option>
                        <option value="Docente">Docente</option>
                        <option value="Estudiante">Estudiante</option>
                    </select>
                    <br>
                    <select name='grado' id="grado">
                        <option value=""></option>
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
                    <label>En caso de ser docente seleccionar no aplica</label>
                    <br>
                    <button>Registrar</button>
                </form>
            </div>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>

</html>