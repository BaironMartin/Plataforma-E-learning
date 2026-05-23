<?php
include('includes/conectar.php');
include('includes/secionesUser.php');

if (!isset($_SESSION['clave'])) {
    header("Location: error_handler.php");
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("location:index.php");
}

// Consultas con prepared statements
$stmt = $cont->prepare("SELECT * FROM clase WHERE clave = ?");
$stmt->bind_param("s", $_SESSION['clave']);
$stmt->execute();
$resultado1 = $stmt->get_result();
$n1 = mysqli_num_rows($resultado1);
$a1 = mysqli_fetch_assoc($resultado1);
$stmt->close();

$stmt2 = $cont->prepare("SELECT * FROM misclases, usuarios WHERE misclases.usuario=usuarios.Email AND misclases.clave = ?");
$stmt2->bind_param("s", $_SESSION['clave']);
$stmt2->execute();
$resultado = $stmt2->get_result();
$n = mysqli_num_rows($resultado);
$alumnos = mysqli_fetch_assoc($resultado);
$stmt2->close();

$stmt3 = $cont->prepare("SELECT * FROM clase, usuarios WHERE clase.usuario=usuarios.Email AND clase.clave = ?");
$stmt3->bind_param("s", $_SESSION['clave']);
$stmt3->execute();
$resultado2 = $stmt3->get_result();
$docente = mysqli_fetch_assoc($resultado2);
$stmt3->close();

$stmt4 = $cont->prepare("SELECT * FROM usuarios WHERE Email = ?");
$stmt4->bind_param("s", $_SESSION['user']);
$stmt4->execute();
$resultad = $stmt4->get_result();
$as = mysqli_fetch_assoc($resultad);
$stmt4->close();

if (isset($_REQUEST['enviar'])) {
    // Validar token CSRF
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        header("Location: error_handler.php?t=login");
        exit();
    }
    
    $u = $_SESSION['user'];
    $c = $_SESSION['clave'];
    $para = filter_input(INPUT_POST, 'para', FILTER_VALIDATE_EMAIL);
    $asunto = trim($_REQUEST['asunto']);
    $texto = trim($_REQUEST['texto']);
    
    if (!$para || empty($asunto) || empty($texto)) {
        header("Location: error_handler.php?t=login");
        exit();
    }
    
    $file = $_FILES['file']['name'];

    if ($file == "") {
        $file = "vacio";
    } else {
        // Validar archivo
        require_once('includes/upload_security.php');
        $uploadFile = validarArchivo($_FILES['file'], ['pdf', 'doc', 'docx', 'txt', 'jpg', 'png'], 5242880);
        
        if (!$uploadFile['success']) {
            header("Location: error_handler.php?t=login");
            exit();
        }
        
        $f = $uploadFile['nombre_seguro'];
        move_uploaded_file($_FILES['file']['tmp_name'], "archivos/ArchivosEmail/" . $f);
        $file = $f;
    }

    $stmt_insert = $cont->prepare("INSERT INTO mensaje VALUES(NULL, ?, ?, NULL, NULL, ?, ?, ?, ?)");
    $stmt_insert->bind_param("ssssss", $para, $u, $asunto, $texto, $c, $file);
    $stmt_insert->execute();
    $stmt_insert->close();
    
    header("location:email.php");
}

if (isset($_REQUEST['enviartodo'])) {
    // Validar token CSRF
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        header("Location: error_handler.php?t=login");
        exit();
    }
    
    $u = $_SESSION['user'];
    $c = $_SESSION['clave'];
    $asunto = trim($_REQUEST['asunto']);
    $texto = trim($_REQUEST['texto']);
    
    if (empty($asunto) || empty($texto)) {
        header("Location: error_handler.php?t=login");
        exit();
    }
    
    $file = $_FILES['file']['name'];

    $stmt_correo = $cont->prepare("SELECT * FROM misclases, usuarios WHERE misclases.usuario=usuarios.Email AND misclases.clave = ?");
    $stmt_correo->bind_param("s", $_SESSION['clave']);
    $stmt_correo->execute();
    $resultadocorreo = $stmt_correo->get_result();
    $ncorreo = mysqli_num_rows($resultadocorreo);
    $alumnoscorreo = mysqli_fetch_assoc($resultadocorreo);

    if ($file == "") {
        $file = "vacio";
    } else {
        require_once('includes/upload_security.php');
        $uploadFile = validarArchivo($_FILES['file'], ['pdf', 'doc', 'docx', 'txt', 'jpg', 'png'], 5242880);
        
        if (!$uploadFile['success']) {
            header("Location: error_handler.php?t=login");
            exit();
        }
        
        $f = $uploadFile['nombre_seguro'];
        move_uploaded_file($_FILES['file']['tmp_name'], "archivos/ArchivosEmail/" . $f);
        $file = $f;
    }
    
    do {
        $para = $alumnoscorreo['Email'];
        $stmt_insert = $cont->prepare("INSERT INTO mensaje VALUES(NULL, ?, ?, NULL, NULL, ?, ?, ?, ?)");
        $stmt_insert->bind_param("ssssss", $para, $u, $asunto, $texto, $c, $file);
        $stmt_insert->execute();
        $stmt_insert->close();
    } while ($alumnoscorreo = mysqli_fetch_assoc($resultadocorreo));
    
    $stmt_correo->close();
    header("location:email.php");
}

include('includes/encabezado.php')

?>
