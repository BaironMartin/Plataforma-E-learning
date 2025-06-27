<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/ReferenciaModel.php';
require_once __DIR__ . '/../models/Clase.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/MisClases.php';

class BibliotecaController extends BaseController {
    private $referenciaModel;
    private $claseModel;
    private $userModel;
    private $misClasesModel;

    public function __construct() {
        parent::__construct(); // Autenticación
        $this->referenciaModel = new ReferenciaModel();
        $this->claseModel = new Clase();
        $this->userModel = new User();
        $this->misClasesModel = new MisClases();
    }

    // Verifica si el usuario tiene acceso a la clase.
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
                $_SESSION['error_message'] = "No tienes acceso a la biblioteca de esta clase.";
                header('Location: index.php?action=listStudentClases');
                exit;
            }
        } elseif ($currentUser['Tipo'] === 'Docente') {
            // Para ver la biblioteca, no necesariamente tiene que ser el dueño.
            // Pero para CUD, sí. Lo controlaremos en las acciones específicas.
            // Por ahora, si es docente, se asume que puede ver la biblioteca de cualquier clase
            // a la que acceda (o se podría restringir a sus clases o a las que está inscrito).
            // Para simplificar, si es docente, permitimos ver. La edición/borrado requerirá ser el dueño.
        }
        return ['clase' => $clase, 'user' => $currentUser];
    }

    private function checkOwnership($referenciaId, $userEmail) {
        $referencia = $this->referenciaModel->getReferenciaByIdAndUsuario($referenciaId, $userEmail);
        if (!$referencia) {
            $_SESSION['error_message'] = "No tienes permiso para modificar o eliminar esta referencia, o no existe.";
            return false;
        }
        return $referencia; // Devuelve la referencia si es propietario
    }

    private function checkUserType($expectedType, $redirectAction = 'dashboard', $errorMessageKey = 'unauthorized_access') {
        $currentUserEmail = $_SESSION['user_email'] ?? null;
        $user = $this->userModel->findByEmail($currentUserEmail);
        if (!$user || $user['Tipo'] !== $expectedType) {
            $_SESSION['error_message'] = "Acceso no autorizado para esta acción de biblioteca.";
            header("Location: index.php?action={$redirectAction}&error={$errorMessageKey}");
            exit;
        }
        return $user;
    }

    // Muestra la biblioteca de una clase
    public function viewBiblioteca() {
        if (!isset($_GET['clave'])) {
            $_SESSION['error_message'] = "No se especificó la clase para ver la biblioteca.";
            header('Location: index.php?action=dashboard');
            exit;
        }
        $claveClase = $_GET['clave'];
        $_SESSION['current_clave_clase_biblioteca'] = $claveClase;

        $accessInfo = $this->checkClaseAccess($claveClase);
        $claseActual = $accessInfo['clase'];
        $usuarioActual = $accessInfo['user'];

        $referencias = $this->referenciaModel->getReferenciasByClaveClase($claveClase);

        $pageTitle = "Biblioteca: " . htmlspecialchars($claseActual['nombre']);
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        $referenciaParaModificar = null;
        if ($usuarioActual['Tipo'] === 'Docente' && isset($_GET['ma_idreferencia'])) {
            $idReferenciaModificar = filter_var($_GET['ma_idreferencia'], FILTER_VALIDATE_INT);
            if ($idReferenciaModificar) {
                // Verificar que la referencia pertenezca al docente y a la clase actual
                $referenciaParaModificar = $this->referenciaModel->getReferenciaByIdAndUsuario($idReferenciaModificar, $usuarioActual['Email']);
                if (!$referenciaParaModificar || $referenciaParaModificar['clave'] !== $claveClase) {
                    $referenciaParaModificar = null;
                    $_SESSION['error_message'] = "No se puede modificar la referencia solicitada.";
                }
            }
        }
        require_once __DIR__ . '/../views/biblioteca/index.php';
    }

    // Guarda una nueva referencia (solo Docente creador de la clase)
    public function storeReferencia() {
        $usuarioActual = $this->checkUserType('Docente');
        $claveClase = $_POST['clave_clase'] ?? $_SESSION['current_clave_clase_biblioteca'] ?? null;

        if (!$claveClase) { /* ... error y redirect ... */ }

        $clase = $this->claseModel->findClaseByClave($claveClase);
        if (!$clase || $clase['usuario'] !== $usuarioActual['Email']) { // Solo el docente dueño de la clase puede añadir
            $_SESSION['error_message'] = "No tienes permiso para añadir referencias a esta clase.";
            header('Location: index.php?action=viewBiblioteca&clave=' . $claveClase);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo_referencia'] ?? '';
            $textoReferencia = $_POST['texto_referencia'] ?? ''; // CKEditor

            if (empty($titulo) || empty($textoReferencia)) {
                $_SESSION['error_message'] = "El título y el contenido de la referencia son requeridos.";
            } else {
                if ($this->referenciaModel->createReferencia($claveClase, $usuarioActual['Email'], $titulo, $textoReferencia)) {
                    $_SESSION['success_message'] = "Referencia agregada exitosamente.";
                } else {
                    $_SESSION['error_message'] = "Error al agregar la referencia.";
                }
            }
        }
        header('Location: index.php?action=viewBiblioteca&clave=' . $claveClase);
        exit;
    }

    // Actualiza una referencia (solo Docente creador de la referencia)
    public function updateReferencia() {
        $usuarioActual = $this->checkUserType('Docente');
        $idReferencia = $_POST['id_referencia_modificar'] ?? null;
        $claveClase = $_POST['clave_clase_redirect'] ?? $_SESSION['current_clave_clase_biblioteca'] ?? null;

        if (!$idReferencia || !$claveClase) { /* ... error y redirect ... */ }

        $referencia = $this->checkOwnership($idReferencia, $usuarioActual['Email']);
        if (!$referencia || $referencia['clave'] !== $claveClase) {
             $_SESSION['error_message'] = "No tienes permiso para modificar esta referencia o no pertenece a esta clase.";
             header('Location: index.php?action=viewBiblioteca&clave=' . $claveClase);
             exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo_referencia'] ?? '';
            $textoReferencia = $_POST['texto_referencia'] ?? '';

            if (empty($titulo) || empty($textoReferencia)) {
                $_SESSION['error_message'] = "El título y el contenido son requeridos.";
            } else {
                if ($this->referenciaModel->updateReferencia($idReferencia, $titulo, $textoReferencia)) {
                    $_SESSION['success_message'] = "Referencia actualizada.";
                } else {
                    $_SESSION['error_message'] = "Error al actualizar o no hubo cambios.";
                }
            }
        }
        header('Location: index.php?action=viewBiblioteca&clave=' . $claveClase);
        exit;
    }

    // Elimina una referencia (solo Docente creador de la referencia)
    public function destroyReferencia() {
        $usuarioActual = $this->checkUserType('Docente');
        $idReferencia = $_GET['id_referencia'] ?? null;
        $claveClase = $_GET['clave_clase_redirect'] ?? $_SESSION['current_clave_clase_biblioteca'] ?? null;

        if (!$idReferencia || !$claveClase) { /* ... error y redirect ... */ }

        $referencia = $this->checkOwnership($idReferencia, $usuarioActual['Email']);
         if (!$referencia || $referencia['clave'] !== $claveClase) {
             $_SESSION['error_message'] = "No tienes permiso para eliminar esta referencia o no pertenece a esta clase.";
        } else {
            if ($this->referenciaModel->deleteReferencia($idReferencia)) {
                $_SESSION['success_message'] = "Referencia eliminada.";
            } else {
                $_SESSION['error_message'] = "Error al eliminar la referencia.";
            }
        }
        header('Location: index.php?action=viewBiblioteca&clave=' . $claveClase);
        exit;
    }
}
?>
