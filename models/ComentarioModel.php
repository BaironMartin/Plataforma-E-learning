<?php
require_once 'Database.php';

class ComentarioModel {
    private $conn;
    private $tableName = "comentario";
    private $usuariosTable = "usuarios"; // Para JOIN

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function createComentario($idTema, $claveClase, $usuarioEmail, $textoComentario) {
        // La columna 'fecha' (creación) tiene default CURRENT_TIMESTAMP o se pasa NULL
        $sql = "INSERT INTO " . $this->tableName . " (idtema, clave, usuario, comentario, fecha)
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing createComentario: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("isss", $idTema, $claveClase, $usuarioEmail, $textoComentario);
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        error_log("Error executing createComentario: " . $stmt->error);
        return false;
    }

    public function getComentariosByTemaId($idTema) {
        $sql = "SELECT c.*, u.Nombre as nombre_usuario, u.Foto as foto_usuario, u.Tipo as tipo_usuario
                FROM " . $this->tableName . " c
                JOIN " . $this->usuariosTable . " u ON c.usuario = u.Email
                WHERE c.idtema = ?
                ORDER BY c.fecha ASC"; // Mostrar comentarios más antiguos primero
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing getComentariosByTemaId: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("i", $idTema);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        error_log("Error executing getComentariosByTemaId: " . $stmt->error);
        return [];
    }

    public function getComentarioById($idComentario) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE idcomentario = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) { return false; }
        $stmt->bind_param("i", $idComentario);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return false;
    }


    public function deleteComentario($idComentario) {
        // En el controlador se debe verificar si el usuario tiene permiso para borrar
        // (ej. es el autor del comentario o es el docente de la clase)
        $sql = "DELETE FROM " . $this->tableName . " WHERE idcomentario = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing deleteComentario: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $idComentario);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        error_log("Error executing deleteComentario: " . $stmt->error);
        return false;
    }
}
?>
