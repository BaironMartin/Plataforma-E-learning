<?php
require_once 'Database.php';

class Clase {
    private $conn;
    private $tableName = "clase";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    private function generaPass() {
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $longitudCadena = strlen($cadena);
        $pass = "";
        $longitudPass = 10;
        for ($i = 1; $i <= $longitudPass; $i++) {
            $pos = rand(0, $longitudCadena - 1);
            $pass .= substr($cadena, $pos, 1);
        }
        return $pass;
    }

    public function createClase($nombre, $imagen, $grado, $usuarioEmail) {
        $clave = $this->generaPass();
        // La columna 'fecha' en la tabla original permitía NULL y tenía un CURRENT_TIMESTAMP por defecto.
        // Si queremos que PHP inserte la fecha, usaríamos NOW() en SQL o date('Y-m-d H:i:s') en PHP.
        // Asumiendo que la BD maneja la fecha automáticamente si pasamos NULL.
        $sql = "INSERT INTO " . $this->tableName . " (nombre, clave, usuario, fecha, imagen, grado) VALUES (?, ?, ?, NOW(), ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error al preparar la consulta de inserción de clase: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("sssss", $nombre, $clave, $usuarioEmail, $imagen, $grado);

        if ($stmt->execute()) {
            return $this->conn->insert_id; // Devuelve el ID de la clase creada o true
        } else {
            error_log("Error al ejecutar la consulta de inserción de clase: " . $stmt->error);
            return false;
        }
    }

    public function getClasesByUsuario($usuarioEmail) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE usuario = ? ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error al preparar la consulta getClasesByUsuario: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("s", $usuarioEmail);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            error_log("Error al ejecutar la consulta getClasesByUsuario: " . $stmt->error);
            return [];
        }
    }

    public function findClaseByIdAndUsuario($idClase, $usuarioEmail) {
        // Para verificar propiedad antes de borrar
        $sql = "SELECT * FROM " . $this->tableName . " WHERE idclase = ? AND usuario = ?";
        $stmt = $this->conn->prepare($sql);
         if ($stmt === false) {
            error_log("Error al preparar la consulta findClaseByIdAndUsuario: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("is", $idClase, $usuarioEmail);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return false;
    }

    public function findClaseByClave($clave) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE clave = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error al preparar la consulta findClaseByClave: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("s", $clave);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return false;
    }


    public function deleteClase($idClase) {
        // Asumimos que la verificación de propiedad ya se hizo en el controlador
        $sql = "DELETE FROM " . $this->tableName . " WHERE idclase = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error al preparar la consulta deleteClase: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $idClase);

        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        } else {
            error_log("Error al ejecutar la consulta deleteClase: " . $stmt->error);
            return false;
        }
    }

    // Podríamos añadir más métodos según se necesiten para otras funcionalidades (ej. unirse a clase, etc.)
}
?>
