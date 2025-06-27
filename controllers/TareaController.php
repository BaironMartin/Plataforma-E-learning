<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/TareaModel.php';
require_once __DIR__ . '/../models/PlanModel.php';
require_once __DIR__ . '/../models/Clase.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/MisClases.php'; // Para verificar acceso a clase del estudiante

class TareaController extends BaseController {
    private $tareaModel;
    private $planModel;
    private $claseModel;
    private $userModel;
    private $misClasesModel;

    public function __construct() {
        parent::__construct(); // Autenticación
        $this->tareaModel = new TareaModel();
        $this->planModel = new PlanModel();
        $this->claseModel = new Clase();
        $this->userModel = new User();
        $this->misClasesModel = new MisClases(); // Necesario para verificar que el estudiante pertenece a la clase
    }

    // Verifica si el usuario tiene acceso a la clase y retorna la info de la clase y el usuario.
    private function checkClaseAccess($claveClase, $checkOwnershipOrEnrollment = true) {
        $clase = $this->claseModel->findClaseByClave($claveClase);
        if (!$clase) {
            $_SESSION['error_message'] = "La clase especificada no existe.";
            header('Location: index.php?action=dashboard');
            exit;
        }

        $userEmail = $_SESSION['user_email'];
        $currentUser = $this->userModel->findByEmail($userEmail);

        if ($checkOwnershipOrEnrollment) {
            if ($currentUser['Tipo'] === 'Estudiante') {
                if (!$this->misClasesModel->isUsuarioInClase($userEmail, $claveClase)) {
                    $_SESSION['error_message'] = "No tienes acceso a esta clase.";
                    header('Location: index.php?action=listStudentClases'); // Dashboard del estudiante
                    exit;
                }
            } elseif ($currentUser['Tipo'] === 'Docente') {
                if ($clase['usuario'] !== $userEmail) {
                    $_SESSION['error_message'] = "No eres el propietario de esta clase.";
                    header('Location: index.php?action=manageClases'); // Dashboard del docente
                    exit;
                }
            }
        }
        return ['clase' => $clase, 'user' => $currentUser];
    }

    // Verifica que el usuario sea del tipo esperado.
    private function checkUserType($expectedType, $redirectAction = 'dashboard', $errorMessageKey = 'unauthorized_access') {
        $currentUserEmail = $_SESSION['user_email'] ?? null;
        $user = $this->userModel->findByEmail($currentUserEmail); // Asume que userModel está inicializado
        if (!$user || $user['Tipo'] !== $expectedType) {
            $_SESSION['error_message'] = "Acceso no autorizado para esta acción.";
            header("Location: index.php?action={$redirectAction}&error={$errorMessageKey}");
            exit;
        }
        return $user;
    }

    // Lista las actividades del plan para un estudiante (reemplaza tareas.php)
    public function listTareasEstudiante() {
        $this->checkUserType('Estudiante');
        if (!isset($_GET['clave'])) {
            $_SESSION['error_message'] = "No se especificó la clase.";
            header('Location: index.php?action=listStudentClases');
            exit;
        }
        $claveClase = $_GET['clave'];
        $_SESSION['current_clave_clase_tareas'] = $claveClase;


        $accessInfo = $this->checkClaseAccess($claveClase);
        $claseActual = $accessInfo['clase'];
        $usuarioActual = $accessInfo['user'];

        // Obtener todas las actividades del plan para esta clase
        $actividadesPlan = $this->planModel->getActividadesByClaveClase($claveClase);

        $pageTitle = "Tareas de: " . htmlspecialchars($claseActual['nombre']);
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        require_once __DIR__ . '/../views/tareas/student_list_actividades.php';
    }

