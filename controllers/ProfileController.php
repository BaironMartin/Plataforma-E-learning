<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';

class ProfileController extends BaseController {
    private $userModel;

    public function __construct() {
        parent::__construct(); // Asegura que el usuario esté autenticado
        $this->userModel = new User();
    }

    // Muestra el formulario de edición de perfil
    public function edit() {
        $userEmail = $_SESSION['user_email'];
        $userData = $this->userModel->findByEmail($userEmail);
        if (!$userData) {
            $_SESSION['error_message'] = "No se pudieron cargar los datos de tu perfil.";
            header('Location: index.php?action=dashboard');
            exit;
        }

        $hasSecurityQuestions = $this->userModel->hasSecurityQuestions($userEmail);

        $pageTitle = "Editar Mi Perfil";
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        require_once __DIR__ . '/../views/profile/edit.php';
    }

    // Procesa la actualización del perfil
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=editProfile');
            exit;
        }

        $userEmail = $_SESSION['user_email']; // El email no se cambia, se usa como identificador
        $currentPassword = $_POST['current_password'] ?? '';

        // Verificar contraseña actual
        if (!$this->userModel->checkPassword($userEmail, $currentPassword)) {
            $_SESSION['error_message'] = "La contraseña actual ingresada es incorrecta.";
            header('Location: index.php?action=editProfile');
            exit;
        }

        // Recoger datos del formulario
        $dataToUpdate = [];
        if (isset($_POST['nombre'])) $dataToUpdate['Nombre'] = $_POST['nombre'];
        if (isset($_POST['cc'])) $dataToUpdate['cc'] = $_POST['cc'];
        if (isset($_POST['baner'])) $dataToUpdate['baner'] = $_POST['baner'];

        // Manejo de subida de foto de perfil
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
            $targetDir = __DIR__ . "/../archivos/";
            if (!file_exists($targetDir)) @mkdir($targetDir, 0777, true);

            $photoFile = $_FILES['photo'];
            $photoNameOriginal = basename($photoFile['name']);
            $photoExtension = strtolower(pathinfo($photoNameOriginal, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $maxFileSize = 2 * 1024 * 1024; // 2MB

            if (!in_array($photoExtension, $allowedExtensions)) {
                $_SESSION['error_message'] = "Tipo de archivo no permitido para la foto de perfil (solo JPG, JPEG, PNG, GIF).";
            } elseif ($photoFile['size'] > $maxFileSize) {
                $_SESSION['error_message'] = "La foto de perfil es demasiado grande (máximo 2MB).";
            } else {
                $sanitizedEmail = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $userEmail);
                // Usar el id del usuario o un UUID sería más robusto para el nombre de archivo que el email + timestamp.
                // Pero por ahora, mantenemos consistencia con la idea original de email + nombre o timestamp.
                $photoName = $sanitizedEmail . "_profile_" . time() . "." . $photoExtension;
                $targetFile = $targetDir . $photoName;

                if (move_uploaded_file($photoFile['tmp_name'], $targetFile)) {
                    $dataToUpdate['Foto'] = $photoName;
                    // Considerar eliminar la foto antigua aquí
                    $currentUserDataForOldPic = $this->userModel->findByEmail($userEmail);
                    if ($currentUserDataForOldPic && !empty($currentUserDataForOldPic['Foto']) && $currentUserDataForOldPic['Foto'] !== $photoName) {
                        $oldPhotoPath = $targetDir . $currentUserDataForOldPic['Foto'];
                        if (file_exists($oldPhotoPath)) {
                            @unlink($oldPhotoPath);
                        }
                    }
                } else {
                    $_SESSION['error_message'] = "Error al subir la nueva foto de perfil.";
                }
            }
        }
        // No se necesita 'fotoa' si el modelo solo actualiza los campos provistos en $dataToUpdate.

        // Solo proceder a actualizar si no hay un error de subida de archivo que deba detener la operación.
        // Si hay error_message por validación de archivo, la actualización de otros datos no debería ocurrir,
        // o el usuario debería ser informado claramente.
        $canUpdateProfile = true;
        if (isset($_SESSION['error_message']) && strpos($_SESSION['error_message'], 'foto') !== false) { // Si el error es de foto
            // Si la foto era opcional y falló, ¿permitir actualizar otros datos?
            // Por ahora, si hay error de foto, no actualizamos nada más para evitar confusión.
             $canUpdateProfile = false; // No actualiza si la subida de foto falló.
        }

        if ($canUpdateProfile && !empty($dataToUpdate)) {
            if ($this->userModel->updateUserProfile($userEmail, $dataToUpdate)) {
                $_SESSION['success_message'] = "Perfil actualizado exitosamente.";
                if(isset($dataToUpdate['Nombre'])) $_SESSION['user_name'] = $dataToUpdate['Nombre'];
            } else {
                 // Si no hubo error_message previo, pero la actualización falló o no hizo cambios.
                if (!isset($_SESSION['error_message'])) {
                    $_SESSION['info_message'] = "No hubo cambios en el perfil o error al guardar.";
                }
            }
        } elseif ($canUpdateProfile && empty($dataToUpdate) && !isset($_SESSION['error_message']) && !isset($_SESSION['success_message'])) {
             $_SESSION['info_message'] = "No se proporcionaron nuevos datos para actualizar.";
        }
        // Si $canUpdateProfile es false debido a error de foto, el error_message ya está en sesión.

        header('Location: index.php?action=editProfile');
        exit;
    }
}
?>
