<?php
require_once __DIR__ . '/../models/Clase.php';
require_once __DIR__ . '/../models/User.php'; // Para verificar tipo de usuario
require_once __DIR__ . '/BaseController.php';

class ClaseController extends BaseController {
    private $claseModel;
    private $userModel;

    public function __construct() {
        parent::__construct(); // Asegura que el usuario esté autenticado
        $this->claseModel = new Clase();
        $this->userModel = new User(); // Necesario para verificar si es Docente

        // Verificar si el usuario es Docente para todas las acciones de este controlador
        // (excepto si hubiera acciones específicas para estudiantes relacionadas con clases)
        $currentUserEmail = $_SESSION['user_email'] ?? null;
        if ($currentUserEmail) {
            $currentUser = $this->userModel->findByEmail($currentUserEmail);
            if (!$currentUser || $currentUser['Tipo'] !== 'Docente') {
                // Si no es docente, no debería estar aquí. Redirigir o mostrar error.
                // $_SESSION['error_message'] = "Acceso no autorizado a esta sección.";
                // Esta verificación es específica para las acciones de DOCENTE.
                // Las acciones de estudiante tendrán su propia lógica o estarán en otro controlador.
                // Por ahora, la comentamos si vamos a añadir acciones de estudiante aquí.
                // header('Location: index.php?action=dashboard&error=unauthorized_clase_management');
                // exit;
            }
        } else {
            // Esto no debería pasar si BaseController funciona, pero por si acaso.
            header('Location: index.php?action=login&error=session_issue');
            exit;
        }
    }

    // Muestra el formulario de creación y la lista de clases del docente
    public function manageClases() { // DOCENTE
        $this->checkUserType('Docente', 'dashboard', 'unauthorized_teacher_action');

        $usuarioEmail = $_SESSION['user_email'];
        $clases = $this->claseModel->getClasesByUsuario($usuarioEmail);

        $pageTitle = "Gestionar Mis Clases";
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        require_once __DIR__ . '/../views/clases/manage_docente.php'; // Renombrar vista para claridad
    }

