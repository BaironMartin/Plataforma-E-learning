<?php
require_once 'Database.php';

class TemaModel {
    private $conn;
    private $tableName = "temas";
    private $comentarioTable = "comentario"; // Para contar o eliminar comentarios

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function createTema($claveClase, $usuarioEmail, $textoTema, $fechaCierre) {
        // La columna 'fecha' (creación) tiene default CURRENT_TIMESTAMP o se pasa NULL
        $sql = "INSERT INTO " . $this->tableName . " (clave, usuario, tema, cierre, fecha)
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing createTema: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ssss", $claveClase, $usuarioEmail, $textoTema, $fechaCierre);
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        error_log("Error executing createTema: " . $stmt->error);
        return false;
    }

    public function getTemasByClaveClase($claveClase) {
        $sql = "SELECT t.*, u.Nombre as nombre_creador
                FROM " . $this->tableName . " t
                JOIN usuarios u ON t.usuario = u.Email
                WHERE t.clave = ?
                ORDER BY t.fecha DESC"; // O ASC según preferencia
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing getTemasByClaveClase: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("s", $claveClase);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $temas = $result->fetch_all(MYSQLI_ASSOC);
            // Añadir conteo de comentarios a cada tema
            foreach ($temas as $key => $tema) {
                $temas[$key]['num_comentarios'] = $this->countComentariosByTemaId($tema['idtema']);
            }
            return $temas;
        }
        error_log("Error executing getTemasByClaveClase: " . $stmt->error);
        return [];
    }

    public function getTemaById($idTema) {
        $sql = "SELECT t.*, u.Nombre as nombre_creador
                FROM " . $this->tableName . " t
                JOIN usuarios u ON t.usuario = u.Email
                WHERE t.idtema = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing getTemaById: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $idTema);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        error_log("Error executing getTemaById: " . $stmt->error);
        return false;
    }

    // Para verificar propiedad antes de eliminar, si es necesario en el controlador
    public function getTemaByIdAndUsuario($idTema, $usuarioEmail) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE idtema = ? AND usuario = ?";
        $stmt = $this->conn->prepare($sql);
         if ($stmt === false) { return false; }
        $stmt->bind_param("is", $idTema, $usuarioEmail);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return false;
    }


    public function deleteTema($idTema) {
        // Primero, eliminar comentarios asociados para evitar problemas de FK si no hay ON DELETE CASCADE
        $this->deleteComentariosByTemaId($idTema);

        $sql = "DELETE FROM " . $this->tableName . " WHERE idtema = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing deleteTema: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $idTema);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        error_log("Error executing deleteTema: " . $stmt->error);
        return false;
    }

    public function countComentariosByTemaId($idTema) {
        $sql = "SELECT COUNT(*) as count FROM " . $this->comentarioTable . " WHERE idtema = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) { return 0; }
        $stmt->bind_param("i", $idTema);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'];
        }
        return 0;
    }

    private function deleteComentariosByTemaId($idTema) {
        $sql = "DELETE FROM " . $this->comentarioTable . " WHERE idtema = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) { return false; } // No crítico si falla, pero se loguearía
        $stmt->bind_param("i", $idTema);
        $stmt->execute(); // Ejecutar y olvidar (o verificar affected_rows si es necesario)
        return true;
    }
}
?>
