<?php
require_once 'Database.php';

class ReferenciaModel {
    private $conn;
    private $tableName = "referencias";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function createReferencia($claveClase, $usuarioEmail, $titulo, $textoReferencia) {
        // La columna 'fecha' (creación) tiene default CURRENT_TIMESTAMP o se pasa NULL
        $sql = "INSERT INTO " . $this->tableName . " (titulo, referencia, usuario, clave, fecha)
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing createReferencia: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ssss", $titulo, $textoReferencia, $usuarioEmail, $claveClase);
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        error_log("Error executing createReferencia: " . $stmt->error);
        return false;
    }

    public function getReferenciasByClaveClase($claveClase) {
        $sql = "SELECT r.*, u.Nombre as nombre_creador
                FROM " . $this->tableName . " r
                JOIN usuarios u ON r.usuario = u.Email
                WHERE r.clave = ?
                ORDER BY r.fecha DESC";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing getReferenciasByClaveClase: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("s", $claveClase);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        error_log("Error executing getReferenciasByClaveClase: " . $stmt->error);
        return [];
    }

    public function getReferenciaById($idReferencia) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing getReferenciaById: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $idReferencia);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        error_log("Error executing getReferenciaById: " . $stmt->error);
        return false;
    }

    // Para verificar propiedad antes de eliminar/modificar por el docente
    public function getReferenciaByIdAndUsuario($idReferencia, $usuarioEmail) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE id = ? AND usuario = ?";
        $stmt = $this->conn->prepare($sql);
         if ($stmt === false) { return false; }
        $stmt->bind_param("is", $idReferencia, $usuarioEmail);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return false;
    }

    public function updateReferencia($idReferencia, $titulo, $textoReferencia) {
        $sql = "UPDATE " . $this->tableName . "
                SET titulo = ?, referencia = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing updateReferencia: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ssi", $titulo, $textoReferencia, $idReferencia);
        if ($stmt->execute()) {
            return $stmt->affected_rows >= 0; // Exitoso incluso si no hay cambios netos
        }
        error_log("Error executing updateReferencia: " . $stmt->error);
        return false;
    }

    public function deleteReferencia($idReferencia) {
        $sql = "DELETE FROM " . $this->tableName . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing deleteReferencia: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $idReferencia);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        error_log("Error executing deleteReferencia: " . $stmt->error);
        return false;
    }
}
?>
