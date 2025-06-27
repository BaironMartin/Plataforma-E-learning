<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../utils/BasePDFReport.php'; // Clase base para FPDF
// Incluir los modelos necesarios
require_once __DIR__ . '/../models/Clase.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/MisClases.php';
require_once __DIR__ . '/../models/TareaModel.php';

class ReporteController extends BaseController {
    private $claseModel;
    private $userModel;
    private $misClasesModel;
    private $tareaModel;

    public function __construct() {
        parent::__construct(); // Autenticación
        $this->claseModel = new Clase();
        $this->userModel = new User();
        $this->misClasesModel = new MisClases();
        $this->tareaModel = new TareaModel();
    }

    // Verifica acceso a la clase (similar a otros controladores)
    private function checkClaseAccess($claveClase, $checkOwnershipOrEnrollment = true, $specificUserEmail = null) {
        $clase = $this->claseModel->findClaseByClave($claveClase);
        if (!$clase) {
            $_SESSION['error_message'] = "La clase especificada no existe para el reporte.";
            // Redirigir a una página segura, tal vez el dashboard
            header('Location: index.php?action=dashboard&error=clase_not_found');
            exit;
        }

        $userEmailForCheck = $specificUserEmail ?? $_SESSION['user_email'];
        $currentUser = $this->userModel->findByEmail($userEmailForCheck);
         if (!$currentUser) {
            $_SESSION['error_message'] = "Usuario no encontrado para generar el reporte.";
            header('Location: index.php?action=dashboard&error=user_not_found');
            exit;
        }


        if ($checkOwnershipOrEnrollment) {
            if ($currentUser['Tipo'] === 'Estudiante') {
                if (!$this->misClasesModel->isUsuarioInClase($userEmailForCheck, $claveClase)) {
                    $_SESSION['error_message'] = "No tienes acceso a esta clase para generar el reporte.";
                    header('Location: index.php?action=listStudentClases&error=clase_access_denied');
                    exit;
                }
            } elseif ($currentUser['Tipo'] === 'Docente') {
                // Para reportes de clase o individuales de alumnos, el docente debe ser el propietario de la clase.
                if ($clase['usuario'] !== $userEmailForCheck) {
                    $_SESSION['error_message'] = "No eres el propietario de esta clase para generar el reporte.";
                    header('Location: index.php?action=manageClases&error=clase_owner_mismatch');
                    exit;
                }
            }
        }
        return ['clase' => $clase, 'user' => $currentUser];
    }

