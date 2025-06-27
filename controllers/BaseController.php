<?php
// Iniciar sesión si no se ha iniciado ya.
// Es mejor tener session_start() en un punto de entrada único como index.php
// o al principio de cada controlador que la necesite, asegurándose que sea antes de cualquier salida.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Controlador base para verificar la autenticación y el timeout de sesión.
 * Otros controladores que requieren que el usuario esté logueado deben extender esta clase.
 */
class BaseController {
    public function __construct() {
        $this->checkAuthentication();
    }

    /**
     * Verifica si el usuario está autenticado y si la sesión no ha expirado.
     * Redirige a la página de login si no está autenticado o la sesión ha expirado.
     * Actualiza el timestamp de la última actividad en la sesión.
     */
    protected function checkAuthentication() {
        // Verificar si el usuario está logueado.
        // 'user_id' es lo que establecimos en AuthController tras un login exitoso.
        // También se podría chequear 'user_email' u otra variable de sesión consistente.
        if (!isset($_SESSION['user_id'])) {
            // Si no está logueado, redirigir a la página de login.
            // Es importante usar exit después de header para detener la ejecución del script.
            header('Location: index.php?action=login&reason=unauthenticated');
            exit;
        }

        // Opcional: Verificar el tiempo de la sesión para expiración automática
        $sessionTimeout = 1800; // 30 minutos (ejemplo, igual que en admin/)
        if (isset($_SESSION['time']) && (time() - $_SESSION['time']) > $sessionTimeout) {
            session_unset(); // Limpiar todas las variables de sesión
            session_destroy(); // Destruir la sesión
            header('Location: index.php?action=login&reason=session_expired');
            exit;
        }
        // Actualizar el tiempo de la última actividad para extender la sesión
        $_SESSION['time'] = time();
    }
}
?>
