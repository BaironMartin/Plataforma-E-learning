<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/TemaModel.php';
require_once __DIR__ . '/../models/ComentarioModel.php';
require_once __DIR__ . '/../models/Clase.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/MisClases.php'; // Para verificar acceso a clase del estudiante

class ForoController extends BaseController {
    private $temaModel;
    private $comentarioModel;
    private $claseModel;
    private $userModel;
    private $misClasesModel;

    public function __construct() {
        parent::__construct(); // Autenticación
        $this->temaModel = new TemaModel();
        $this->comentarioModel = new ComentarioModel();
        $this->claseModel = new Clase();
        $this->userModel = new User();
        $this->misClasesModel = new MisClases();
    }

    // Verifica si el usuario tiene acceso a la clase y retorna la info de la clase y el usuario.
    private function checkClaseAccess($claveClase) {
        $clase = $this->claseModel->findClaseByClave($claveClase);
        if (!$clase) {
            $_SESSION['error_message'] = "La clase especificada no existe.";
            header('Location: index.php?action=dashboard');
            exit;
        }

        $userEmail = $_SESSION['user_email'];
        $currentUser = $this->userModel->findByEmail($userEmail);

        if ($currentUser['Tipo'] === 'Estudiante') {
            if (!$this->misClasesModel->isUsuarioInClase($userEmail, $claveClase)) {
                $_SESSION['error_message'] = "No tienes acceso a esta clase.";
                header('Location: index.php?action=listStudentClases');
                exit;
            }
        } elseif ($currentUser['Tipo'] === 'Docente') {
            if ($clase['usuario'] !== $userEmail) {
                 $_SESSION['error_message'] = "No eres el propietario de esta clase.";
                header('Location: index.php?action=manageClases');
                exit;
            }
        }
        return ['clase' => $clase, 'user' => $currentUser];
    }

    private function checkUserType($expectedType, $redirectAction = 'dashboard', $errorMessageKey = 'unauthorized_access') {
        $currentUserEmail = $_SESSION['user_email'] ?? null;
        $user = $this->userModel->findByEmail($currentUserEmail);
        if (!$user || $user['Tipo'] !== $expectedType) {
            $_SESSION['error_message'] = "Acceso no autorizado para esta acción del foro.";
            header("Location: index.php?action={$redirectAction}&error={$errorMessageKey}");
            exit;
        }
        return $user;
    }

    // Lista los temas del foro de una clase
    public function listTemas() {
        if (!isset($_GET['clave'])) {
            $_SESSION['error_message'] = "No se especificó la clase para ver el foro.";
            header('Location: index.php?action=dashboard');
            exit;
        }
        $claveClase = $_GET['clave'];
        $_SESSION['current_clave_clase_foro'] = $claveClase;

        $accessInfo = $this->checkClaseAccess($claveClase);
        $claseActual = $accessInfo['clase'];
        $usuarioActual = $accessInfo['user'];

        $temas = $this->temaModel->getTemasByClaveClase($claveClase);

        $pageTitle = "Foro: " . htmlspecialchars($claseActual['nombre']);
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        require_once __DIR__ . '/../views/foro/lista_temas.php';
    }

