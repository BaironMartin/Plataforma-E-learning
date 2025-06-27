<?php
require_once 'Database.php';

class ExamenModel {
    private $conn;
    private $tableName = "examen"; // Asumiendo que la tabla se llama 'examen'

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Actualiza la fecha de cierre de un examen basado en la bandera.
     * La bandera se comparte con la actividad del plan asociada.
     */
    public function updateFechaCierreExamenByBandera($bandera, $fechaCierre) {
        // Verificar si existe un examen con esa bandera primero podría ser útil,
        // pero una actualización simple no fallará si no hay filas coincidentes.
        $sql = "UPDATE " . $this->tableName . " SET cierre = ? WHERE bandera = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing updateFechaCierreExamenByBandera: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ss", $fechaCierre, $bandera);
        if ($stmt->execute()) {
            // affected_rows puede ser 0 si la fecha ya era la misma o no se encontró la bandera.
            // Para esta lógica, no necesariamente indica un error si affected_rows es 0.
            return true;
        } else {
            error_log("Error executing updateFechaCierreExamenByBandera: " . $stmt->error);
            return false;
        }
    }

    /**
     * Elimina un examen (o exámenes) basado en la bandera.
     * Esto se usa cuando se elimina la actividad del plan asociada.
     */
    public function deleteExamenByBandera($bandera) {
        $sql = "DELETE FROM " . $this->tableName . " WHERE bandera = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing deleteExamenByBandera: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("s", $bandera);
        if ($stmt->execute()) {
            // Devuelve true incluso si no se eliminan filas (puede que no hubiera examen con esa bandera).
            return true;
        } else {
            error_log("Error executing deleteExamenByBandera: " . $stmt->error);
            return false;
        }
    }

    // Podrían añadirse otros métodos para crear exámenes, obtener preguntas, registrar respuestas, etc.
    // Por ahora, solo los necesarios para la refactorización de plan.php
}
?>
