<?php
// No extender BaseController ya que estas acciones son para usuarios no logueados (o logueados para setear preguntas)
if (session_status() == PHP_SESSION_NONE) { // Asegurar que la sesión esté iniciada si se necesita para 'setup'
    session_start();
}
require_once __DIR__ . '/../models/User.php';

class PasswordRecoveryController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Muestra el formulario para configurar preguntas de seguridad
    public function setupQuestionsForm() {
        // Esta acción requiere que el usuario esté logueado
        if (!isset($_SESSION['user_email'])) {
            $_SESSION['error_message'] = "Debes iniciar sesión para configurar tus preguntas de seguridad.";
            header('Location: index.php?action=login');
            exit;
        }
        $userEmail = $_SESSION['user_email'];
        if ($this->userModel->hasSecurityQuestions($userEmail)) {
            $_SESSION['info_message'] = "Ya has configurado tus preguntas de seguridad.";
            // Podría redirigir a editProfile o a una página para ver/cambiar preguntas
            header('Location: index.php?action=editProfile');
            exit;
        }

        $pageTitle = "Configurar Preguntas de Seguridad";
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        require_once __DIR__ . '/../views/profile/setup_security_questions.php';
    }

    // Guarda las preguntas de seguridad
    public function saveQuestions() {
        if (!isset($_SESSION['user_email'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $userEmail = $_SESSION['user_email'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $p1 = $_POST['security_question1'] ?? '';
            $r1 = trim($_POST['respuesta1'] ?? '');
            $p2 = $_POST['security_question2'] ?? '';
            $r2 = trim($_POST['respuesta2'] ?? '');

            if (empty($p1) || empty($r1) || empty($p2) || empty($r2)) {
                $_SESSION['error_message'] = "Debes seleccionar y responder ambas preguntas de seguridad.";
            } elseif ($p1 === $p2) {
                $_SESSION['error_message'] = "Debes seleccionar dos preguntas diferentes.";
            } else {
                if ($this->userModel->setSecurityQuestions($userEmail, $p1, $r1, $p2, $r2)) {
                    $_SESSION['success_message'] = "Preguntas de seguridad guardadas exitosamente.";
                    header('Location: index.php?action=editProfile'); // Redirigir al perfil
                    exit;
                } else {
                    $_SESSION['error_message'] = "Error al guardar las preguntas de seguridad.";
                }
            }
        }
        // Si falla, redirige de nuevo al formulario
        header('Location: index.php?action=setupSecurityQuestions');
        exit;
    }

    // Muestra el formulario de restauración de contraseña (paso 1: ingresar email y preguntas)
    public function forgotPasswordForm() {
        // Esta acción es para usuarios no logueados
        if (isset($_SESSION['user_email'])) {
            header('Location: index.php?action=dashboard'); // Si ya está logueado, no necesita esto
            exit;
        }
        $pageTitle = "Restaurar Contraseña";
        $successMessage = $_SESSION['success_message'] ?? null; // Para mensajes post-reset
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        // Pasar la lista de preguntas a la vista
        $preguntasGrupo1 = $this->getPreguntasGrupo1();
        $preguntasGrupo2 = $this->getPreguntasGrupo2();

        require_once __DIR__ . '/../views/auth/reset_password_form.php';
    }

    // Procesa la verificación de preguntas y el cambio de contraseña
    public function processPasswordReset() {
         if (isset($_SESSION['user_email'])) { // No debería estar logueado
            header('Location: index.php?action=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['Email'] ?? '';
            $p1_form = $_POST['security_question1'] ?? '';
            $r1_form = trim($_POST['respuesta1'] ?? '');
            $p2_form = $_POST['security_question2'] ?? '';
            $r2_form = trim($_POST['respuesta2'] ?? '');
            $newPass1 = $_POST['pass1'] ?? '';
            $newPass2 = $_POST['pass2'] ?? '';

            if (empty($email) || empty($p1_form) || empty($r1_form) || empty($p2_form) || empty($r2_form) || empty($newPass1) || empty($newPass2)) {
                $_SESSION['error_message'] = "Todos los campos son requeridos.";
            } else {
                $securityData = $this->userModel->getSecurityQuestions($email);
                if (!$securityData) {
                    // Simular error de preguntas incorrectas para no revelar si el email existe o no con preguntas.
                    // O un mensaje más específico si se prefiere: "No hay preguntas de seguridad configuradas para este email."
                    $_SESSION['error_message'] = "Las preguntas o respuestas de seguridad no coinciden, o no están configuradas.";
                } elseif ($p1_form == $securityData['p1'] && $r1_form == $securityData['r1'] &&
                          $p2_form == $securityData['p2'] && $r2_form == $securityData['r2']) {

                    if ($newPass1 === $newPass2) {
                        if(strlen($newPass1) < 6) { // Ejemplo de validación de longitud mínima
                             $_SESSION['error_message'] = "La nueva contraseña debe tener al menos 6 caracteres.";
                        } else {
                            $hashedNewPassword = hash('sha512', $newPass1);
                            if ($this->userModel->updatePassword($email, $hashedNewPassword)) {
                                $_SESSION['success_message'] = "Contraseña actualizada exitosamente. Ahora puedes iniciar sesión.";
                                header('Location: index.php?action=login'); // Redirigir al login
                                exit;
                            } else {
                                $_SESSION['error_message'] = "Error al actualizar la contraseña.";
                            }
                        }
                    } else {
                        $_SESSION['error_message'] = "Las nuevas contraseñas no coinciden.";
                    }
                } else {
                    $_SESSION['error_message'] = "Las preguntas o respuestas de seguridad no coinciden.";
                }
            }
        }
        // Si falla, redirige de nuevo al formulario de reseteo
        header('Location: index.php?action=forgotPassword');
        exit;
    }

    // Funciones para obtener las listas de preguntas (podrían estar en un helper o config)
    private function getPreguntasGrupo1() {
        return [
            "1" => "¿Cuál es el nombre de tu mejor amigo de la infancia?",
            "2" => "¿Cuál es el apellido de soltera de tu madre?",
            "3" => "¿Cuál fue tu ciudad natal?",
            "4" => "¿Cuál es tu deporte favorito?",
            "5" => "¿Cuál es tu comida favorita?",
            "6" => "¿Cuál es tu personaje histórico favorito?",
            "7" => "¿Cuál es tu libro favorito?",
            "8" => "¿Cuál es tu canción favorita de la infancia?",
            "9" => "¿Cuál es tu película favorita de terror?",
            "10" => "¿Cuál fue el nombre de tu primer/a novio/a?"
        ];
    }
    private function getPreguntasGrupo2() {
        return [
            "11" => "¿Cuál es el nombre de tu mascota?",
            "12" => "¿Cuál es tu color favorito?",
            "13" => "¿Cuál fue tu primer coche?",
            "14" => "¿Cuál es tu película favorita?",
            "15" => "¿Cuál es tu canción favorita?"
        ];
    }
}
?>
