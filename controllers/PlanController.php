<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PlanModel.php';
require_once __DIR__ . '/../models/Clase.php'; // Para obtener info de la clase y verificar acceso
require_once __DIR__ . '/../models/User.php'; // Para obtener tipo de usuario
require_once __DIR__ . '/../models/TareaModel.php'; // Para crear/eliminar tareas asociadas
require_once __DIR__ . '/../models/ExamenModel.php'; // Para actualizar/eliminar exámenes asociados
require_once __DIR__ . '/../models/MisClases.php'; // Para verificar si estudiante está en clase

class PlanController extends BaseController {
    private $planModel;
    private $claseModel;
    private $userModel;
    private $tareaModel;
    private $examenModel;
    private $misClasesModel;

    public function __construct() {
        parent::__construct(); // Autenticación
        $this->planModel = new PlanModel();
        $this->claseModel = new Clase();
        $this->userModel = new User();
        $this->tareaModel = new TareaModel();
        $this->examenModel = new ExamenModel();
        $this->misClasesModel = new MisClases();
    }

    private function checkClaseAccess($claveClase) {
        $clase = $this->claseModel->findClaseByClave($claveClase);
        if (!$clase) {
            $_SESSION['error_message'] = "La clase especificada no existe.";
            header('Location: index.php?action=dashboard'); // O a una página de error general
            exit;
        }

        $userEmail = $_SESSION['user_email'];
        $currentUser = $this->userModel->findByEmail($userEmail);

        // Si es estudiante, verificar que esté inscrito en la clase
        if ($currentUser['Tipo'] === 'Estudiante') {
            if (!$this->misClasesModel->isUsuarioInClase($userEmail, $claveClase)) {
                $_SESSION['error_message'] = "No tienes acceso a esta clase.";
                header('Location: index.php?action=listStudentClases');
                exit;
            }
        } elseif ($currentUser['Tipo'] === 'Docente') {
            // Si es docente, verificar que sea el propietario de la clase
            if ($clase['usuario'] !== $userEmail) {
                 $_SESSION['error_message'] = "No eres el propietario de esta clase.";
                header('Location: index.php?action=manageClases');
                exit;
            }
        }
        return ['clase' => $clase, 'user' => $currentUser];
    }

    // Muestra el plan de la clase y el formulario de actividades (si es docente)
    public function viewPlan() {
        if (!isset($_GET['clave'])) {
            $_SESSION['error_message'] = "No se especificó la clave de la clase.";
            header('Location: index.php?action=dashboard');
            exit;
        }
        $claveClase = $_GET['clave'];
        $_SESSION['current_clave_clase'] = $claveClase; // Guardar para uso en formularios

        $accessInfo = $this->checkClaseAccess($claveClase);
        $claseActual = $accessInfo['clase'];
        $usuarioActual = $accessInfo['user'];

        $actividades = $this->planModel->getActividadesByClaveClase($claveClase);

        $pageTitle = "Plan de Estudio: " . htmlspecialchars($claseActual['nombre']);
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        $actividadParaModificar = null;
        if ($usuarioActual['Tipo'] === 'Docente' && isset($_GET['ma_idplan'])) {
            $idPlanModificar = filter_var($_GET['ma_idplan'], FILTER_VALIDATE_INT);
            if ($idPlanModificar) {
                // Verificar que la actividad pertenezca al docente y a la clase actual
                $actividadParaModificar = $this->planModel->getActividadByIdAndUsuario($idPlanModificar, $usuarioActual['Email']);
                if (!$actividadParaModificar || $actividadParaModificar['clave'] !== $claveClase) {
                    $actividadParaModificar = null; // No permitir modificar si no cumple
                    $_SESSION['error_message'] = "No se puede modificar la actividad solicitada.";
                }
            }
        }

        // Cargar el menú lateral (esto se podría manejar en el layout principal)
        // $menu_content = $this->loadViewToString(__DIR__ . '/../views/layouts/menu_lateral.php', ['userType' => $usuarioActual['Tipo']]);

        require_once __DIR__ . '/../views/plan/view.php';
    }