    // Reporte General de Calificaciones de una Clase (para Docentes)
    public function generarReporteClase() {
        $this->checkUserType('Docente'); // Solo docentes
        $claveClase = $_POST['clave_clase'] ?? $_GET['clave'] ?? null; // Clave puede venir por POST o GET
        if (!$claveClase) {
            $_SESSION['error_message'] = "No se especificó la clase para el reporte.";
            header('Location: index.php?action=manageClases');
            exit;
        }

        $accessInfo = $this->checkClaseAccess($claveClase);
        $claseActual = $accessInfo['clase'];

        $alumnosInscritos = $this->misClasesModel->getAlumnosByClaveClase($claveClase);
        $datosReporte = [];
        foreach ($alumnosInscritos as $alumno) {
            $promedioTotal = $this->tareaModel->getPromedioEstudianteClase($alumno['Email'], $claveClase);
            $fila = [
                'nombre' => $alumno['Nombre'],
                // Calcular promedios por periodo
                'periodo1' => $this->tareaModel->getPromedioEstudianteClasePorPeriodo($alumno['Email'], $claveClase, 'I'),
                'periodo2' => $this->tareaModel->getPromedioEstudianteClasePorPeriodo($alumno['Email'], $claveClase, 'II'),
                'periodo3' => $this->tareaModel->getPromedioEstudianteClasePorPeriodo($alumno['Email'], $claveClase, 'III'),
                'periodo4' => $this->tareaModel->getPromedioEstudianteClasePorPeriodo($alumno['Email'], $claveClase, 'IV'),
                'promedio_total' => $promedioTotal !== null ? number_format($promedioTotal, 1) : 'N/A'
            ];
            // Formatear periodos N/A o 0.0
            foreach (['periodo1', 'periodo2', 'periodo3', 'periodo4'] as $p) {
                $fila[$p] = $fila[$p] !== null ? number_format($fila[$p], 1) : '0.0';
            }
            $datosReporte[] = $fila;
        }

        $pdf = new BasePDFReport();
        $pdf->AliasNbPages();
        $pdf->setReportTitle('REPORTE DE CALIFICACIONES GRUPALES');
        $pdf->setClaseNombre($claseActual['nombre']);
        $pdf->AddPage();

        $header = ['NOMBRES', 'PER. I', 'PER. II', 'PER. III', 'PER. IV', 'PROM. FINAL'];
        $tableData = [];
        foreach($datosReporte as $dr) {
            $tableData[] = [$dr['nombre'], $dr['periodo1'], $dr['periodo2'], $dr['periodo3'], $dr['periodo4'], $dr['promedio_total']];
        }
        $pdf->BasicTable($header, $tableData); // Necesita ajustar anchos en BasePDFReport

        $pdf->Output('D', 'reporte_clase_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $claseActual['nombre']) . '.pdf');
        exit;
    }

    // Reporte Individual de Calificaciones de un Alumno (para Docentes)
    public function generarReporteIndividualAlumno() {
        $this->checkUserType('Docente');
        $claveClase = $_GET['clave'] ?? null;
        $emailEstudiante = $_GET['userEmail'] ?? null;

        if (!$claveClase || !$emailEstudiante) {
            $_SESSION['error_message'] = "Falta información para generar el reporte individual.";
            header('Location: index.php?action=manageClases'); // O a la vista de calificaciones del docente
            exit;
        }

        // El docente debe ser propietario de la clase. checkClaseAccess lo valida.
        $accessInfo = $this->checkClaseAccess($claveClase);
        $claseActual = $accessInfo['clase'];

        // Obtener datos del estudiante específico
        $estudiante = $this->userModel->findByEmail($emailEstudiante);
        if(!$estudiante || $estudiante['Tipo'] !== 'Estudiante') {
            $_SESSION['error_message'] = "El usuario especificado no es un estudiante válido.";
            header('Location: index.php?action=viewCalificaciones&clave='.$claveClase);
            exit;
        }
        // Adicionalmente, verificar que este estudiante esté en esta clase.
        if (!$this->misClasesModel->isUsuarioInClase($emailEstudiante, $claveClase)) {
             $_SESSION['error_message'] = "El estudiante no pertenece a esta clase.";
            header('Location: index.php?action=viewCalificaciones&clave='.$claveClase);
            exit;
        }


        $calificaciones = $this->tareaModel->getCalificacionesEstudiante($emailEstudiante, $claveClase);

        $pdf = new BasePDFReport();
        $pdf->AliasNbPages();
        $pdf->setReportTitle('REPORTE INDIVIDUAL DE CALIFICACIONES');
        $pdf->setClaseNombre($claseActual['nombre'] . " - Est.: " . $estudiante['Nombre']);
        $pdf->AddPage();

        $header = ['TAREA', 'PERIODO', 'CALIFICACION'];
        $tableData = [];
        if (!empty($calificaciones)) {
            foreach($calificaciones as $calif) {
                $tableData[] = [
                    $calif['titulo_plan'],
                    $calif['periodo_plan'] ?? $calif['periodo'],
                    ($calif['calificado'] && $calif['evaluacion'] !== '') ? $calif['evaluacion'] : 'Pendiente'
                ];
            }
        } else {
            $tableData[] = ["Sin tareas o calificaciones registradas.", "", ""];
        }
        $pdf->BasicTable($header, $tableData);

        // Añadir promedio si se desea
        $promedio = $this->tareaModel->getPromedioEstudianteClase($emailEstudiante, $claveClase);
        if ($promedio !== null) {
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 10, 'Promedio General en la Clase: ' . number_format($promedio, 2), 0, 1);
        }


        $pdf->Output('D', 'reporte_individual_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $estudiante['Nombre']) . '.pdf');
        exit;
    }

    // Reporte de Mis Calificaciones (para Estudiantes)
    public function generarReporteMisCalificaciones() {
        $this->checkUserType('Estudiante');
        $claveClase = $_POST['clave_clase'] ?? $_GET['clave'] ?? null; // Clave puede venir por POST o GET
        $emailEstudiante = $_SESSION['user_email']; // El estudiante solo puede ver su propio reporte

        if (!$claveClase) {
            $_SESSION['error_message'] = "No se especificó la clase para el reporte.";
            header('Location: index.php?action=listStudentClases'); // A su lista de clases
            exit;
        }

        // checkClaseAccess verifica que el estudiante esté inscrito
        $accessInfo = $this->checkClaseAccess($claveClase, true, $emailEstudiante);
        $claseActual = $accessInfo['clase'];
        $estudianteActual = $accessInfo['user'];

        $calificaciones = $this->tareaModel->getCalificacionesEstudiante($emailEstudiante, $claveClase);

        $pdf = new BasePDFReport();
        $pdf->AliasNbPages();
        $pdf->setReportTitle('MI REPORTE DE CALIFICACIONES');
        $pdf->setClaseNombre($claseActual['nombre'] . " - Est.: " . $estudianteActual['Nombre']);
        $pdf->AddPage();

        $header = ['TAREA', 'PERIODO', 'MI CALIFICACION'];
        $tableData = [];
         if (!empty($calificaciones)) {
            foreach($calificaciones as $calif) {
                $tableData[] = [
                    $calif['titulo_plan'],
                    $calif['periodo_plan'] ?? $calif['periodo'],
                    ($calif['calificado'] && $calif['evaluacion'] !== '') ? $calif['evaluacion'] : 'Pendiente'
                ];
            }
        } else {
            $tableData[] = ["Sin tareas o calificaciones registradas.", "", ""];
        }
        $pdf->BasicTable($header, $tableData);

        $promedio = $this->tareaModel->getPromedioEstudianteClase($emailEstudiante, $claveClase);
        if ($promedio !== null) {
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 10, 'Mi Promedio General en la Clase: ' . number_format($promedio, 2), 0, 1);
        }

        $pdf->Output('D', 'mis_calificaciones_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $claseActual['nombre']) . '.pdf');
        exit;
    }

    // Método auxiliar para verificar tipo de usuario
    private function checkUserType($expectedType) {
        $currentUserEmail = $_SESSION['user_email'] ?? null;
        if (!$currentUserEmail) {
            header('Location: index.php?action=login&error=session_expired_report');
            exit;
        }
        $user = $this->userModel->findByEmail($currentUserEmail);
        if (!$user || $user['Tipo'] !== $expectedType) {
            $_SESSION['error_message'] = "Acceso no autorizado para generar este tipo de reporte.";
            header("Location: index.php?action=dashboard&error=report_auth_failed");
            exit;
        }
        return $user;
    }
}

// Añadir esto a TareaModel.php si no existe:
// public function getPromedioEstudianteClasePorPeriodo($usuarioEmail, $claveClase, $periodo) {
//     $sql = "SELECT AVG(CAST(evaluacion AS DECIMAL(10,2))) as promedio
//             FROM " . $this->tableName . "
//             WHERE usuario = ? AND clave = ? AND periodo = ? AND calificado = 1 AND evaluacion <> ''";
//     $stmt = $this->conn->prepare($sql);
//     if ($stmt === false) { return null; }
//     $stmt->bind_param("sss", $usuarioEmail, $claveClase, $periodo);
//     if ($stmt->execute()) {
//         $result = $stmt->get_result();
//         $row = $result->fetch_assoc();
//         return $row['promedio'] !== null ? round($row['promedio'], 2) : null;
//     }
//     return null;
// }
?>
