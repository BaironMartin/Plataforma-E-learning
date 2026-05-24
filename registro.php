<?php
session_start();

// Configuración de base de datos
$host = 'localhost';
$db   = 'nombre_base_datos'; // CAMBIAR POR TU BD
$user = 'root';              // CAMBIAR POR TU USUARIO
$pass = '';                  // CAMBIAR POR TU CONTRASEÑA
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$error = '';
$success = '';

// Procesar Registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $cc = trim($_POST['cc'] ?? '');
    $grado = trim($_POST['grado'] ?? '');

    // Validaciones
    if (empty($usuario) || empty($email) || empty($password) || empty($cc) || empty($grado)) {
        $error = "Todos los campos son obligatorios.";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El email no es válido.";
    } else {
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            
            // Verificar si el usuario o email ya existen
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ? OR email = ?");
            $stmt->execute([$usuario, $email]);
            
            if ($stmt->fetch()) {
                $error = "El usuario o email ya está registrado.";
            } else {
                // Hash de contraseña
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Insertar usuario
                $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, email, password, cc, grado, rol) VALUES (?, ?, ?, ?, ?, 'user')");
                $stmt->execute([$usuario, $email, $password_hash, $cc, $grado]);
                
                $success = "Registro exitoso. Ahora puedes iniciar sesión.";
            }
        } catch (PDOException $e) {
            $error = "Error de conexión: " . $e->getMessage();
        }
    }
}

// Si ya está logueado, redirigir
if (isset($_SESSION['id'])) {
    if (($_SESSION['rol'] ?? '') === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <style>
        :root { --primary: #2563eb; --bg: #f3f4f6; --text: #1f2937; }
        body { font-family: system-ui, sans-serif; background: var(--bg); display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; color: var(--text); padding: 1rem; }
        .container { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); width: 100%; max-width: 450px; }
        h2 { text-align: center; margin-bottom: 1.5rem; color: var(--primary); }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        input, select { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 1rem; }
        input:focus, select:focus { outline: none; border-color: var(--primary); ring: 2px solid var(--primary); }
        button { width: 100%; padding: 0.75rem; background: var(--primary); color: white; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        button:hover { background: #1d4ed8; }
        .alert { padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .links { text-align: center; margin-top: 1rem; font-size: 0.9rem; }
        .links a { color: var(--primary); text-decoration: none; }
        .links a:hover { text-decoration: underline; }
        .row { display: flex; gap: 1rem; }
        .row .form-group { flex: 1; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Crear Cuenta</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="usuario">Usuario *</label>
                <input type="text" id="usuario" name="usuario" required placeholder="Nombre de usuario">
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required placeholder="tu@email.com">
            </div>
            
            <div class="row">
                <div class="form-group">
                    <label for="cc">CC/DNI *</label>
                    <input type="text" id="cc" name="cc" required placeholder="Número de documento">
                </div>
                <div class="form-group">
                    <label for="grado">Grado/Cargo *</label>
                    <input type="text" id="grado" name="grado" required placeholder="Ej: Estudiante, Profesor">
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña *</label>
                <input type="password" id="password" name="password" required placeholder="Mínimo 6 caracteres" minlength="6">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmar Contraseña *</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Repite la contraseña" minlength="6">
            </div>
            
            <button type="submit">Registrarse</button>
        </form>

        <div class="links">
            ¿Ya tienes cuenta? <a href="index.php">Inicia sesión aquí</a>
        </div>
    </div>
</body>
</html>
