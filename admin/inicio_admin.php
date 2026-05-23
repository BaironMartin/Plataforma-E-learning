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

// Prepared statement para consulta de admin
$stmt = $cont->prepare("SELECT * FROM admin_p WHERE email = ?");
$stmt->bind_param("s", $_SESSION['user_admin']);
$stmt->execute();
$resultado = $stmt->get_result();
$a = mysqli_fetch_assoc($resultado);
$stmt->close();

if (isset($_REQUEST['nom']) && !empty($_REQUEST['nom'])) {
    // Validar token CSRF
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        header("Location: errors/errorlogin.php");
        exit();
    }
    
    $n = trim($_REQUEST['nom']);
    $ap = trim($_REQUEST['ape']);
    $mail = filter_input(INPUT_POST, 'mail', FILTER_VALIDATE_EMAIL);
    $pas = $_REQUEST['pass'];
    $car = trim($_REQUEST['car']);
    $cel = trim($_REQUEST['cel']);
    $edad = intval($_REQUEST['edad']);
    
    // Validaciones básicas
    if (!$mail || empty($pas) || $edad <= 0) {
        header("Location: errors/errorlogin.php");
        exit();
    }
    
    // Validar archivos subidos
    $uploadPerfil = validarArchivo($_FILES['perfil'], ['jpg', 'jpeg', 'png', 'gif'], 5242880);
    $uploadBane = validarArchivo($_FILES['bane'], ['jpg', 'jpeg', 'png', 'gif'], 5242880);
    
    if (!$uploadPerfil['success'] || !$uploadBane['success']) {
        header("Location: errors/errorlogin.php");
        exit();
    }
    
    $fp = $uploadPerfil['nombre_seguro'];
    $fb = $uploadBane['nombre_seguro'];

    // Hash seguro con bcrypt
    $pas_hash = password_hash($pas, PASSWORD_BCRYPT);
    
    // Verificar si el email ya existe
    $stmt_check = $cont->prepare("SELECT email FROM admin_p WHERE email = ?");
    $stmt_check->bind_param("s", $mail);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if (mysqli_num_rows($result_check) == 0) {
        // Insertar con prepared statement
        $stmt_insert = $cont->prepare("INSERT INTO admin_p VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_insert->bind_param("ssssssiss", $n, $ap, $mail, $pas_hash, $car, $cel, $edad, $fp, $fb);
        
        if ($stmt_insert->execute()) {
            // Mover archivos con nombres seguros
            move_uploaded_file($_FILES['perfil']['tmp_name'], "archivos/perfil/" . $fp);
            move_uploaded_file($_FILES['bane']['tmp_name'], "archivos/baner/" . $fb);
            header("Location: inicio_admin.php?registro=exitoso");
        } else {
            header("Location: errors/errorlogin.php");
        }
        $stmt_insert->close();
    } else {
        header("Location: errors/errorlogin2.php");
    }
    
    $stmt_check->close();
}
