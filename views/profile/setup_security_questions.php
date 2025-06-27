<?php
// Vars: $pageTitle, $successMessage, $errorMessage
// (Controlador debe instanciar las preguntas y pasarlas si es necesario, o hacerlo aquí)
include __DIR__ . '/../layouts/header.php';

// Definir las preguntas aquí o pasarlas desde el controlador
$preguntasGrupo1 = [
    "1" => "¿Cuál es el nombre de tu mejor amigo de la infancia?", "2" => "¿Cuál es el apellido de soltera de tu madre?",
    "3" => "¿Cuál fue tu ciudad natal?", "4" => "¿Cuál es tu deporte favorito?",
    "5" => "¿Cuál es tu comida favorita?", "6" => "¿Cuál es tu personaje histórico favorito?",
    "7" => "¿Cuál es tu libro favorito?", "8" => "¿Cuál es tu canción favorita de la infancia?",
    "9" => "¿Cuál es tu película favorita de terror?", "10" => "¿Cuál fue el nombre de tu primer/a novio/a?"
];
$preguntasGrupo2 = [
    "11" => "¿Cuál es el nombre de tu mascota?", "12" => "¿Cuál es tu color favorito?",
    "13" => "¿Cuál fue tu primer coche?", "14" => "¿Cuál es tu película favorita?",
    "15" => "¿Cuál es tu canción favorita?"
];
?>
<div class="container-fluid">
    <div class="row">
        <!-- Menú Lateral de Perfil -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar" style="padding-top:20px; border-right: 1px solid #ddd;">
            <div class="sidebar-sticky">
                <h4>Mi Cuenta</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="index.php?action=editProfile">Editar Perfil</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=changePassword">Cambiar Contraseña</a></li>
                    <li class="nav-item"><a class="nav-link active" href="index.php?action=setupSecurityQuestions">Preguntas de Seguridad</a></li>
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

            <p>Configura tus preguntas y respuestas de seguridad. Esto te ayudará a recuperar tu cuenta si olvidas tu contraseña.</p>
            <p class="text-danger">Asegúrate de recordar tus respuestas exactamente como las escribes (mayúsculas/minúsculas importan).</p>

            <form action="index.php?action=saveQuestions" method="post" class="formu edit-profile-form">
                <div class="form-group mb-3">
                    <label for="security_question1" class="form-label">Pregunta de Seguridad 1:</label>
                    <select id="security_question1" name="security_question1" class="form-control formu-input" required>
                        <option value="">-- Selecciona tu primera pregunta --</option>
                        <?php foreach ($preguntasGrupo1 as $key => $value): ?>
                            <option value="<?php echo $key; ?>"><?php echo htmlspecialchars($value); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="respuesta1" class="form-label">Respuesta a Pregunta 1:</label>
                    <input type="text" id="respuesta1" name="respuesta1" class="form-control formu-input" required autocomplete="off">
                </div>

                <hr>

                <div class="form-group mb-3">
                    <label for="security_question2" class="form-label">Pregunta de Seguridad 2:</label>
                    <select id="security_question2" name="security_question2" class="form-control formu-input" required>
                        <option value="">-- Selecciona tu segunda pregunta --</option>
                         <?php foreach ($preguntasGrupo2 as $key => $value): ?>
                            <option value="<?php echo $key; ?>"><?php echo htmlspecialchars($value); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="respuesta2" class="form-label">Respuesta a Pregunta 2:</label>
                    <input type="text" id="respuesta2" name="respuesta2" class="form-control formu-input" required autocomplete="off">
                </div>

                <p class="text-muted"><small>Asegúrate de que las preguntas seleccionadas sean diferentes.</small></p>

                <button type="submit" class="btn btn-primary formu-button">Guardar Preguntas</button>
            </form>
        </main>
    </div>
</div>
<?php
include __DIR__ . '/../layouts/footer.php';
?>