    // Guarda un nuevo tema (solo Docente)
    public function storeTema() {
        $this->checkUserType('Docente');
        $claveClase = $_POST['clave_clase'] ?? $_SESSION['current_clave_clase_foro'] ?? null;

        if (!$claveClase) {
            $_SESSION['error_message'] = "No se pudo determinar la clase para el nuevo tema.";
            header('Location: index.php?action=manageClases'); // O dashboard
            exit;
        }
        $this->checkClaseAccess($claveClase); // Verifica propiedad

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $textoTema = $_POST['texto_tema'] ?? '';
            $fechaCierre = $_POST['fecha_cierre'] ?? '';
            $usuarioEmail = $_SESSION['user_email'];

            if (empty($textoTema) || empty($fechaCierre)) {
                $_SESSION['error_message'] = "El texto del tema y la fecha de cierre son requeridos.";
            } else {
                if ($this->temaModel->createTema($claveClase, $usuarioEmail, $textoTema, $fechaCierre)) {
                    $_SESSION['success_message'] = "Nuevo tema creado exitosamente.";
                } else {
                    $_SESSION['error_message'] = "Error al crear el nuevo tema.";
                }
            }
        }
        header('Location: index.php?action=listTemas&clave=' . $claveClase);
        exit;
    }

    // Elimina un tema (solo Docente)
    public function destroyTema() {
        $this->checkUserType('Docente');
        $idTema = $_GET['id_tema'] ?? null;
        $claveClase = $_GET['clave_clase'] ?? $_SESSION['current_clave_clase_foro'] ?? null;

        if (!$idTema || !$claveClase) {
            $_SESSION['error_message'] = "Información insuficiente para eliminar el tema.";
            header('Location: index.php?action=dashboard'); // O a la lista de temas si tenemos clave
            exit;
        }
        $this->checkClaseAccess($claveClase); // Verifica propiedad de la clase
        // Adicional: Verificar que el tema pertenezca a esta clase y/o al docente
        $tema = $this->temaModel->getTemaById($idTema);
        if (!$tema || $tema['clave'] !== $claveClase || $tema['usuario'] !== $_SESSION['user_email']) {
             $_SESSION['error_message'] = "No tienes permiso para eliminar este tema o no existe.";
        } else {
            if ($this->temaModel->deleteTema($idTema)) { // Esto también elimina comentarios asociados
                $_SESSION['success_message'] = "Tema y sus comentarios eliminados exitosamente.";
            } else {
                $_SESSION['error_message'] = "Error al eliminar el tema.";
            }
        }
        header('Location: index.php?action=listTemas&clave=' . $claveClase);
        exit;
    }

    // Muestra un tema específico y sus comentarios
    public function viewTema() {
        if (!isset($_GET['id_tema'])) {
            $_SESSION['error_message'] = "No se especificó el tema.";
            header('Location: index.php?action=dashboard'); // O a la lista de foros de la clase si tenemos clave
            exit;
        }
        $idTema = filter_var($_GET['id_tema'], FILTER_VALIDATE_INT);
        if (!$idTema) { /* ... error ... */ }
        $_SESSION['current_id_tema_foro'] = $idTema;

        $temaActual = $this->temaModel->getTemaById($idTema);
        if (!$temaActual) {
            $_SESSION['error_message'] = "El tema solicitado no existe.";
            $claveClaseFallback = $_SESSION['current_clave_clase_foro'] ?? null;
            header('Location: index.php?action=' . ($claveClaseFallback ? 'listTemas&clave='.$claveClaseFallback : 'dashboard'));
            exit;
        }
        $claveClase = $temaActual['clave'];
        $_SESSION['current_clave_clase_foro'] = $claveClase; // Asegurar que esté seteada para la vuelta

        $accessInfo = $this->checkClaseAccess($claveClase);
        $claseActual = $accessInfo['clase']; // Para el nombre de la clase en la vista
        $usuarioActual = $accessInfo['user'];

        $comentarios = $this->comentarioModel->getComentariosByTemaId($idTema);

        $pageTitle = "Foro: " . htmlspecialchars($temaActual['tema']);
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        $foroAbierto = true; // Por defecto
        if ($usuarioActual['Tipo'] === 'Estudiante') {
            $fechaActual = date("Y-m-d");
            if ($temaActual['cierre'] < $fechaActual) {
                $foroAbierto = false;
            }
        }

        require_once __DIR__ . '/../views/foro/ver_tema.php';
    }

    // Guarda un nuevo comentario
    public function storeComentario() {
        $idTema = $_POST['id_tema'] ?? $_SESSION['current_id_tema_foro'] ?? null;
        $claveClase = $_POST['clave_clase'] ?? $_SESSION['current_clave_clase_foro'] ?? null;

        if (!$idTema || !$claveClase) {
            $_SESSION['error_message'] = "Información insuficiente para agregar el comentario.";
            header('Location: index.php?action=dashboard');
            exit;
        }

        $accessInfo = $this->checkClaseAccess($claveClase); // Verifica acceso a la clase
        $usuarioActual = $accessInfo['user'];
        $temaActual = $this->temaModel->getTemaById($idTema);

        // Verificar si el foro está cerrado para estudiantes
        if ($usuarioActual['Tipo'] === 'Estudiante') {
            $fechaActual = date("Y-m-d");
            if ($temaActual && $temaActual['cierre'] < $fechaActual) {
                $_SESSION['error_message'] = "Este foro ya está cerrado y no admite nuevos comentarios.";
                header('Location: index.php?action=viewTema&id_tema=' . $idTema);
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $textoComentario = $_POST['texto_comentario'] ?? '';
            $usuarioEmail = $_SESSION['user_email'];

            if (empty($textoComentario)) {
                $_SESSION['error_message'] = "El comentario no puede estar vacío.";
            } else {
                if ($this->comentarioModel->createComentario($idTema, $claveClase, $usuarioEmail, $textoComentario)) {
                    $_SESSION['success_message'] = "Comentario agregado exitosamente.";
                } else {
                    $_SESSION['error_message'] = "Error al agregar el comentario.";
                }
            }
        }
        header('Location: index.php?action=viewTema&id_tema=' . $idTema);
        exit;
    }

    // Elimina un comentario (solo Docente o autor si se implementa)
    public function destroyComentario() {
        // Por ahora, solo el docente de la clase puede eliminar cualquier comentario.
        // Se podría extender para que un estudiante elimine los suyos si no hay respuestas, etc.
        $this->checkUserType('Docente');

        $idComentario = $_GET['id_comentario'] ?? null;
        $idTema = $_GET['id_tema_redirect'] ?? $_SESSION['current_id_tema_foro'] ?? null; // Para redirigir

        if (!$idComentario || !$idTema) {
            $_SESSION['error_message'] = "Información insuficiente para eliminar el comentario.";
            header('Location: index.php?action=dashboard'); // O a la lista de temas si tenemos clave
            exit;
        }

        // Verificar que el docente es propietario de la clase a la que pertenece el comentario
        $comentario = $this->comentarioModel->getComentarioById($idComentario);
        if (!$comentario) {
            $_SESSION['error_message'] = "Comentario no encontrado.";
        } else {
            $this->checkClaseAccess($comentario['clave']); // Verifica que el docente actual sea dueño de la clase del comentario
            if ($this->comentarioModel->deleteComentario($idComentario)) {
                $_SESSION['success_message'] = "Comentario eliminado exitosamente.";
            } else {
                $_SESSION['error_message'] = "Error al eliminar el comentario.";
            }
        }
        header('Location: index.php?action=viewTema&id_tema=' . $idTema);
        exit;
    }
}
?>
