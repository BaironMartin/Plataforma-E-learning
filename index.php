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

// Procesar Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($usuario) || empty($password)) {
        $error = "Por favor ingrese usuario y contraseña.";
    } else {
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            
            // Buscar usuario (ajusta 'usuarios' y las columnas según tu BD real)
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ? OR email = ?");
            $stmt->execute([$usuario, $usuario]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Login exitoso
                $_SESSION['id'] = $user['id'];
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['rol'] = $user['rol'] ?? 'user';
                
                // Redirigir según rol
                if ($user['rol'] === 'admin') {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit;
            } else {
                $error = "Usuario o contraseña incorrectos.";
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
    <title>Iniciar Sesión</title>
    <style>
        :root { --primary: #2563eb; --bg: #f3f4f6; --text: #1f2937; }
        body { font-family: system-ui, sans-serif; background: var(--bg); display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; color: var(--text); }
        .container { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; margin-bottom: 1.5rem; color: var(--primary); }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        input { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 1rem; }
        input:focus { outline: none; border-color: var(--primary); ring: 2px solid var(--primary); }
        button { width: 100%; padding: 0.75rem; background: var(--primary); color: white; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        button:hover { background: #1d4ed8; }
        .alert { padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .links { text-align: center; margin-top: 1rem; font-size: 0.9rem; }
        .links a { color: var(--primary); text-decoration: none; }
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bienvenido</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="usuario">Usuario o Email</label>
                <input type="text" id="usuario" name="usuario" required placeholder="Ingresa tu usuario">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit">Ingresar</button>
        </form>

        <div class="links">
            ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
        </div>
    </div>
</body>
</html>
