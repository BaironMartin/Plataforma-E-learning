<?php
session_start();
include('../includes/conectar.php');
include('../includes/csrf.php');

// Regenerar token CSRF
generarTokenCSRF();

if (isset($_REQUEST['u']) && !empty($_REQUEST['u'])) {
    // Validar token CSRF
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        header("Location: error_handler.php?t=captcha");
        exit;
    }
    
    $u = $_POST['u'];
    $p = $_POST['p'];

    // Prepared statement para prevenir SQL injection
    $sql = "SELECT * FROM admin_p WHERE email=?";
    $stmt = mysqli_prepare($cont, $sql);
    mysqli_stmt_bind_param($stmt, "s", $u);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($resultado) == 1) {
        $admin = mysqli_fetch_assoc($resultado);
        
        // Verificar password con bcrypt o SHA-512 legacy
        if (password_verify($p, $admin['contrasenia']) || hash('sha512', $p) == $admin['contrasenia']) {
            // Regenerar ID de sesión
            session_regenerate_id(true);
            $_SESSION['user_admin'] = $u;
            $_SESSION['time'] = time();
            
            // Migrar a bcrypt si estaba usando SHA-512
            if (hash('sha512', $p) == $admin['contrasenia']) {
                $new_hash = password_hash($p, PASSWORD_BCRYPT);
                $update_stmt = mysqli_prepare($cont, "UPDATE admin_p SET contrasenia=? WHERE email=?");
                mysqli_stmt_bind_param($update_stmt, "ss", $new_hash, $u);
                mysqli_stmt_execute($update_stmt);
                mysqli_stmt_close($update_stmt);
            }
            
            header("Location: inicio_admin.php");
        } else {
            header("Location: error_handler.php?t=admin");
        }
    } else {
        header("Location: error_handler.php?t=admin");
    }
    
    mysqli_stmt_close($stmt);
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
    <title>Plataforma E-LEARNING login and register</title>
    <link rel="icon" href="../img/logo.ico">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/materialize.min.css">
    <link rel="stylesheet" href="css/main.css?v=<?php echo (rand()); ?>">

    <script src="js/materialize.min.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/materialize.js"></script>

</head>

<body>

    <nav class='green'>
        <div class="container">
            <div class="nav-wrapper">
                <a href="#!" class="brand-logo  ">Institucion educativa Horizontes Academicos</a>
                <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            </div>
        </div>
    </nav>



    <div class="container">
        <div class="row">
            <div class=" col s12 m6">
                <br><br><br><br>
                <img src="../img/logo.png" alt="" srcset="">
            </div>

            <div class=" col s12 m6">
                <br><br><br><br>
                <form action="index.php" method="post" class="col s12" autocomplete="">
                    <?php echo campoTokenCSRF(); ?>
                    <div class="row">
                        <div class="row">
                            <div class="input-field col s12">
                                <input name="u" id="email" type="email" class="validate" required>
                                <label for="email">Email</label>
                            </div>
                        </div>
                        <div class="input-field col s12">
                            <input name="p" id="password" type="password" class="validate" required>
                            <label for="password">Password</label>
                        </div>
                    </div>

                    <br>
                    <div class="center-align">
                        <button style="display: inline-flex;" class=" green btn m5"><i class="material-icons">input</i> &nbsp; Login</button>
                    </div>


                </form>

            </div>



        </div>
    </div>
    </div>


    <br><br><br>









    <?php
include('includes/footer.php')

    ?>

</body>





<script src="js/app.js"></script>

</html>