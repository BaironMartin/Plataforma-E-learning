<?php
require_once 'Database.php';

class MisClases {
    private $conn;
    private $tableName = "misclases";
    private $claseTable = "clase"; // Para JOINs

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function isUsuarioInClase($usuarioEmail, $claveClase) {
        $sql = "SELECT COUNT(*) as count FROM " . $this->tableName . " WHERE usuario = ? AND clave = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing isUsuarioInClase: " . $this->conn->error);
            return false; // O podría retornar true para prevenir múltiples uniones en caso de error
        }
        $stmt->bind_param("ss", $usuarioEmail, $claveClase);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        }
        error_log("Error executing isUsuarioInClase: " . $stmt->error);
        return false; // O true
    }

    public function addUsuarioToClase($usuarioEmail, $claveClase) {
        // Asumimos que la validación de existencia de la clase y si ya está unido se hizo en el controlador
        $sql = "INSERT INTO " . $this->tableName . " (usuario, clave) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing addUsuarioToClase: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ss", $usuarioEmail, $claveClase);
        if ($stmt->execute()) {
            return true;
        }
        error_log("Error executing addUsuarioToClase: " . $stmt->error);
        return false;
    }

    public function removeUsuarioFromClaseById($idMiClase, $usuarioEmail) {
        // Primero, verificar que el registro 'idmiclase' realmente pertenece al usuario para seguridad.
        $checkSql = "SELECT usuario FROM " . $this->tableName . " WHERE idmiclase = ?";
        $checkStmt = $this->conn->prepare($checkSql);
        if ($checkStmt === false) {
            error_log("Error preparing check for removeUsuarioFromClaseById: " . $this->conn->error);
            return false;
        }
        $checkStmt->bind_param("i", $idMiClase);
        if (!$checkStmt->execute()) {
            error_log("Error executing check for removeUsuarioFromClaseById: " . $checkStmt->error);
            return false;
        }
        $result = $checkStmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row || $row['usuario'] !== $usuarioEmail) {
            error_log("Attempt to delete enrollment not belonging to user. IDMiClase: $idMiClase, User: $usuarioEmail");
            return false; // No pertenece al usuario o no existe
        }
        $checkStmt->close();


        // Si la verificación es exitosa, proceder a eliminar
        $sql = "DELETE FROM " . $this->tableName . " WHERE idmiclase = ? AND usuario = ?"; // Doble check con usuario
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing removeUsuarioFromClaseById: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("is", $idMiClase, $usuarioEmail);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        error_log("Error executing removeUsuarioFromClaseById: " . $stmt->error);
        return false;
    }

    public function getClasesByUsuarioEmail($usuarioEmail) {
        $sql = "SELECT c.nombre, c.imagen, c.clave, mc.idmiclase
                FROM " . $this->claseTable . " c
                JOIN " . $this->tableName . " mc ON c.clave = mc.clave
                WHERE mc.usuario = ?
                ORDER BY c.nombre ASC";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing getClasesByUsuarioEmail: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("s", $usuarioEmail);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            error_log("Error executing getClasesByUsuarioEmail: " . $stmt->error);
            return [];
        }

    public function getAlumnosByClaveClase($claveClase) {
        // Modificado para incluir idmiclase
        $sql = "SELECT u.Email, u.Nombre, mc.idmiclase
                FROM " . $this->usuariosTable . " u
                JOIN " . $this->tableName . " mc ON u.Email = mc.usuario
                WHERE mc.clave = ? AND u.Tipo = 'Estudiante'";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing getAlumnosByClaveClase: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("s", $claveClase);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            error_log("Error executing getAlumnosByClaveClase: " . $stmt->error);
            return [];
        }

    // Método para que un docente elimine la inscripción de un alumno de su clase.
    // La verificación de que el docente es propietario de la clase se hace en el controlador.
    public function adminRemoveAlumnoFromClaseByInscripcionId($idMiClase) {
        $sql = "DELETE FROM " . $this->tableName . " WHERE idmiclase = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing adminRemoveAlumnoFromClaseByInscripcionId: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $idMiClase);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        error_log("Error executing adminRemoveAlumnoFromClaseByInscripcionId: " . $stmt->error);
        return false;
    }
    }
    }
}
?>
