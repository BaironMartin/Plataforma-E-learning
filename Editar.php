<?php

include('includes/conectar.php');
include('includes/secionesUser.php');
include('includes/csrf.php');
include('includes/upload_security.php');

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
    exit;
}

$varsession = $_SESSION['user'];

if ($varsession == null || $varsession == '') {
    echo 'acceso denegado';
    die();
}


$sql = "SELECT * FROM usuarios WHERE Email=?";
$stmt = mysqli_prepare($cont, $sql);
mysqli_stmt_bind_param($stmt, "s", $_SESSION['user']);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$a = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (isset($_REQUEST['pass']) && !empty($_REQUEST['pass'])) {
    // Validar token CSRF
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        header("location:inicio.php");
        exit;
    }

    $p = $_REQUEST['pass'];
    
    // Verificar password actual
    if (password_verify($p, $a['Clave']) || hash('sha512', $p) == $a['Clave']) {

        $u = $_REQUEST['user'];
        $n = $_REQUEST['nombre'];
        $t = $_REQUEST['tipo'];
        $bn = $_REQUEST['baner'];
        $grado = $_REQUEST['grado'];
        $cc = $_REQUEST['cc'];
        $f = '';
        $img = $_FILES['photo']['name'];

        if ($img == "") {
            $f = $_REQUEST['fotoa'];
        } else {
            // Validar y subir archivo de forma segura
            $upload_result = subirArchivoSeguro(
                $_FILES['photo'], 
                "archivos/", 
                $u,
                ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                5 * 1024 * 1024
            );
            
            if ($upload_result['success']) {
                $f = $upload_result['filename'];
            } else {
                header("location:inicio.php?error=" . urlencode($upload_result['error']));
                exit;
            }
        }


        $sql = "UPDATE usuarios SET Nombre=?, Foto=?, cc=?, Tipo=?, baner=? WHERE Email=?";
        $update_stmt = mysqli_prepare($cont, $sql);
        mysqli_stmt_bind_param($update_stmt, "ssssss", $n, $f, $cc, $t, $bn, $_SESSION['user']);
        mysqli_query($cont, $update_stmt);
        mysqli_stmt_close($update_stmt);
        
        header("location:inicio.php");
        exit;

    } else {
        header("location:inicio.php");
        exit;
    }
}

mysqli_free_result($resultado);
mysqli_close($cont);

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

    <div class="contenedor_flex">
        <?php
        if($nm != 1){
            ?>
        <a class='crear' style="text-align:center ;" href='seguridad.php'> Crear preguntas de seguridad</a>
        <?php
        }
        ?>

        <form action="Editar.php" method="post" enctype="multipart/form-data" class="formu">
            <?php echo campoTokenCSRF(); ?>
            <BR>

            <div class="form_seleccion">
                <label class="label1">Email</label>
                <input type="email" class="formu-input" name="user" value="<?php echo htmlspecialchars($a['Email']); ?>" readonly>
            </div>
            <BR>


            <div class="form_seleccion">
                <label class="label1">Nombre </label>
                <input type="text" class="formu-input" name="nombre" value="<?php echo htmlspecialchars($a['Nombre']); ?>" required>
            </div>
            <BR>

            <div class="form_seleccion">
                <label class="label1">Codigo </label>
                <input type="text" class="formu-input" name="cc" value="<?php echo htmlspecialchars($a['cc']); ?>" required>
            </div>
            <BR>

            <div class="form_seleccion">
                <label class="label1">Foto Actual <?php echo htmlspecialchars($a['Foto']); ?> </label><BR>
                <?php
                echo "<img src ='archivos/" . htmlspecialchars($_SESSION['user']) . "" . htmlspecialchars($a['Foto']) . "'width=30% > ";
                ?>
                <input type="file" class="formu-input" name="photo">
            </div>

            <input type="hidden" class="formu-input" name="tipo" value="<?php echo htmlspecialchars($a['Tipo']); ?>">
            <input type="hidden" class="formu-input" name="fotoa" value="<?php echo htmlspecialchars($a['Foto']); ?>">
            <br>
            <div class="form_seleccion">
                <label class="label1">Banner </label>
                <br>
                <label class="label1">Banner Actual <?php echo htmlspecialchars($a['Foto']); ?> </label><BR>
                <?php
                echo "<img src ='" . htmlspecialchars($a['baner']) . "'width=30% > ";
                ?>
                <input type="text" class="formu-input" name="baner" value="<?php echo htmlspecialchars($a['baner']); ?>" required>
            </div>
            <BR>

            <div class="form_seleccion">
                <label class="label1" style="color: orange ;">Ingrese su Password </label>
                <input type="password" class="formu-input" name="pass" placeholder="Contraseña" required>
            </div>
            <BR>


            <div class="form_seleccion">
                <input type="submit" name="Editar" VALUE="Editar" class="formu-button"></input>
            </div>



        </form>

    </div>

    <?php

    ?>

</body>

</html>