    // Muestra el formulario de entrega o el detalle de una tarea para un estudiante
    // O la lista de entregas para un docente
    public function viewTarea() {
        if (!isset($_GET['id_plan'])) {
            $_SESSION['error_message'] = "No se especificó la tarea/actividad.";
            $claveClaseFallback = $_SESSION['current_clave_clase_tareas'] ?? // Si venimos de la lista de tareas
                                  $_SESSION['current_clave_clase'] ?? // Si venimos del plan de estudio
                                  null;
            $redirectAction = $claveClaseFallback ? 'viewPlan&clave='.$claveClaseFallback : 'dashboard';
            header('Location: index.php?action='.$redirectAction);
            exit;
        }
        $idPlan = filter_var($_GET['id_plan'], FILTER_VALIDATE_INT);
        if (!$idPlan) {
            $_SESSION['error_message'] = "ID de tarea/actividad inválido.";
            // Similar fallback para redirección
             $claveClaseFallback = $_SESSION['current_clave_clase_tareas'] ?? $_SESSION['current_clave_clase'] ?? null;
             $redirectAction = $claveClaseFallback ? 'viewPlan&clave='.$claveClaseFallback : 'dashboard';
             header('Location: index.php?action='.$redirectAction);
            exit;
        }
        $_SESSION['current_id_plan_entrega'] = $idPlan;


        $actividadPlan = $this->planModel->getActividadById($idPlan);
        if (!$actividadPlan) {
            $_SESSION['error_message'] = "La tarea/actividad no existe.";
            header('Location: index.php?action=dashboard'); // O a la lista de clases/tareas
            exit;
        }
        $claveClase = $actividadPlan['clave'];
        // Guardar clave por si se necesita para volver o en formularios
        $_SESSION['current_clave_clase_tareas'] = $claveClase;

        $accessInfo = $this->checkClaseAccess($claveClase);
        $claseActual = $accessInfo['clase'];
        $usuarioActual = $accessInfo['user'];

        $pageTitle = "Detalle Tarea: " . htmlspecialchars($actividadPlan['titulo']);
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        if ($usuarioActual['Tipo'] === 'Estudiante') {
            // Verificar si el estudiante tiene una entrada en 'tareas' para este id_plan
            // Si no, podría necesitar crearla (lógica de 'tareaone' del original)
            $tareaEntregada = $this->tareaModel->getTareaForEstudiante($idPlan, $usuarioActual['Email']);

            if (!$tareaEntregada) {
                // El estudiante no tiene una entrada para esta tarea, ¿crearla?
                // Esto es lo que hacía el botón "ingresar a tarea"
                // Podríamos hacerlo automáticamente o mostrar un botón para ello.
                // Por ahora, mostraremos un mensaje o el botón.
                $necesitaCrearEntradaTarea = true;
            }

            require_once __DIR__ . '/../views/tareas/student_entrega_view.php';

        } elseif ($usuarioActual['Tipo'] === 'Docente') {
            // El docente ve la lista de todas las entregas para esta actividad del plan
            $entregas = $this->tareaModel->getTareasEntregadasByClave($claveClase, $idPlan);
            require_once __DIR__ . '/../views/tareas/docente_calificar_lista.php';
        }
    }

    // Procesa la creación de la entrada de tarea para un estudiante que se unió tarde
    public function ensureStudentTareaEntry() {
        $this->checkUserType('Estudiante');
        $idPlan = $_SESSION['current_id_plan_entrega'] ?? $_POST['id_plan'] ?? null;
        $claveClase = $_SESSION['current_clave_clase_tareas'] ?? null;

        if (!$idPlan || !$claveClase) {
            $_SESSION['error_message'] = "Falta información para registrar la tarea.";
            header('Location: index.php?action=dashboard'); // O a la lista de clases del estudiante
            exit;
        }

        $accessInfo = $this->checkClaseAccess($claveClase); // Verifica que el estudiante esté en la clase
        $usuarioActual = $accessInfo['user'];
        $actividadPlan = $this->planModel->getActividadById($idPlan);

        if (!$actividadPlan) {
             $_SESSION['error_message'] = "La actividad del plan no existe.";
        } else {
            // Crear la entrada en la tabla 'tareas' para este estudiante y este id_plan
            // El método createTareasForNuevaActividad es para muchos alumnos. Necesitamos uno para un solo alumno
            // o adaptar TareaModel para tener un método más específico, o que TareaModel.getTareaForEstudiante
            // cree la entrada si no existe.
            // Por ahora, vamos a asumir que TareaModel necesita un método:
            // $this->tareaModel->createSingleTareaForEstudiante($idPlan, $usuarioActual['Email'], $claveClase, $actividadPlan['periodo']);
            // Esta funcionalidad está implícita en el flujo original de `entregatarea.php` con la variable `tareaone`.
            // Lo más simple sería que el modelo TareaModel->getTareaForEstudiante cree la fila si no existe.
            // O aquí llamamos a un nuevo método en TareaModel.

            // Simulando la creación de la tarea si no existe (esto debería estar en el modelo)
             $existe = $this->tareaModel->getTareaForEstudiante($idPlan, $usuarioActual['Email']);
             if(!$existe){
                 //Columnas: archivo, usuario, clave, fecha, evaluacion, periodo, calificado, idplan
                 $this->tareaModel->createSingleTareaPlaceholder($idPlan, $usuarioActual['Email'], $claveClase, $actividadPlan['periodo']);
                 $_SESSION['success_message'] = "Ahora puedes entregar la tarea.";
             } else {
                 $_SESSION['info_message'] = "Ya tienes una entrada para esta tarea.";
             }
        }
        header('Location: index.php?action=viewTarea&id_plan=' . $idPlan);
        exit;
    }


