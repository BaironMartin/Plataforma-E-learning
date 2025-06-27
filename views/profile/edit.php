<?php
// Vars: $pageTitle, $userData, $hasSecurityQuestions, $successMessage, $errorMessage
include __DIR__ . '/../layouts/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <!-- Menú Lateral (si aplica, o un menú de usuario general) -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar" style="padding-top:20px; border-right: 1px solid #ddd;">
            <div class="sidebar-sticky">
                <h4>Mi Cuenta</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link active" href="index.php?action=editProfile">Editar Perfil</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=changePassword">Cambiar Contraseña</a></li> <!-- Necesitará acción y vista -->
                    <?php if ($hasSecurityQuestions): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=manageSecurityQuestions">Preguntas de Seguridad</a></li> <!-- Necesitará acción y vista -->
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=setupSecurityQuestions">Configurar Preguntas de Seguridad</a></li> <!-- Necesitará acción y vista -->
                    <?php endif; ?>
                </ul>
                <hr>
                <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm">Volver al Dashboard</a>
            </div>
        </nav>

        <!-- Contenido Principal -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?php echo htmlspecialchars($pageTitle); ?></h1>
            </div>

            <?php if (isset($successMessage)): ?><div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div><?php endif; ?>
            <?php if (isset($errorMessage)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div><?php endif; ?>
            <?php if (isset($_SESSION['info_message'])): ?><div class="alert alert-info"><?php echo htmlspecialchars($_SESSION['info_message']); unset($_SESSION['info_message']); ?></div><?php endif; ?>


            <form action="index.php?action=updateProfile" method="post" enctype="multipart/form-data" class="formu edit-profile-form">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Correo Electrónico (No se puede cambiar)</label>
                            <input type="email" id="email" class="form-control formu-input-readonly" name="user_email_display" value="<?php echo htmlspecialchars($userData['Email']); ?>" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nombre" class="form-label">Nombre Completo</label>
                            <input type="text" id="nombre" class="form-control formu-input" name="nombre" value="<?php echo htmlspecialchars($userData['Nombre']); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="cc" class="form-label">Documento de Identidad</label>
                            <input type="text" id="cc" class="form-control formu-input" name="cc" value="<?php echo htmlspecialchars($userData['cc']); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="baner" class="form-label">URL de la Imagen de Banner</label>
                            <input type="text" id="baner" class="form-control formu-input" name="baner" value="<?php echo htmlspecialchars($userData['baner'] ?? ''); ?>" placeholder="https://ejemplo.com/banner.jpg">
                             <?php if (!empty($userData['baner'])): ?>
                                <div class="mt-2">
                                    <p class="mb-1">Banner Actual:</p>
                                    <img src="<?php echo htmlspecialchars($userData['baner']); ?>" alt="Banner actual" style="max-width: 100%; height: auto; max-height: 150px; border:1px solid #ccc;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <hr>
                        <div class="form-group mb-3">
                            <label for="current_password" class="form-label fw-bold text-warning">Contraseña Actual (requerida para guardar cambios)</label>
                            <input type="password" id="current_password" class="form-control formu-input" name="current_password" placeholder="Ingresa tu contraseña actual" required>
                        </div>

                        <button type="submit" class="btn btn-primary formu-button">Guardar Cambios</button>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="photo" class="form-label">Foto de Perfil</label>
                            <input type="file" id="photo" class="form-control formu-input-file" name="photo" accept="image/*">
                            <?php if (!empty($userData['Foto'])): ?>
                                <div class="mt-2">
                                    <p class="mb-1">Foto Actual:</p>
                                    <img src="archivos/<?php echo htmlspecialchars($userData['Email'] . $userData['Foto']); ?>"
                                         alt="Foto actual"
                                         class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                    <input type="hidden" name="fotoa" value="<?php echo htmlspecialchars($userData['Foto']); ?>">
                                </div>
                            <?php endif; ?>
                            <small class="form-text text-muted">Sube una nueva imagen para cambiarla.</small>
                        </div>
                    </div>
                </div>
            </form>
            <br>
             <?php if (!$hasSecurityQuestions): ?>
                <div class="alert alert-info">
                    Aún no has configurado tus preguntas de seguridad.
                    <a href="index.php?action=setupSecurityQuestions">Configúralas ahora</a> para ayudarte a recuperar tu cuenta si olvidas tu contraseña.
                </div>
            <?php endif; ?>


        </main>
    </div>
</div>
<style>
    .formu-input-readonly { background-color: #e9ecef; opacity: 1; }
</style>
<?php
include __DIR__ . '/../layouts/footer.php';
?>
