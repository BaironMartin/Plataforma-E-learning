<?php
include('includes/conectar.php');
include('function/funciones.php');
include('includes/csrf.php');

session_start();

// Regenerar token CSRF en cada sesión
generarTokenCSRF();

if (isset($_SESSION['user'])) {
    header("Location: inicio.php");
    exit;
}

// Manejar login
if (isset($_POST['u']) && !empty($_POST['u'])) {
    // Validar token CSRF primero
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        header("Location: Errors/errorlogin.php");
        exit;
    }
    
    $u = $_POST['u'] ?? '';
    $p = $_POST['p'] ?? '';
    
    if (empty($u) || empty($p)) {
        header("Location: Errors/errorlogin.php");
        exit;
    }
    
    $clave = $_POST['g-recaptcha-response'] ?? '';
    $secret = getenv('RECAPTCHA_SECRET_KEY') ?: '6LcRjHskAAAAABA0ioTMxTx7GwBSq8PfKKZBQcTo';

    if (!$clave) {
        header("Location: Errors/errorlogin.php");
        exit;
    }

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$clave");
    $arr = json_decode($response, true);
    
    if ($arr['success']) {
        login_Index($u, $p);
        exit;
    } else {
        header("Location: Errors/errorlogin.php");
        exit;
    }
}

// Manejar registro
if (isset($_POST['user']) && !empty($_POST['user'])) {
    // Validar token CSRF
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        header("Location: Errors/errorlogin.php");
        exit;
    }
    
    $u = $_POST['user'] ?? '';
    $p = $_POST['pass'] ?? '';
    $n = $_POST['nombre'] ?? '';
    $cc = $_POST['cc'] ?? '';
    $t = $_POST['tipo'] ?? '';
    $g = $_POST['grado'] ?? '';
    
    // Validar campos requeridos
    if (empty($u) || empty($p) || empty($n) || empty($cc) || empty($t)) {
        header("Location: Errors/errorlogin.php");
        exit;
    }
    
    // Validar tipo de usuario
    if (!in_array($t, ['Docente', 'Estudiante'])) {
        header("Location: Errors/errorlogin.php");
        exit;
    }

    registrer_Index($u, $p, $n, $cc, $t, $g);
    exit;
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
                    <?php echo campoTokenCSRF(); ?>
                    <input type="email" name="u" id="" placeholder="Correo Electronico" required>
                    <input type="password" name="p" id="" placeholder="Contraseña" required>
                    <br><br>
                    <div class="g-recaptcha" data-sitekey="<?php echo getenv('RECAPTCHA_SITE_KEY') ?: '6LcRjHskAAAAAEwUuhbrMYUDI4W2am3GtMfrr4dh'; ?>"></div>
                    <button type="submit">Entrar</button>
                    <br><a href="restaurarPasword.php">Olvide mi Password</a>
                </form>
                <form action="index.php" method="post" enctype="multipart/form-data" class="formulario_register">
                    <h2>Regístrarse</h2>
                    <?php echo campoTokenCSRF(); ?>
                    <input type="email" name="user" id="" placeholder="Correo Electronico" required>
                    <input type="password" name="pass" id="" placeholder="Contraseña" required>
                    <input type="text" name="nombre" id="" placeholder="Nombre Completo" required>
                    <input type="number" name="cc" id="" placeholder="Documento de identidad" required>
                    <input type="file" name="photo" id="" accept="image/*" required><br><br>
                    <select name='tipo' id="tipo" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="Docente">Docente</option>
                        <option value="Estudiante">Estudiante</option>
                    </select>
                    <br>
                    <select name='grado' id="grado">
                        <option value="">Seleccione un grado</option>
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
                    <button type="submit">Registrar</button>
                </form>
            </div>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>

</html>