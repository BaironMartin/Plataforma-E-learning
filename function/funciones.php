<?php

function login_Index($u, $p){
    global $cont;
    
    // Hash seguro con bcrypt
    $hash = password_hash($p, PASSWORD_BCRYPT);
    
    // Prepared statement para prevenir SQL injection
    $stmt = mysqli_prepare($cont, "SELECT * FROM usuarios WHERE Email=?");
    mysqli_stmt_bind_param($stmt, "s", $u);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verificar password con password_verify
        if (password_verify($p, $user['Clave']) || hash('sha512', $p) == $user['Clave']) {
            // Regenerar ID de sesión para prevenir session fixation
            session_regenerate_id(true);
            $_SESSION['user'] = $u;
            $_SESSION['time'] = time();
            
            // Migrar a bcrypt si estaba usando SHA-512
            if (hash('sha512', $p) == $user['Clave']) {
                $update_stmt = mysqli_prepare($cont, "UPDATE usuarios SET Clave=? WHERE Email=?");
                $new_hash = password_hash($p, PASSWORD_BCRYPT);
                mysqli_stmt_bind_param($update_stmt, "ss", $new_hash, $u);
                mysqli_stmt_execute($update_stmt);
                mysqli_stmt_close($update_stmt);
            }
            
            header("Location: inicio.php");
        } else {
            header("Location: Errors/errorlogin1.php");
        }
    } else {
        header("Location: Errors/errorlogin1.php");
    }
    
    mysqli_stmt_close($stmt);
    mysqli_free_result($result);
}

function registrer_Index($u, $p, $n, $cc, $f, $t, $g){
    global $cont;
    
    // Validar archivo subido
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        header("Location: Errors/errorlogin.php");
        return;
    }
    
    $file_type = $_FILES['photo']['type'];
    $file_size = $_FILES['photo']['size'];
    $file_ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_type, $allowed_types) || !in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        header("Location: Errors/errorlogin.php");
        return;
    }
    
    if ($file_size > $max_size) {
        header("Location: Errors/errorlogin.php");
        return;
    }
    
    // Hash seguro con bcrypt
    $p_hash = password_hash($p, PASSWORD_BCRYPT);
    
    // Prepared statements para prevenir SQL injection
    $stmt = mysqli_prepare($cont, "SELECT * FROM usuarios WHERE Email=? OR cc=?");
    mysqli_stmt_bind_param($stmt, "ss", $u, $cc);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 0) {
        // Insertar nuevo usuario
        $insert_stmt = mysqli_prepare($cont, "INSERT INTO usuarios VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($insert_stmt, "sssssss", $u, $p_hash, $n, $cc, $f, $t, $g);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            // Generar nombre seguro para el archivo
            $safe_filename = preg_replace("/[^a-zA-Z0-9._-]/", "", $f);
            $new_filename = $u . '_' . time() . '_' . $safe_filename;
            
            move_uploaded_file($_FILES['photo']['tmp_name'], "archivos/" . $new_filename);
            
            mysqli_stmt_close($insert_stmt);
            mysqli_stmt_close($stmt);
            header("Location: index.php");
            return;
        }
    }
    
    mysqli_stmt_close($stmt);
    
    // Verificar qué error fue
    $check_email = mysqli_prepare($cont, "SELECT * FROM usuarios WHERE Email=?");
    mysqli_stmt_bind_param($check_email, "s", $u);
    mysqli_stmt_execute($check_email);
    
    if (mysqli_num_rows(mysqli_stmt_get_result($check_email)) > 0) {
        header("Location: Errors/errorlogin3.php");
    } else {
        header("Location: Errors/errorlogin2.php");
    }
    
    mysqli_stmt_close($check_email);
}

?>