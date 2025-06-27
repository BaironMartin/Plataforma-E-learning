<?php
session_start();
require_once __DIR__ . '/../models/User.php';
// Podríamos tener una clase de utilidad para el manejo de archivos o reCAPTCHA si se vuelve complejo.
// require_once __DIR__ . '/../utils/Recaptcha.php';
// require_once __DIR__ . '/../utils/FileUpload.php';

/**
 * Controlador para la autenticación de usuarios (login, registro, logout).
 */
class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Maneja el proceso de inicio de sesión.
     * Muestra el formulario de login (GET) o procesa los datos de login (POST).
     * Espera $_POST['u'] (email), $_POST['p'] (contraseña), y $_POST['g-recaptcha-response'].
     */
    public function login() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard"); // Redirigir al dashboard MVC
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['u'])) {
            $email = $_POST['u'];
            $password = $_POST['p'];

            // Verificación reCAPTCHA (simplificada, idealmente en una clase/función helper)
            $recaptchaSecret = '6LcRjHskAAAAABA0ioTMxTx7GwBSq8PfKKZBQcTo'; // Esto debería estar en una config
            $recaptchaResponse = $_POST['g-recaptcha-response'];

            if (empty($recaptchaResponse)) {
                $error = "Por favor, complete el reCAPTCHA.";
            } else {
                $verifyURL = "https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}";
                $response = file_get_contents($verifyURL);
                $responseData = json_decode($response, true);

                if ($responseData['success']) {
                    $user = $this->userModel->findByEmailAndPassword($email, $password);
                    if ($user) {
                        $_SESSION['user_id'] = $user['Email']; // Usar ID numérico si está disponible y es preferible
                        $_SESSION['user_email'] = $user['Email'];
                        $_SESSION['user_name'] = $user['Nombre'];
                        $_SESSION['user_type'] = $user['Tipo'];
                        $_SESSION['time'] = time();
                        header("Location: index.php?action=dashboard"); // Redirigir al dashboard MVC
                        exit;
                    } else {
                        $error = "Correo electrónico o contraseña incorrectos.";
                    }
                } else {
                    $error = "Verificación reCAPTCHA fallida. Inténtelo de nuevo.";
                }
            }
        }
        // Cargar la vista de login
        require_once __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Maneja el proceso de registro de nuevos usuarios.
     * Muestra el formulario de registro (GET) o procesa los datos de registro (POST).
     * Espera $_POST['user'] (email), $_POST['pass'], $_POST['nombre'], $_POST['cc'],
     * $_POST['tipo'], $_POST['grado'] y $_FILES['photo'].
     */
    public function register() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard"); // Redirigir al dashboard MVC
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user'])) {
            $email = $_POST['user'];
            $password = $_POST['pass'];
            $nombre = $_POST['nombre'];
            $cc = $_POST['cc'];
            $tipo = $_POST['tipo'];
            $grado = $_POST['grado'];
            $fotoName = "";

            // Manejo de subida de foto
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $targetDir = __DIR__ . "/../archivos/"; // Asegúrate que esta carpeta exista y tenga permisos
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $fotoName = $email . basename($_FILES['photo']['name']); // Renombrar para evitar colisiones
                $targetFile = $targetDir . $fotoName;

                if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                    $error = "Error al subir la foto.";
                    // Considerar no continuar si la foto es obligatoria y falla la subida
                }
            } else {
                // $error = "Error en la subida de la foto o no se proporcionó ninguna.";
                // Si la foto no es obligatoria, $fotoName quedará vacío y se guardará así.
            }

            if (!$error) { // Procede solo si no hubo error en la subida de foto (si es relevante)
                $result = $this->userModel->createUser($email, $password, $nombre, $cc, $fotoName, $tipo, $grado);

                if ($result === true) {
                    $success = "¡Registro exitoso! Ahora puedes iniciar sesión.";
                    // Podrías redirigir al login directamente:
                    // header("Location: index.php?action=login&status=registration_success");
                    // exit;
                } elseif ($result === "error_email_exists") {
                    $error = "El correo electrónico ya está registrado.";
                } elseif ($result === "error_cc_exists") {
                    $error = "El documento de identidad ya está registrado.";
                } else {
                    $error = "Ocurrió un error durante el registro. Inténtelo de nuevo.";
                }
            }
        }
        // Cargar la vista de registro
        require_once __DIR__ . '/../views/auth/register.php';
    }

    /**
     * Cierra la sesión del usuario actual y redirige al inicio.
     */
    public function logout() {
        session_destroy();
        header("Location: index.php"); // O /login
        exit;
    }
}
?>
