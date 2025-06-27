<?php
require_once 'Database.php';

class TareaModel {
    private $conn;
    private $tableName = "tareas";
    private $misClasesTable = "misclases"; // Para obtener alumnos
    private $usuariosTable = "usuarios"; // Para obtener emails de alumnos

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Crea entradas en la tabla 'tareas' para todos los alumnos inscritos en una clase
     * cuando se crea una nueva actividad en el plan.
     */
    public function createTareasForNuevaActividad($idPlan, $claveClase, $periodoActividad) {
        // 1. Obtener todos los usuarios (alumnos) inscritos en la clase
        $sqlAlumnos = "SELECT usuario FROM " . $this->misClasesTable . " WHERE clave = ?";
        $stmtAlumnos = $this->conn->prepare($sqlAlumnos);
        if ($stmtAlumnos === false) {
            error_log("Error preparing getAlumnos for createTareas: " . $this->conn->error);
            return false;
        }
        $stmtAlumnos->bind_param("s", $claveClase);

        if (!$stmtAlumnos->execute()) {
            error_log("Error executing getAlumnos for createTareas: " . $stmtAlumnos->error);
            return false;
        }

        $resultAlumnos = $stmtAlumnos->get_result();
        $alumnosEmails = [];
        while ($row = $resultAlumnos->fetch_assoc()) {
            $alumnosEmails[] = $row['usuario'];
        }
        $stmtAlumnos->close();

        if (empty($alumnosEmails)) {
            return true; // No hay alumnos inscritos, así que no hay tareas que crear. Éxito.
        }

        // 2. Preparar la inserción de tareas
        // Columnas de 'tareas': idtarea, archivo, usuario, clave, fecha, evaluacion, periodo, calificado, idplan
        // Asumimos: archivo vacío al crear, fecha NULL (o NOW()), evaluacion vacía o 0, calificado 0 (o false)
        $sqlInsertTarea = "INSERT INTO " . $this->tableName .
                          " (archivo, usuario, clave, fecha, evaluacion, periodo, calificado, idplan)
                          VALUES ('', ?, ?, NOW(), '', ?, 0, ?)";
        $stmtInsert = $this->conn->prepare($sqlInsertTarea);
        if ($stmtInsert === false) {
            error_log("Error preparing insertTarea for createTareas: " . $this->conn->error);
            return false;
        }

        $success = true;
        foreach ($alumnosEmails as $email) {
            $stmtInsert->bind_param("sssi", $email, $claveClase, $periodoActividad, $idPlan);
            if (!$stmtInsert->execute()) {
                error_log("Error inserting tarea for user $email, plan $idPlan: " . $stmtInsert->error);
                $success = false; // Continuar intentando para otros, pero marcar fallo general
            }
        }
        $stmtInsert->close();
        return $success;
    }

    /**
     * Elimina todas las tareas asociadas a un idplan.
     * Esto se usa cuando se elimina una actividad del plan.
     */
    public function deleteTareasByPlanId($idPlan) {
        $sql = "DELETE FROM " . $this->tableName . " WHERE idplan = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing deleteTareasByPlanId: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $idPlan);
        if ($stmt->execute()) {
            return true; // Devuelve true incluso si no se eliminan filas (puede que no hubiera tareas)
        } else {
            error_log("Error executing deleteTareasByPlanId: " . $stmt->error);
            return false;
        }
    }

    // --- Métodos para la entrega de tareas por parte de estudiantes ---

