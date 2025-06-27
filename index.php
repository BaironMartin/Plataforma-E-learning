<?php
// Punto de entrada principal de la aplicación (Front Controller)

// Cargar configuración básica o autoloader si se usa (ej. Composer)
// require_once 'config/config.php'; // Si tuvieras configuraciones centralizadas
// require_once 'vendor/autoload.php'; // Si usaras Composer

require_once __DIR__ . '/controllers/AuthController.php';
// Agrega aquí otros controladores a medida que los vayas creando
// require_once __DIR__ . '/controllers/ClaseController.php';
// require_once __DIR__ . '/controllers/TareaController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/ClaseController.php';
require_once __DIR__ . '/controllers/PlanController.php'; // <--- AÑADIDO
// ...etc.

// Determinar la acción solicitada (ej. a través de un parámetro GET)
// Asegurar que session_start() se llame antes de cualquier acceso a $_SESSION o salida.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$action = $_GET['action'] ?? (isset($_SESSION['user_id']) ? 'dashboard' : 'login'); // Acción por defecto

// Instanciar el controlador y llamar al método apropiado
// Esto es un enrutador muy básico. Un sistema más robusto usaría expresiones regulares o una librería de enrutamiento.

switch ($action) {
    case 'login':
        $authController = new AuthController();
        $authController->login();
        break;
    case 'register':
        $authController = new AuthController();
        $authController->register();
        break;
    case 'logout':
        $authController = new AuthController();
        $authController->logout();
        break;
    case 'dashboard': // <--- AÑADIDO
        $dashboardController = new DashboardController();
        $dashboardController->index();
        break;
    case 'manageClases': // <--- AÑADIDO (Docentes)
        $claseController = new ClaseController();
        $claseController->manageClases();
        break;
    case 'storeClase': // <--- AÑADIDO (Docentes)
        $claseController = new ClaseController();
        $claseController->storeClase();
        break;
    case 'deleteClase': // <--- AÑADIDO (Docentes)
        $claseController = new ClaseController();
        $claseController->deleteClase();
        break;
    case 'listStudentClases': // <--- AÑADIDO (Estudiantes)
        $claseController = new ClaseController();
        $claseController->listStudentClases();
        break;
    case 'joinClase': // <--- AÑADIDO (Estudiantes)
        $claseController = new ClaseController();
        $claseController->joinClase();
        break;
    case 'leaveClase': // <--- AÑADIDO (Estudiantes)
        $claseController = new ClaseController();
        $claseController->leaveClase();
        break;
    case 'viewPlan': // <--- AÑADIDO (Docentes y Estudiantes)
        $planController = new PlanController();
        $planController->viewPlan();
        break;
    case 'storeActividad': // <--- AÑADIDO (Docentes)
        $planController = new PlanController();
        $planController->storeActividad();
        break;
    case 'updateActividad': // <--- AÑADIDO (Docentes)
        $planController = new PlanController();
        $planController->updateActividad();
        break;
    case 'destroyActividad': // <--- AÑADIDO (Docentes)
        $planController = new PlanController();
        $planController->destroyActividad();
        break;
    // --- Tareas Controller Actions ---
    case 'listTareasEstudiante': // Estudiante: lista actividades de una clase para entregar
        require_once __DIR__ . '/controllers/TareaController.php';
        $tareaController = new TareaController();
        $tareaController->listTareasEstudiante();
        break;
    case 'viewTarea': // Estudiante: ve detalle/formulario de entrega. Docente: ve lista de entregas para calificar.
        require_once __DIR__ . '/controllers/TareaController.php';
        $tareaController = new TareaController();
        $tareaController->viewTarea();
        break;
    case 'ensureStudentTareaEntry': // Estudiante: se "inscribe" a una tarea si se unió tarde a clase
        require_once __DIR__ . '/controllers/TareaController.php';
        $tareaController = new TareaController();
        $tareaController->ensureStudentTareaEntry();
        break;
    case 'submitEntrega': // Estudiante: procesa el envío de su tarea
        require_once __DIR__ . '/controllers/TareaController.php';
        $tareaController = new TareaController();
        $tareaController->submitEntrega();
        break;
    case 'deleteStudentEntrega': // Estudiante: elimina su propia entrega (si no calificada)
        require_once __DIR__ . '/controllers/TareaController.php';
        $tareaController = new TareaController();
        $tareaController->deleteStudentEntrega();
        break;
    case 'processCalificacion': // Docente: procesa la calificación de una tarea
        require_once __DIR__ . '/controllers/TareaController.php';
        $tareaController = new TareaController();
        $tareaController->processCalificacion();
        break;
    // --- Calificaciones Controller Actions ---
    case 'viewCalificaciones': // Docente: ve resumen de promedios. Estudiante: ve sus calificaciones.
        require_once __DIR__ . '/controllers/CalificacionController.php';
        $calificacionController = new CalificacionController();
        $calificacionController->viewCalificaciones();
        break;
    // --- Reportes Controller Actions ---
    case 'generarReporteClase': // Docente: Reporte general de la clase
        require_once __DIR__ . '/controllers/ReporteController.php';
        $reporteController = new ReporteController();
        $reporteController->generarReporteClase();
        break;
    case 'generarReporteIndividual': // Docente: Reporte individual de un alumno
        require_once __DIR__ . '/controllers/ReporteController.php';
        $reporteController = new ReporteController();
        $reporteController->generarReporteIndividualAlumno();
        break;
    case 'generarReporteEstudiante': // Estudiante: Su propio reporte de calificaciones
        require_once __DIR__ . '/controllers/ReporteController.php';
        $reporteController = new ReporteController();
        $reporteController->generarReporteMisCalificaciones();
        break;
    // --- Profile Controller Actions ---
    case 'editProfile': // Muestra el formulario para editar el perfil del usuario logueado
        require_once __DIR__ . '/controllers/ProfileController.php';
        $profileController = new ProfileController();
        $profileController->edit();
        break;
    case 'updateProfile': // Procesa la actualización del perfil
        require_once __DIR__ . '/controllers/ProfileController.php';
        $profileController = new ProfileController();
        $profileController->update();
        break;
    // --- PasswordRecovery Controller Actions ---
    case 'setupSecurityQuestions': // Formulario para que usuario logueado configure preguntas
        require_once __DIR__ . '/controllers/PasswordRecoveryController.php';
        $recoveryController = new PasswordRecoveryController();
        $recoveryController->setupQuestionsForm();
        break;
    case 'saveQuestions': // Procesa el guardado de las preguntas de seguridad
        require_once __DIR__ . '/controllers/PasswordRecoveryController.php';
        $recoveryController = new PasswordRecoveryController();
        $recoveryController->saveQuestions();
        break;
    case 'forgotPassword': // Formulario para restaurar contraseña (usuario no logueado)
        require_once __DIR__ . '/controllers/PasswordRecoveryController.php';
        $recoveryController = new PasswordRecoveryController();
        $recoveryController->forgotPasswordForm();
        break;
    case 'processPasswordReset': // Procesa la restauración de contraseña
        require_once __DIR__ . '/controllers/PasswordRecoveryController.php';
        $recoveryController = new PasswordRecoveryController();
        $recoveryController->processPasswordReset();
        break;
    // --- ClaseController (participantes) ---
    case 'viewParticipantes':
        // ClaseController ya está requerido arriba para otras acciones de clase
        $claseController = new ClaseController();
        $claseController->viewParticipantes();
        break;
    case 'removeParticipante': // Docente elimina alumno de clase
        $claseController = new ClaseController();
        $claseController->removeParticipante();
        break;
    // --- Biblioteca Controller Actions ---
    case 'viewBiblioteca':
        require_once __DIR__ . '/controllers/BibliotecaController.php';
        $bibliotecaController = new BibliotecaController();
        $bibliotecaController->viewBiblioteca();
        break;
    case 'storeReferencia': // Docente
        require_once __DIR__ . '/controllers/BibliotecaController.php';
        $bibliotecaController = new BibliotecaController();
        $bibliotecaController->storeReferencia();
        break;
    case 'updateReferencia': // Docente
        require_once __DIR__ . '/controllers/BibliotecaController.php';
        $bibliotecaController = new BibliotecaController();
        $bibliotecaController->updateReferencia();
        break;
    case 'destroyReferencia': // Docente
        require_once __DIR__ . '/controllers/BibliotecaController.php';
        $bibliotecaController = new BibliotecaController();
        $bibliotecaController->destroyReferencia();
        break;
    // Casos para otras funcionalidades (después de crear sus controladores y vistas)
    // case 'crearClase': // Esto se integra en manageClases
    //     // $claseController = new ClaseController();
    //     // $claseController->create();
    //     break;
    // case 'verClases':
    //     // $claseController = new ClaseController();
    //     // $claseController->index();
    //     break;
    default:
        // Acción no reconocida, mostrar página de error o redirigir al login
        // Por ahora, simple error.
        // header("HTTP/1.0 404 Not Found");
        // require_once __DIR__ . '/views/errors/404.php'; // Crear esta vista
        // O redirigir a login o dashboard según el estado de la sesión
        if (isset($_SESSION['user_id'])) {
            $dashboardController = new DashboardController();
            $dashboardController->index();
        } else {
            $authController = new AuthController();
            $authController->login();
        }
        break;
}

?>