    // Procesa la entrega de una tarea por un estudiante
    public function submitEntrega() {
        $this->checkUserType('Estudiante');
        $idPlan = $_SESSION['current_id_plan_entrega'] ?? $_POST['id_plan'] ?? null;

        if (!$idPlan) {
            $_SESSION['error_message'] = "ID de Plan no especificado para la entrega.";
            header('Location: index.php?action=dashboard'); // O a su lista de clases
            exit;
        }

        $actividadPlan = $this->planModel->getActividadById($idPlan);
        if (!$actividadPlan) { /* ... manejo de error ... */ }
        $claveClase = $actividadPlan['clave'];
        $accessInfo = $this->checkClaseAccess($claveClase);
        $usuarioActual = $accessInfo['user'];

        // Verificar fecha límite
        $fechaActual = date("Y-m-d");
        if ($actividadPlan['fechaentrega'] < $fechaActual) {
            $_SESSION['error_message'] = "La fecha límite para entregar esta tarea ha pasado.";
            header('Location: index.php?action=viewTarea&id_plan=' . $idPlan);
            exit;
        }

        $tareaEstudiante = $this->tareaModel->getTareaForEstudiante($idPlan, $usuarioActual['Email']);
        if (!$tareaEstudiante) {
            // Esto no debería pasar si ensureStudentTareaEntry se llama o la lógica está integrada
            $_SESSION['error_message'] = "No se encontró tu registro para esta tarea. Intenta ingresar a la tarea primero.";
            header('Location: index.php?action=viewTarea&id_plan=' . $idPlan);
            exit;
        }
        $idTarea = $tareaEstudiante['idtarea']; // ID de la tabla 'tareas'

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_entrega'])) {
            $textoObservacion = $_POST['texto_observacion'] ?? '';
            $nombreArchivoOriginal = basename($_FILES['archivo_entrega']['name']);

            if ($_FILES['archivo_entrega']['error'] === UPLOAD_ERR_OK && !empty($nombreArchivoOriginal)) {
                $targetDir = __DIR__ . "/../archivos/archivosTareas/";
                if (!file_exists($targetDir)) @mkdir($targetDir, 0777, true);

                $file = $_FILES['archivo_entrega'];
                $fileExtension = strtolower(pathinfo($nombreArchivoOriginal, PATHINFO_EXTENSION));
                // Definir extensiones permitidas para tareas (podría ser más amplio que para fotos)
                $allowedTaskExtensions = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar', 'jpg', 'png', 'jpeg', 'ppt', 'pptx', 'xls', 'xlsx'];
                $maxTaskFileSize = 10 * 1024 * 1024; // 10MB para tareas

                if (!in_array($fileExtension, $allowedTaskExtensions)) {
                    $_SESSION['error_message'] = "Tipo de archivo no permitido para la entrega de tarea. Extensiones permitidas: " . implode(', ', $allowedTaskExtensions);
                } elseif ($file['size'] > $maxTaskFileSize) {
                    $_SESSION['error_message'] = "El archivo de la tarea es demasiado grande (máximo 10MB).";
                } else {
                    $sanitizedUserEmail = preg_replace("/[^a-zA-Z0-9_.-]/", "_", $usuarioActual['Email']);
                    $nombreArchivoServidor = "tarea_" . $idTarea . "_user_" . $sanitizedUserEmail ."_". time() . "." . $fileExtension;
                    $targetFile = $targetDir . $nombreArchivoServidor;

                    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                        if ($this->tareaModel->entregarTarea($idTarea, $nombreArchivoServidor, $usuarioActual['Email'], $textoObservacion)) {
                            $_SESSION['success_message'] = "Tarea entregada exitosamente.";
                        } else {
                            $_SESSION['error_message'] = "Error al guardar la información de la entrega en la base de datos.";
                            @unlink($targetFile);
                        }
                    } else {
                        $_SESSION['error_message'] = "Error al subir el archivo de la tarea.";
                    }
                }
            } elseif (!empty($textoObservacion) && $_FILES['archivo_entrega']['error'] === UPLOAD_ERR_NO_FILE) {
                 // Si solo se envía texto y no archivo
                if ($this->tareaModel->entregarTarea($idTarea, $tareaEstudiante['archivo'] ?? '', $usuarioActual['Email'], $textoObservacion)) {
                    $_SESSION['success_message'] = "Observaciones guardadas.";
                } else {
                    $_SESSION['error_message'] = "Error al guardar las observaciones.";
                }
            }
            else {
                 $_SESSION['error_message'] = "Debe seleccionar un archivo o escribir una observación.";
            }
        }
        header('Location: index.php?action=viewTarea&id_plan=' . $idPlan);
        exit;
    }

    // Estudiante elimina su propia entrega (si no está calificada)
    public function deleteStudentEntrega() {
        $this->checkUserType('Estudiante');
        $idTarea = $_GET['id_tarea'] ?? null; // id de la tabla 'tareas'
        $idPlan = $_SESSION['current_id_plan_entrega'] ?? null; // Para redirigir

        if (!$idTarea || !$idPlan) {
             $_SESSION['error_message'] = "Información insuficiente para eliminar la entrega.";
             header('Location: index.php?action=dashboard');
             exit;
        }
        $idTarea = filter_var($idTarea, FILTER_VALIDATE_INT);
        $usuarioEmail = $_SESSION['user_email'];

        // Obtener info de la tarea para verificar que no esté calificada y para obtener nombre de archivo
        $tareaInfo = $this->tareaModel->getTareaByIdAndUsuario($idTarea, $usuarioEmail); // Necesitarás este método

        if ($tareaInfo && ($tareaInfo['evaluacion'] == '' || $tareaInfo['evaluacion'] == 0) && $tareaInfo['calificado'] == 0) {
            $archivoAEliminar = __DIR__ . "/../archivos/archivosTareas/" . $tareaInfo['archivo'];

            if ($this->tareaModel->resetEntregaEstudiante($idTarea, $usuarioEmail)) { // Método que pone archivo='', texto=''
                if (!empty($tareaInfo['archivo']) && file_exists($archivoAEliminar)) {
                    unlink($archivoAEliminar);
                }
                $_SESSION['success_message'] = "Entrega eliminada/reseteada exitosamente.";
            } else {
                $_SESSION['error_message'] = "Error al eliminar/resetear la entrega.";
            }
        } else {
            $_SESSION['error_message'] = "No se puede eliminar la entrega porque ya ha sido calificada o no se encontró.";
        }
        header('Location: index.php?action=viewTarea&id_plan=' . $idPlan);
        exit;
    }


    // Procesa la calificación de una tarea por un docente
    public function processCalificacion() {
        $this->checkUserType('Docente');
        $idTarea = $_POST['id_tarea_calificar'] ?? $_GET['id_tarea_calificar'] ?? null; // id de la tabla 'tareas'
        $calificacion = $_POST['calificacion'] ?? $_GET['calificacion'] ?? null;
        $idPlan = $_SESSION['current_id_plan_entrega'] ?? null; // Para redirigir

        if (!$idTarea || $calificacion === null || !$idPlan) {
            $_SESSION['error_message'] = "Información insuficiente para calificar.";
        } else {
            // Validar que la tarea pertenece a una clase del docente (indirectamente a través del idPlan y clave)
            // $this->checkClaseAccess($claveDeLaTarea); ...
            if ($this->tareaModel->calificarTarea($idTarea, $calificacion)) {
                $_SESSION['success_message'] = "Tarea calificada exitosamente.";
            } else {
                $_SESSION['error_message'] = "Error al guardar la calificación.";
            }
        }

        if ($idPlan) {
            header('Location: index.php?action=viewTarea&id_plan=' . $idPlan);
        } else {
            // Fallback si no tenemos idPlan, quizás al dashboard del docente
            header('Location: index.php?action=dashboard');
        }
        exit;
    }
}
?>