    public function getTareaForEstudiante($idPlan, $usuarioEmail) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE idplan = ? AND usuario = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing getTareaForEstudiante: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("is", $idPlan, $usuarioEmail);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        error_log("Error executing getTareaForEstudiante: " . $stmt->error);
        return false;
    }

    /**
     * Actualiza la tarea de un estudiante (ej. al entregar un archivo).
     * idtarea es la PK de la tabla 'tareas'.
     * @param string $textoObservacion Texto de observación del estudiante.
     */
    public function entregarTarea($idTarea, $nombreArchivo, $usuarioEmail, $textoObservacion) {
        // Asegurarse que la tarea pertenece al usuario que la entrega
        $sql = "UPDATE " . $this->tableName .
               " SET archivo = ?, texto = ?, fecha = NOW()
               WHERE idtarea = ? AND usuario = ?"; // Añadido campo 'texto'
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing entregarTarea: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ssis", $nombreArchivo, $textoObservacion, $idTarea, $usuarioEmail);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        error_log("Error executing entregarTarea: " . $stmt->error);
        return false;
    }

    public function getTareaByIdAndUsuario($idTarea, $usuarioEmail) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE idtarea = ? AND usuario = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing getTareaByIdAndUsuario: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("is", $idTarea, $usuarioEmail);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        error_log("Error executing getTareaByIdAndUsuario: " . $stmt->error);
        return false;
    }

    public function resetEntregaEstudiante($idTarea, $usuarioEmail) {
        // Pone el archivo y el texto en vacío, como si no se hubiera entregado.
        // No resetea la calificación si ya existe. El controlador debe verificar eso.
        $sql = "UPDATE " . $this->tableName .
               " SET archivo = '', texto = '', fecha = NULL
               WHERE idtarea = ? AND usuario = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing resetEntregaEstudiante: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("is", $idTarea, $usuarioEmail);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        error_log("Error executing resetEntregaEstudiante: " . $stmt->error);
        return false;
    }

    public function createSingleTareaPlaceholder($idPlan, $usuarioEmail, $claveClase, $periodoActividad) {
        // Similar a createTareasForNuevaActividad pero para un solo usuario.
        // Usado si el estudiante se inscribe tarde o no tiene una entrada.
        $sqlInsertTarea = "INSERT INTO " . $this->tableName .
                          " (archivo, usuario, clave, fecha, evaluacion, periodo, calificado, idplan, texto)
                          VALUES ('', ?, ?, NULL, '', ?, 0, ?, '')"; // Fecha NULL, texto vacío
        $stmtInsert = $this->conn->prepare($sqlInsertTarea);
        if ($stmtInsert === false) {
            error_log("Error preparing createSingleTareaPlaceholder: " . $this->conn->error);
            return false;
        }
        $stmtInsert->bind_param("ssssi", $usuarioEmail, $claveClase, $periodoActividad, $idPlan);
        if ($stmtInsert->execute()) {
            return $this->conn->insert_id; // Devuelve el ID de la tarea creada
        } else {
            error_log("Error executing createSingleTareaPlaceholder: " . $stmtInsert->error);
            return false;
        }
    }


    // --- Métodos para calificaciones (podrían estar en CalificacionModel) ---

    public function getTareasEntregadasByClave($claveClase, $idPlan = null) {
        // Obtiene tareas entregadas (archivo no vacío) para una clase, opcionalmente filtrado por idPlan
        $sql = "SELECT t.*, u.Nombre as nombre_estudiante, p.titulo as titulo_plan
                FROM " . $this->tableName . " t
                JOIN " . $this->usuariosTable . " u ON t.usuario = u.Email
                JOIN plan p ON t.idplan = p.idplan
                WHERE t.clave = ? AND t.archivo <> ''";
        if ($idPlan !== null) {
            $sql .= " AND t.idplan = ?";
        }
        $sql .= " ORDER BY u.Nombre, p.fechaentrega";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing getTareasEntregadasByClave: " . $this->conn->error);
            return [];
        }
        if ($idPlan !== null) {
            $stmt->bind_param("si", $claveClase, $idPlan);
        } else {
            $stmt->bind_param("s", $claveClase);
        }

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        error_log("Error executing getTareasEntregadasByClave: " . $stmt->error);
        return [];
    }

    public function calificarTarea($idTarea, $evaluacion, $calificado = 1) {
        $sql = "UPDATE " . $this->tableName . " SET evaluacion = ?, calificado = ? WHERE idtarea = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing calificarTarea: " . $this->conn->error);
            return false;
        }
        // Asumiendo que evaluacion puede ser string (ej. "10.0") y calificado es int (0 o 1)
        $stmt->bind_param("sii", $evaluacion, $calificado, $idTarea);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        error_log("Error executing calificarTarea: " . $stmt->error);
        return false;
    }

    public function getCalificacionesEstudiante($usuarioEmail, $claveClase) {
        $sql = "SELECT t.*, p.titulo as titulo_plan, p.texto as descripcion_plan, p.periodo as periodo_plan
                FROM " . $this->tableName . " t
                JOIN plan p ON t.idplan = p.idplan
                WHERE t.usuario = ? AND t.clave = ?
                ORDER BY p.periodo, p.fechaentrega";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing getCalificacionesEstudiante: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("ss", $usuarioEmail, $claveClase);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        error_log("Error executing getCalificacionesEstudiante: " . $stmt->error);
        return [];
    }

    public function getPromedioEstudianteClase($usuarioEmail, $claveClase) {
        $sql = "SELECT AVG(CAST(evaluacion AS DECIMAL(10,2))) as promedio
                FROM " . $this->tableName . "
                WHERE usuario = ? AND clave = ? AND calificado = 1 AND evaluacion <> ''";
        $stmt = $this->conn->prepare($sql);
         if ($stmt === false) {
            error_log("Error preparing getPromedioEstudianteClase: " . $this->conn->error);
            return null;
        }
        $stmt->bind_param("ss", $usuarioEmail, $claveClase);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['promedio'] !== null ? round($row['promedio'], 2) : null;
        }
        error_log("Error executing getPromedioEstudianteClase: " . $stmt->error);
        return null;
    }

    public function getPromedioEstudianteClasePorPeriodo($usuarioEmail, $claveClase, $periodo) {
        $sql = "SELECT AVG(CAST(evaluacion AS DECIMAL(10,2))) as promedio
                FROM " . $this->tableName . "
                WHERE usuario = ? AND clave = ? AND periodo = ? AND calificado = 1 AND evaluacion <> '' AND evaluacion IS NOT NULL";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing getPromedioEstudianteClasePorPeriodo: " . $this->conn->error);
            return null;
        }
        $stmt->bind_param("sss", $usuarioEmail, $claveClase, $periodo);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['promedio'] !== null ? round($row['promedio'], 2) : null;
        }
        error_log("Error executing getPromedioEstudianteClasePorPeriodo: " . $stmt->error);
        return null;
    }
}
?>