    // Guarda una nueva actividad (solo Docente)
    public function storeActividad() {
        $this->checkUserType('Docente');
        $claveClase = $_SESSION['current_clave_clase'] ?? $_POST['clave_clase'] ?? null;
         if (!$claveClase) {
            $_SESSION['error_message'] = "No se pudo determinar la clase para añadir la actividad.";
            header('Location: index.php?action=manageClases');
            exit;
        }
        $accessInfo = $this->checkClaseAccess($claveClase); // Verifica propiedad

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'] ?? '';
            $texto = $_POST['texto'] ?? ''; // CKEditor usualmente postea aquí
            $periodo = $_POST['periodo'] ?? '';
            $fechaEntrega = $_POST['fecha_entrega'] ?? '';
            $usuarioEmail = $_SESSION['user_email'];

            if (empty($titulo) || empty($texto) || empty($periodo) || empty($fechaEntrega)) {
                $_SESSION['error_message'] = "Todos los campos son requeridos para la actividad.";
            } else {
                $creationResult = $this->planModel->createActividad($usuarioEmail, $claveClase, $titulo, $texto, $periodo, $fechaEntrega);
                if ($creationResult && isset($creationResult['id']) && isset($creationResult['bandera'])) {
                    $idPlanNuevo = $creationResult['id'];
                    // Crear tareas para los alumnos inscritos
                    $this->tareaModel->createTareasForNuevaActividad($idPlanNuevo, $claveClase, $periodo);
                    // Si se crea un examen automáticamente, aquí se haría con $creationResult['bandera']
                    $_SESSION['success_message'] = "Actividad creada y tareas asignadas.";
                } else {
                    $_SESSION['error_message'] = "Error al crear la actividad en el plan.";
                }
            }
        }
        header('Location: index.php?action=viewPlan&clave=' . $claveClase);
        exit;
    }

    // Actualiza una actividad existente (solo Docente)
    public function updateActividad() {
        $this->checkUserType('Docente');
        $claveClase = $_SESSION['current_clave_clase'] ?? $_POST['clave_clase'] ?? null;
        $idPlan = $_POST['id_plan_modificar'] ?? null;

        if (!$claveClase || !$idPlan) {
            $_SESSION['error_message'] = "Información insuficiente para modificar la actividad.";
            header('Location: index.php?action=manageClases'); // O dashboard
            exit;
        }
        $accessInfo = $this->checkClaseAccess($claveClase); // Verifica propiedad de la clase
        $actividad = $this->planModel->getActividadByIdAndUsuario($idPlan, $_SESSION['user_email']); // Verifica propiedad de la actividad

        if (!$actividad || $actividad['clave'] !== $claveClase) {
            $_SESSION['error_message'] = "No tienes permiso para modificar esta actividad o no pertenece a esta clase.";
            header('Location: index.php?action=viewPlan&clave=' . $claveClase);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'] ?? '';
            $texto = $_POST['texto'] ?? '';
            $periodo = $_POST['periodo'] ?? '';
            $fechaEntrega = $_POST['fecha_entrega'] ?? '';

            if (empty($titulo) || empty($texto) || empty($periodo) || empty($fechaEntrega)) {
                $_SESSION['error_message'] = "Todos los campos son requeridos para modificar la actividad.";
            } else {
                if ($this->planModel->updateActividad($idPlan, $titulo, $texto, $periodo, $fechaEntrega)) {
                    // Actualizar fecha de cierre del examen si existe y está asociado por la bandera
                    if (!empty($actividad['bandera'])) {
                        $this->examenModel->updateFechaCierreExamenByBandera($actividad['bandera'], $fechaEntrega);
                    }
                    $_SESSION['success_message'] = "Actividad actualizada exitosamente.";
                } else {
                    $_SESSION['error_message'] = "Error al actualizar la actividad o no hubo cambios.";
                }
            }
        }
        header('Location: index.php?action=viewPlan&clave=' . $claveClase);
        exit;
    }

    // Elimina una actividad (solo Docente)
    public function destroyActividad() {
        $this->checkUserType('Docente');
        $claveClase = $_GET['clave_clase'] ?? null; // Necesitamos la clave para redirigir
        $idPlan = $_GET['id_plan'] ?? null;

        if (!$claveClase || !$idPlan) {
            $_SESSION['error_message'] = "Información insuficiente para eliminar la actividad.";
            // Intentar obtener claveClase de sesión si no viene por GET para la redirección
            $claveClaseFallback = $_SESSION['current_clave_clase'] ?? null;
            header('Location: ' . ($claveClaseFallback ? 'index.php?action=viewPlan&clave='.$claveClaseFallback : 'index.php?action=manageClases'));
            exit;
        }

        $accessInfo = $this->checkClaseAccess($claveClase); // Verifica propiedad de la clase
        $actividad = $this->planModel->getActividadByIdAndUsuario($idPlan, $_SESSION['user_email']); // Verifica propiedad de la actividad

        if (!$actividad || $actividad['clave'] !== $claveClase) {
            $_SESSION['error_message'] = "No tienes permiso para eliminar esta actividad o no pertenece a esta clase.";
        } else {
            $bandera = $this->planModel->deleteActividad($idPlan); // Modelo devuelve bandera al eliminar
            if ($bandera) {
                $this->tareaModel->deleteTareasByPlanId($idPlan);
                if (!empty($bandera)) { // Si la actividad tenía una bandera
                    $this->examenModel->deleteExamenByBandera($bandera);
                }
                $_SESSION['success_message'] = "Actividad y sus tareas/exámenes asociados eliminados.";
            } else {
                $_SESSION['error_message'] = "Error al eliminar la actividad del plan.";
            }
        }
        header('Location: index.php?action=viewPlan&clave=' . $claveClase);
        exit;
    }

    // Método auxiliar para verificar tipo de usuario (similar al de ClaseController)
    private function checkUserType($expectedType, $redirectAction = 'dashboard', $errorMessageKey = 'unauthorized_access') {
        $currentUserEmail = $_SESSION['user_email'] ?? null;
        $user = $this->userModel->findByEmail($currentUserEmail);
        if (!$user || $user['Tipo'] !== $expectedType) {
            $_SESSION['error_message'] = "Acceso no autorizado para esta acción.";
            header("Location: index.php?action={$redirectAction}&error={$errorMessageKey}");
            exit;
        }
        return $user;
    }
}
?>
