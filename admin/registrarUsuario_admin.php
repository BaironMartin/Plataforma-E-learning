<?php

include('../includes/conectar.php');
require_once('../includes/upload_security.php');


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

// Consulta segura del admin actual
$stmt = $cont->prepare("SELECT * FROM admin_p WHERE email = ?");
$stmt->bind_param("s", $_SESSION['user_admin']);
$stmt->execute();
$resultado = $stmt->get_result();
$a = mysqli_fetch_assoc($resultado);
$stmt->close();

if (isset($_REQUEST['mail']) && !empty($_REQUEST['mail'])) {
    // Validar token CSRF
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        header("Location: error_handler.php?t=login");
        exit();
    }
    
    $u = filter_input(INPUT_POST, 'mail', FILTER_VALIDATE_EMAIL);
    $p = $_REQUEST['pass'];
    $n = trim($_REQUEST['name']);
    $cc = trim($_REQUEST['cc']);
    $t = trim($_REQUEST['tipo']);
    $g = trim($_REQUEST['grado']);
    
    // Validaciones básicas
    if (!$u || empty($p) || empty($n) || empty($cc) || !in_array($t, ['Docente', 'Estudiante'])) {
        header("Location: error_handler.php?t=login");
        exit();
    }
    
    // Validar archivo subido
    $uploadPerfil = validarArchivo($_FILES['perfil'], ['jpg', 'jpeg', 'png', 'gif'], 5242880);
    
    if (!$uploadPerfil['success']) {
        header("Location: error_handler.php?t=login");
        exit();
    }
    
    $f = $uploadPerfil['nombre_seguro'];

    // Hash seguro con bcrypt
    $p_hash = password_hash($p, PASSWORD_BCRYPT);
    
    // Verificar si el email o cc ya existen
    $stmt_check_email = $cont->prepare("SELECT Email FROM usuarios WHERE Email = ?");
    $stmt_check_email->bind_param("s", $u);
    $stmt_check_email->execute();
    $result_email = $stmt_check_email->get_result();
    
    $stmt_check_cc = $cont->prepare("SELECT cc FROM usuarios WHERE cc = ?");
    $stmt_check_cc->bind_param("s", $cc);
    $stmt_check_cc->execute();
    $result_cc = $stmt_check_cc->get_result();
    
    if (mysqli_num_rows($result_email) == 0 && mysqli_num_rows($result_cc) == 0) {
        // Insertar con prepared statement
        $stmt_insert = $cont->prepare("INSERT INTO usuarios VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_insert->bind_param("sssssss", $u, $p_hash, $n, $cc, $f, $t, $g);
        
        if ($stmt_insert->execute()) {
            // Mover archivo con nombre seguro
            move_uploaded_file($_FILES['perfil']['tmp_name'], "../archivos/" . $f);
            header("Location: registrarUsuario_admin.php?registro=exitoso");
        } else {
            header("Location: error_handler.php?t=login");
        }
        $stmt_insert->close();
    } elseif (mysqli_num_rows($result_email) > 0) {
        header("Location: error_handler.php?t=admin");
    } else {
        header("Location: error_handler.php?t=registro");
    }
    
    $stmt_check_email->close();
    $stmt_check_cc->close();
}

include('includes/header.php');
include('includes/menu.php');

?>
