<?php

// Cargar variables de entorno
$env_file = __DIR__ . '/../.env';
if (file_exists($env_file)) {
    $env_vars = parse_ini_file($env_file);
    foreach ($env_vars as $key => $value) {
        putenv("$key=$value");
    }
}

// Configuración segura de sesiones
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', getenv('SESSION_HTTPONLY') ?: true);
    ini_set('session.cookie_secure', getenv('SESSION_SECURE') ?: false);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
}

$servidor = getenv('DB_HOST') ?: "localhost";
$usuario = getenv('DB_USER') ?: "root";
$contraseña = getenv('DB_PASS') ?: "";
$base = getenv('DB_NAME') ?: "plataforma";

$cont = mysqli_connect($servidor, $usuario, $contraseña, $base);
if (!$cont) {
    error_log("Conexión fallida: " . mysqli_connect_error());
    die("Error de conexión a la base de datos");
}

// Configurar charset para prevenir problemas de encoding
mysqli_set_charset($cont, "utf8mb4");

?>
