<?php
require_once 'Database.php';

/**
 * Modelo User para interactuar con la tabla de usuarios y seguridad.
 */
class User {
    private $conn;
    private $tableName = "usuarios";
    private $securityTable = "seguridad"; // Nombre de la tabla de preguntas de seguridad

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Busca un usuario por email y contraseña.
     * @param string $email
     * @param string $password Contraseña sin hashear.
     * @return array|false Los datos del usuario si se encuentra y la contraseña coincide, false en caso contrario.
     */
    public function findByEmailAndPassword($email, $password) {
        $hashedPassword = hash('sha512', $password);
        $sql = "SELECT * FROM " . $this->tableName . " WHERE Email = ? AND Clave = ?";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            // Manejo de error en la preparación de la consulta
            // error_log("Error al preparar la consulta: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("ss", $email, $hashedPassword);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                return $result->fetch_assoc();
            } else {
                return false;
            }
        } else {
            // Manejo de error en la ejecución
            // error_log("Error al ejecutar la consulta: " . $stmt->error);
            return false;
        }
    }

    /**
     * Busca un usuario por su email.
     * @param string $email
     * @return array|false Los datos del usuario si se encuentra, false en caso contrario.
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE Email = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return false;
    }

    /**
     * Busca un usuario por su número de Cédula de Ciudadanía (cc).
     * @param string $cc
     * @return array|false Los datos del usuario si se encuentra, false en caso contrario.
     */
    public function findByCC($cc) {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE cc = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("s", $cc);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return false;
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     * @param string $email
     * @param string $password Contraseña sin hashear.
     * @param string $nombre
     * @param string $cc
     * @param string $foto Nombre del archivo de la foto.
     * @param string $tipo 'Docente' o 'Estudiante'.
     * @param string $grado Grado del estudiante, o 'noaplica'.
     * @return bool|string True si se crea exitosamente, "error_email_exists" o "error_cc_exists" si falla la validación, false si hay error SQL.
     */
    public function createUser($email, $password, $nombre, $cc, $foto, $tipo, $grado) {
        if ($this->findByEmail($email)) {
            return "error_email_exists"; // Usuario ya existe con este email
        }
        if ($this->findByCC($cc)) {
            return "error_cc_exists"; // Usuario ya existe con esta CC
        }

        $hashedPassword = hash('sha512', $password);
        $sql = "INSERT INTO " . $this->tableName . " (Email, Clave, Nombre, cc, Foto, Tipo, grado) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            // error_log("Error al preparar la consulta de inserción: " . $this->conn->error);
            return false;
        }

        // El tipo de dato para cc debe ser 's' si es un string, o 'i' si es un entero. Asumiendo string.
        $stmt->bind_param("sssssss", $email, $hashedPassword, $nombre, $cc, $foto, $tipo, $grado);

        if ($stmt->execute()) {
            return true;
        } else {
            // error_log("Error al ejecutar la consulta de inserción: " . $stmt->error);
            return false;
        }
    }

    // Destructor para cerrar la conexión si es necesario, aunque Database::close() podría llamarse explícitamente.
    // public function __destruct() {
    //     if ($this->conn) {
    //         $this->conn->close();
    //     }
    // }

    /**
     * Verifica si la contraseña proporcionada coincide con la del usuario.
     * @param string $email Email del usuario.
     * @param string $password Contraseña sin hashear a verificar.
     * @return bool True si la contraseña es correcta, false en caso contrario o si el usuario no existe.
     */
    public function checkPassword($email, $password) {
        $user = $this->findByEmail($email);
        if (!$user) {
            return false;
        }
        $hashedPasswordInput = hash('sha512', $password);
        return $hashedPasswordInput === $user['Clave'];
    }

    /**
     * Actualiza el perfil de un usuario.
     * @param string $email Email del usuario a actualizar.
     * @param array $data Array asociativo con los campos a actualizar y sus nuevos valores.
     *                   Campos permitidos: Nombre, Foto, cc, baner.
     * @return bool True si la actualización fue exitosa o no hubo cambios, false en caso de error.
     */
    public function updateUserProfile($email, $data) {
        // $data es un array asociativo ['Nombre' => valor, 'Foto' => valor, ...]
        // Construir la parte SET de la consulta dinámicamente
        $setClauses = [];
        $params = [];
        $types = "";

        if (isset($data['Nombre'])) {
            $setClauses[] = "Nombre = ?";
            $params[] = $data['Nombre'];
            $types .= "s";
        }
        if (isset($data['Foto'])) {
            $setClauses[] = "Foto = ?";
            $params[] = $data['Foto'];
            $types .= "s";
        }
        if (isset($data['cc'])) {
            $setClauses[] = "cc = ?";
            $params[] = $data['cc'];
            $types .= "s"; // Asumiendo cc como string, ajustar si es numérico
        }
        if (isset($data['baner'])) {
            $setClauses[] = "baner = ?";
            $params[] = $data['baner'];
            $types .= "s";
        }
        // No permitir cambiar Email (PK), Tipo o Grado desde aquí por ahora.
        // Clave se cambia por otro proceso (ej. cambiar contraseña).

        if (empty($setClauses)) {
            return false; // No hay nada que actualizar
        }

        $sql = "UPDATE " . $this->tableName . " SET " . implode(", ", $setClauses) . " WHERE Email = ?";
        $params[] = $email;
        $types .= "s";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error al preparar la consulta updateUserProfile: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            return $stmt->affected_rows >= 0; // Exitoso si se ejecuta, incluso si no hay cambios (affected_rows = 0)
        } else {
            error_log("Error al ejecutar la consulta updateUserProfile: " . $stmt->error);
            return false;
        }
    }

    /**
     * Verifica si un usuario ha configurado preguntas de seguridad.
     * @param string $userEmail
     * @return bool True si tiene preguntas, false en caso contrario o si hay error.
     */
    public function hasSecurityQuestions($userEmail) {
        $sql = "SELECT COUNT(*) as count FROM " . $this->securityTable . " WHERE user = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing hasSecurityQuestions: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("s", $userEmail);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        }
        error_log("Error executing hasSecurityQuestions: " . $stmt->error);
        return false;
    }

    /**
     * Guarda las preguntas y respuestas de seguridad para un usuario.
     * Asume que el controlador ha verificado que el usuario no tenía preguntas previas.
     * @param string $userEmail
     * @param string $p1 ID de la pregunta 1.
     * @param string $r1 Respuesta a la pregunta 1.
     * @param string $p2 ID de la pregunta 2.
     * @param string $r2 Respuesta a la pregunta 2.
     * @return bool True si se guardan exitosamente, false en caso de error.
     */
    public function setSecurityQuestions($userEmail, $p1, $r1, $p2, $r2) {
        $sql = "INSERT INTO " . $this->securityTable . " (user, p1, r1, p2, r2) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing setSecurityQuestions: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("sssss", $userEmail, $p1, $r1, $p2, $r2);
        if ($stmt->execute()) {
            return true;
        }
        error_log("Error executing setSecurityQuestions: " . $stmt->error);
        return false;
    }

    /**
     * Obtiene las preguntas y respuestas de seguridad de un usuario.
     * @param string $userEmail
     * @return array|false Los datos de seguridad si se encuentran, false en caso contrario o si hay error.
     */
    public function getSecurityQuestions($userEmail) {
        $sql = "SELECT * FROM " . $this->securityTable . " WHERE user = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing getSecurityQuestions: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("s", $userEmail);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        error_log("Error executing getSecurityQuestions: " . $stmt->error);
        return false;
    }

    /**
     * Actualiza la contraseña de un usuario.
     * @param string $email
     * @param string $newHashedPassword La nueva contraseña ya hasheada.
     * @return bool True si se actualiza exitosamente, false en caso de error.
     */
    public function updatePassword($email, $newHashedPassword) {
        $sql = "UPDATE " . $this->tableName . " SET Clave = ? WHERE Email = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing updatePassword: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ss", $newHashedPassword, $email);
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        error_log("Error executing updatePassword: " . $stmt->error);
        return false;
    }
}
?>
