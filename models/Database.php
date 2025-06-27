<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $dbName = "plataforma";
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbName);
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
            // echo "Conexión exitosa"; // Para depuración
        } catch (Exception $e) {
            // echo "Error de conexión: " . $e->getMessage(); // Para depuración
            // En una aplicación real, esto se registraría o manejaría de forma más robusta
            die("Error de conexión a la base de datos. Por favor, inténtelo más tarde.");
        }
        return $this->conn;
    }

    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
