<?php
require_once 'Database.php';

class PlanModel {
    private $conn;
    private $tableName = "plan";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    private function generaBandera() {
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $longitudCadena = strlen($cadena);
        $pass = "";
        $longitudPass = 20; // Longitud de la bandera
        for ($i = 1; $i <= $longitudPass; $i++) {
            $pos = rand(0, $longitudCadena - 1);
            $pass .= substr($cadena, $pos, 1);
        }
        return $pass;
    }

    public function createActividad($usuarioEmail, $claveClase, $titulo, $texto, $periodo, $fechaEntrega) {
        $bandera = $this->generaBandera();
        // Asumiendo que la columna 'fecha' (de creación) tiene un default CURRENT_TIMESTAMP o se pasa NULL
        $sql = "INSERT INTO " . $this->tableName . " (usuario, clave, titulo, texto, periodo, fecha, fechaentrega, bandera)
                VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing createActividad: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("sssssss", $usuarioEmail, $claveClase, $titulo, $texto, $periodo, $fechaEntrega, $bandera);

        if ($stmt->execute()) {
            // Devolver el ID de la actividad creada y la bandera para crear tareas y exámenes
            return ['id' => $this->conn->insert_id, 'bandera' => $bandera];
        } else {
            error_log("Error executing createActividad: " . $stmt->error);
            return false;
        }
    }

    public function getActividadesByClaveClase($claveClase) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE clave = ? ORDER BY fechaentrega DESC";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing getActividadesByClaveClase: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("s", $claveClase);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        error_log("Error executing getActividadesByClaveClase: " . $stmt->error);
        return [];
    }

    public function getActividadById($idPlan) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE idplan = ?";
        $stmt = $this->conn->prepare($sql);
          if ($stmt === false) {
            error_log("Error preparing getActividadById: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $idPlan);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        error_log("Error executing getActividadById: " . $stmt->error);
        return false;
    }

    public function getActividadByIdAndUsuario($idPlan, $usuarioEmail) {
        // Para verificar que el docente que modifica/elimina es el creador de la actividad
        $sql = "SELECT * FROM " . $this->tableName . " WHERE idplan = ? AND usuario = ?";
        $stmt = $this->conn->prepare($sql);
          if ($stmt === false) {
            error_log("Error preparing getActividadByIdAndUsuario: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("is", $idPlan, $usuarioEmail);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        error_log("Error executing getActividadByIdAndUsuario: " . $stmt->error);
        return false;
    }


    public function updateActividad($idPlan, $titulo, $texto, $periodo, $fechaEntrega) {
        $sql = "UPDATE " . $this->tableName . "
                SET titulo = ?, texto = ?, periodo = ?, fechaentrega = ?
                WHERE idplan = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing updateActividad: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ssssi", $titulo, $texto, $periodo, $fechaEntrega, $idPlan);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0; // Retorna true si alguna fila fue afectada
        }
        error_log("Error executing updateActividad: " . $stmt->error);
        return false;
    }

    public function deleteActividad($idPlan) {
        // La eliminación de tareas y exámenes asociados se manejará en el controlador
        // o mediante FK con ON DELETE CASCADE si la BD lo soporta y está configurado.
        // Este método solo elimina la entrada del plan.
        $actividad = $this->getActividadById($idPlan); // Necesitamos la bandera para el controlador
        if (!$actividad) return false;

        $sql = "DELETE FROM " . $this->tableName . " WHERE idplan = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing deleteActividad: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $idPlan);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return $actividad['bandera']; // Devolver la bandera para que el controlador elimine dependencias
            }
            return false; // No se eliminó nada (quizás el ID no existía)
        }
        error_log("Error executing deleteActividad: " . $stmt->error);
        return false;
    }
}
?>
