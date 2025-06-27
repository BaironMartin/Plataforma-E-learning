<?php
// Vars: $pageTitle, $successMessage, $errorMessage
// $preguntasGrupo1, $preguntasGrupo2 (pasadas por el controlador)
include __DIR__ . '/../layouts/header.php'; // Usar el layout general
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0"><?php echo htmlspecialchars($pageTitle); ?></h1>
                </div>
                <div class="card-body">
                    <?php if (isset($successMessage)): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
                        <p><a href="index.php?action=login" class="btn btn-success">Ir a Iniciar Sesión</a></p>
                    <?php else: ?>
                        <?php if (isset($errorMessage)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div><?php endif; ?>

                        <p class="text-muted">
                            Para restaurar tu contraseña, por favor ingresa tu correo electrónico, responde tus preguntas de seguridad y elige una nueva contraseña.
                        </p>
                        <hr>
                        <form action="index.php?action=processPasswordReset" method="post" class="formu">
                            <div class="mb-3">
                                <label for="Email" class="form-label">Correo Electrónico Registrado:</label>
                                <input type="email" id="Email" name="Email" class="form-control formu-input" required
                                       value="<?php echo htmlspecialchars($_POST['Email'] ?? ''); ?>">
                            </div>
                            <hr>
                            <h5 class="mb-3">Preguntas de Seguridad</h5>
                            <div class="mb-3">
                                <label for="security_question1" class="form-label">Pregunta de Seguridad 1:</label>
                                <select id="security_question1" name="security_question1" class="form-select formu-input" required>
                                    <option value="">-- Selecciona tu primera pregunta --</option>
                                    <?php foreach ($preguntasGrupo1 as $key => $value): ?>
                                        <option value="<?php echo $key; ?>" <?php echo (isset($_POST['security_question1']) && $_POST['security_question1'] == $key) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($value); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="respuesta1" class="form-label">Tu Respuesta a la Pregunta 1:</label>
                                <input type="text" id="respuesta1" name="respuesta1" class="form-control formu-input" required autocomplete="off"
                                       value="<?php echo htmlspecialchars($_POST['respuesta1'] ?? ''); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="security_question2" class="form-label">Pregunta de Seguridad 2:</label>
                                <select id="security_question2" name="security_question2" class="form-select formu-input" required>
                                    <option value="">-- Selecciona tu segunda pregunta --</option>
                                    <?php foreach ($preguntasGrupo2 as $key => $value): ?>
                                         <option value="<?php echo $key; ?>" <?php echo (isset($_POST['security_question2']) && $_POST['security_question2'] == $key) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($value); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="respuesta2" class="form-label">Tu Respuesta a la Pregunta 2:</label>
                                <input type="text" id="respuesta2" name="respuesta2" class="form-control formu-input" required autocomplete="off"
                                       value="<?php echo htmlspecialchars($_POST['respuesta2'] ?? ''); ?>">
                            </div>
                            <hr>
                            <h5 class="mb-3">Nueva Contraseña</h5>
                            <div class="mb-3">
                                <label for="pass1" class="form-label">Nueva Contraseña:</label>
                                <input type="password" id="pass1" name="pass1" class="form-control formu-input" placeholder="Mínimo 6 caracteres" required>
                            </div>
                            <div class="mb-3">
                                <label for="pass2" class="form-label">Confirmar Nueva Contraseña:</label>
                                <input type="password" id="pass2" name="pass2" class="form-control formu-input" required>
                                <div id="passwordMatchStatus" class="form-text mt-1"></div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="rec" class="btn btn-primary formu-button">Restaurar Contraseña</button>
                                <a href="index.php?action=login" class="btn btn-outline-secondary">Cancelar</a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const pass1 = document.getElementById("pass1");
        const pass2 = document.getElementById("pass2");
        const statusDiv = document.getElementById("passwordMatchStatus");

        function checkPasswordMatch() {
            if (pass1.value === "" && pass2.value === "") {
                 statusDiv.textContent = "";
                 return;
            }
            if (pass1.value === pass2.value) {
                statusDiv.textContent = "Las contraseñas coinciden.";
                statusDiv.className = "form-text text-success";
            } else {
                statusDiv.textContent = "Las contraseñas NO coinciden.";
                statusDiv.className = "form-text text-danger";
            }
        }
        if(pass1 && pass2 && statusDiv){ // Asegurarse que los elementos existen
            pass1.addEventListener("input", checkPasswordMatch);
            pass2.addEventListener("input", checkPasswordMatch);
        }
    });
</script>

<?php
// No incluir el footer general si esta página debe ser más simple,
// o asegurarse que el footer no incluya elementos de usuario logueado.
// Para consistencia, lo incluimos.
include __DIR__ . '/../layouts/footer.php';
?>
