<?php
// session_start(); // Ya se inicia en AuthController o en el index.php principal si es necesario globalmente.
// Asegurarse que la sesión se inicie antes de cualquier salida.
// Si index.php es el único punto de entrada, session_start() allí sería lo ideal.
// Por ahora, asumimos que AuthController la maneja o que index.php la inicia.

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/BaseController.php'; // Para funcionalidades comunes como checkAuth

/**
 * Controlador para el dashboard principal del usuario después de iniciar sesión.
 */
class DashboardController extends BaseController {
    private $userModel;

    public function __construct() {
        parent::__construct(); // Llama al constructor de BaseController para verificar auth
        $this->userModel = new User();
    }

    /**
     * Muestra la página principal del dashboard del usuario.
     * Obtiene los datos del usuario actual y los pasa a la vista.
     */
    public function index() {
        // BaseController ya verifica la autenticación y redirige si no está logueado.
        // Si necesitamos específicamente el user_id/email aquí, lo obtenemos de la sesión.
        $userEmail = $_SESSION['user_email'] ?? null;

        if (!$userEmail) {
            // Esto no debería ocurrir si BaseController funciona bien, pero como salvaguarda:
            header('Location: index.php?action=login&error=session_expired');
            exit;
        }

        $userData = $this->userModel->findByEmail($userEmail);

        if (!$userData) {
            // Usuario no encontrado en BD, aunque estaba en sesión (raro, pero posible)
            // Invalidar sesión y redirigir a login
            session_destroy();
            header('Location: index.php?action=login&error=user_not_found');
            exit;
        }

        // Pasar datos a la vista
        $pageTitle = "Inicio - Plataforma"; // Título para el layout

        // Cargar la vista del dashboard
        // Las variables $pageTitle y $userData estarán disponibles en el scope de la vista.
        require_once __DIR__ . '/../views/dashboard/index.php';
    }

    // La funcionalidad de logout ya está en AuthController.
    // Si inicio.php tenía un ?cerrar=1, el index.php (router) debería dirigirlo a index.php?action=logout
}
?>