    // Procesa la creación de una nueva clase
    public function storeClase() { // DOCENTE
        $this->checkUserType('Docente', 'manageClases', 'unauthorized_teacher_action');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['clase_nombre'] ?? '';
            $imagen = $_POST['clase_imagen'] ?? '';
            $grado = $_POST['clase_grado'] ?? '';
            $usuarioEmail = $_SESSION['user_email'];

            if (empty($nombre) || empty($imagen) || empty($grado)) {
                $_SESSION['error_message'] = "Todos los campos son requeridos para crear una clase.";
            } else {
                $result = $this->claseModel->createClase($nombre, $imagen, $grado, $usuarioEmail);
                if ($result) {
                    $_SESSION['success_message'] = "Clase creada exitosamente.";
                } else {
                    $_SESSION['error_message'] = "Error al crear la clase.";
                }
            }
        }
        header('Location: index.php?action=manageClases');
        exit;
    }

    // Procesa la eliminación de una clase por el docente
    public function deleteClase() { // DOCENTE
        $this->checkUserType('Docente', 'manageClases', 'unauthorized_teacher_action');

        if (isset($_GET['id'])) {
            $idClase = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            $usuarioEmail = $_SESSION['user_email'];

            if (!$idClase) {
                 $_SESSION['error_message'] = "ID de clase inválido.";
            } else {
                $clase = $this->claseModel->findClaseByIdAndUsuario($idClase, $usuarioEmail); // Verifica propiedad
                if ($clase) {
                    if ($this->claseModel->deleteClase($idClase)) {
                        $_SESSION['success_message'] = "Clase eliminada exitosamente.";
                    } else {
                        $_SESSION['error_message'] = "Error al eliminar la clase. Puede tener tareas o alumnos inscritos.";
                    }
                } else {
                    $_SESSION['error_message'] = "No se encontró la clase o no tienes permiso para eliminarla.";
                }
            }
        } else {
            $_SESSION['error_message'] = "Solicitud de eliminación inválida.";
        }
        header('Location: index.php?action=manageClases');
        exit;
    }

    // --- ACCIONES PARA ESTUDIANTES ---
    private $misClasesModel;

    private function initMisClasesModel() {
        if ($this->misClasesModel === null) {
            require_once __DIR__ . '/../models/MisClases.php';
            $this->misClasesModel = new MisClases();
        }
    }

    private function checkUserType($expectedType, $redirectAction = 'dashboard', $errorMessageKey = 'unauthorized_access') {
        $currentUserEmail = $_SESSION['user_email'] ?? null;
        if (!$currentUserEmail) { // No debería llegar aquí si BaseController funciona
            header('Location: index.php?action=login&error=session_expired');
            exit;
        }
        $user = $this->userModel->findByEmail($currentUserEmail);
        if (!$user || $user['Tipo'] !== $expectedType) {
            $_SESSION['error_message'] = "Acceso no autorizado."; // Mensaje genérico
            header("Location: index.php?action={$redirectAction}&error={$errorMessageKey}");
            exit;
        }
        return $user; // Devuelve el usuario por si se necesita
    }


    public function listStudentClases() { // ESTUDIANTE
        $this->checkUserType('Estudiante', 'dashboard', 'unauthorized_student_action');
        $this->initMisClasesModel();

        $usuarioEmail = $_SESSION['user_email'];
        $clasesInscritas = $this->misClasesModel->getClasesByUsuarioEmail($usuarioEmail);

        $pageTitle = "Mis Clases / Unirme";
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        require_once __DIR__ . '/../views/clases/student_manage.php';
    }

    public function joinClase() { // ESTUDIANTE
        $this->checkUserType('Estudiante', 'listStudentClases', 'unauthorized_student_action');
        $this->initMisClasesModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clave_clase'])) {
            $claveClase = trim($_POST['clave_clase']);
            $usuarioEmail = $_SESSION['user_email'];

            if (empty($claveClase)) {
                $_SESSION['error_message'] = "Debe ingresar la clave de la clase.";
            } else {
                $claseInfo = $this->claseModel->findClaseByClave($claveClase);
                if (!$claseInfo) {
                    $_SESSION['error_message'] = "La clase con la clave proporcionada no existe.";
                } elseif ($this->misClasesModel->isUsuarioInClase($usuarioEmail, $claveClase)) {
                    $_SESSION['error_message'] = "Ya estás inscrito en esta clase.";
                } else {
                    if ($this->misClasesModel->addUsuarioToClase($usuarioEmail, $claveClase)) {
                        $_SESSION['success_message'] = "Te has unido a la clase '" . htmlspecialchars($claseInfo['nombre']) . "' exitosamente.";
                    } else {
                        $_SESSION['error_message'] = "Error al unirse a la clase. Inténtalo de nuevo.";
                    }
                }
            }
        }
        header('Location: index.php?action=listStudentClases');
        exit;
    }

    public function leaveClase() { // ESTUDIANTE
        $this->checkUserType('Estudiante', 'listStudentClases', 'unauthorized_student_action');
        $this->initMisClasesModel();

        if (isset($_GET['idmiclase'])) {
            $idMiClase = filter_var($_GET['idmiclase'], FILTER_VALIDATE_INT);
            $usuarioEmail = $_SESSION['user_email'];

            if (!$idMiClase) {
                $_SESSION['error_message'] = "ID de inscripción inválido.";
            } else {
                // MisClasesModel->removeUsuarioFromClaseById ya verifica que el idmiclase pertenezca al usuario
                if ($this->misClasesModel->removeUsuarioFromClaseById($idMiClase, $usuarioEmail)) {
                    $_SESSION['success_message'] = "Has salido de la clase exitosamente.";
                } else {
                    $_SESSION['error_message'] = "Error al salir de la clase o no estabas inscrito.";
                }
            }
        } else {
            $_SESSION['error_message'] = "Solicitud para salir de clase inválida.";
        }
        header('Location: index.php?action=listStudentClases');
        exit;
    }

    // --- Acción para ver participantes ---
    public function viewParticipantes() {
        if (!isset($_GET['clave'])) {
            $_SESSION['error_message'] = "No se especificó la clase para ver los participantes.";
            header('Location: index.php?action=dashboard');
            exit;
        }
        $claveClase = $_GET['clave'];
        $_SESSION['current_clave_clase_participantes'] = $claveClase;

        // checkClaseAccess verifica que el usuario actual (docente o estudiante) tenga acceso a esta clase.
        $accessInfo = $this->checkClaseAccess($claveClase);
        $claseActual = $accessInfo['clase'];
        $usuarioActual = $accessInfo['user']; // Usuario que está viendo la página

        // Obtener el docente/creador de la clase
        $docenteDeLaClase = $this->userModel->findByEmail($claseActual['usuario']);

        // Obtener los alumnos inscritos
        $this->initMisClasesModel(); // Asegurar que $misClasesModel esté inicializado

        // $alumnosInscritos de MisClasesModel debería devolver idmiclase, Email, Nombre del alumno
        $alumnosInscritos = $this->misClasesModel->getAlumnosByClaveClase($claveClase);

        $alumnosParaVista = [];
        foreach($alumnosInscritos as $inscripcion) {
            $datosAlumnoCompleto = $this->userModel->findByEmail($inscripcion['Email']);
            if ($datosAlumnoCompleto) {
                // Combinar datos del usuario con el id de su inscripción específica a esta clase
                $alumnosParaVista[] = array_merge(
                    $datosAlumnoCompleto,
                    ['idmiclase' => $inscripcion['idmiclase']]
                );
            }
        }

        $pageTitle = "Participantes: " . htmlspecialchars($claseActual['nombre']);
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        // Pasar $alumnosParaVista en lugar de $alumnosConFoto si se cambió el nombre
        // require_once __DIR__ . '/../views/clases/participantes.php';
        // Para mantener consistencia con la vista que espera $alumnosConFoto:
        $alumnosConFoto = $alumnosParaVista;
        require_once __DIR__ . '/../views/clases/participantes.php';
    }

    // Acción para que un docente elimine un participante (alumno) de su clase
    public function removeParticipante() {
        $this->checkUserType('Docente', 'dashboard', 'unauthorized_teacher_action');
        $idMiClase = $_GET['idmiclase'] ?? null; // ID de la inscripción en la tabla 'misclases'
        $claveClase = $_GET['clave_clase_redirect'] ?? $_SESSION['current_clave_clase_participantes'] ?? null;

        if (!$idMiClase || !$claveClase) {
            $_SESSION['error_message'] = "Información insuficiente para eliminar al participante.";
            header('Location: index.php?action=dashboard'); // O a la lista de clases del docente
            exit;
        }

        // Verificar que el docente actual es el propietario de la clase de la que intenta eliminar
        $claseInfo = $this->claseModel->findClaseByClave($claveClase);
        if (!$claseInfo || $claseInfo['usuario'] !== $_SESSION['user_email']) {
            $_SESSION['error_message'] = "No tienes permiso para modificar los participantes de esta clase.";
            header('Location: index.php?action=manageClases');
            exit;
        }

        // Adicional: verificar que idMiClase realmente pertenece a la $claveClase (por seguridad)
        // $inscripcion = $this->misClasesModel->getInscripcionById($idMiClase); // Necesitaría este método
        // if (!$inscripcion || $inscripcion['clave'] !== $claveClase) { ... error ... }


        $this->initMisClasesModel();
        if ($this->misClasesModel->adminRemoveAlumnoFromClaseByInscripcionId($idMiClase)) {
            $_SESSION['success_message'] = "Participante eliminado de la clase exitosamente.";
        } else {
            $_SESSION['error_message'] = "Error al eliminar al participante o el participante no fue encontrado.";
        }

        header('Location: index.php?action=viewParticipantes&clave=' . $claveClase);
        exit;
    }
}
?>
