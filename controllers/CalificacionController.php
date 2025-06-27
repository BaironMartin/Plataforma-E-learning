<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/TareaModel.php';
require_once __DIR__ . '/../models/Clase.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/MisClases.php';

class CalificacionController extends BaseController {
    private $tareaModel;
    private $claseModel;
    private $userModel;
    private $misClasesModel;

    public function __construct() {
        parent::__construct(); // Autenticación
        $this->tareaModel = new TareaModel();
        $this->claseModel = new Clase();
        $this->userModel = new User();
        $this->misClasesModel = new MisClases();
    }

    // Similar a TareaController, podría moverse a un BaseController o Trait si se repite mucho.
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

    public function viewCalificaciones() {
        if (!isset($_GET['clave'])) {
            $_SESSION['error_message'] = "No se especificó la clave de la clase.";
            header('Location: index.php?action=dashboard');
            exit;
        }
        $claveClase = $_GET['clave'];
        $_SESSION['current_clave_clase_calificaciones'] = $claveClase; // Para uso en reportes, etc.

        $accessInfo = $this->checkClaseAccess($claveClase);
        $claseActual = $accessInfo['clase'];
        $usuarioActual = $accessInfo['user'];

        $pageTitle = "Calificaciones: " . htmlspecialchars($claseActual['nombre']);
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        if ($usuarioActual['Tipo'] === 'Docente') {
            $alumnosConPromedios = [];
            $alumnosInscritos = $this->misClasesModel->getAlumnosByClaveClase($claveClase);
            foreach ($alumnosInscritos as $alumno) {
                $promedio = $this->tareaModel->getPromedioEstudianteClase($alumno['Email'], $claveClase);
                $alumnosConPromedios[] = [
                    'Email' => $alumno['Email'],
                    'Nombre' => $alumno['Nombre'],
                    // Asumiendo que User Model puede obtener la foto si es necesario.
                    // $this->userModel->findByEmail($alumno['Email'])['Foto']
                    'Foto' => $this->userModel->findByEmail($alumno['Email'])['Foto'] ?? 'default.png',
                    'promedio' => $promedio !== null ? number_format($promedio, 2) : 'N/A'
                ];
            }
            require_once __DIR__ . '/../views/calificaciones/docente_resumen.php';

        } elseif ($usuarioActual['Tipo'] === 'Estudiante') {
            $calificacionesEstudiante = $this->tareaModel->getCalificacionesEstudiante($usuarioActual['Email'], $claveClase);
            $promedioGeneralEstudiante = 0;
            $countCalificadas = 0;
            $sumaCalificadas = 0;

            foreach($calificacionesEstudiante as $calif) {
                if ($calif['calificado'] && $calif['evaluacion'] !== '') {
                    $sumaCalificadas += floatval($calif['evaluacion']);
                    $countCalificadas++;
                }
            }
            if ($countCalificadas > 0) {
                $promedioGeneralEstudiante = round($sumaCalificadas / $countCalificadas, 2);
            } else {
                $promedioGeneralEstudiante = 'N/A'; // O 0, o '-'
            }

            require_once __DIR__ . '/../views/calificaciones/estudiante_detalle.php';
        }
    }
}
?>
