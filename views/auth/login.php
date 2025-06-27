<?php
$pageTitle = "Iniciar Sesión";
include __DIR__ . '/../layouts/header.php';
?>

<div class="contenedor__todo">
    <div class="caja_trasera">
        <div class="caja_trasera_login">
            <h3>Ya tienes una cuenta</h3>
            <p>Iniciar sesión para ingresar a la página</p>
            <!-- Este botón es para la animación JS, se puede mantener o simplificar si no se usa más -->
            <button id="btn_iniciar_secion_vista" onclick="mostrarLogin()">Iniciar sesión</button>
        </div>
        <div class="caja_trasera_register">
            <h3>Aún no tienes una cuenta</h3>
            <p>Regístrate para que puedas iniciar sesión</p>
            <button id="btn_Register_vista" onclick="mostrarRegistro()">Regístrarse</button>
            <br><br>
            <!-- Enlace directo al formulario de registro si el JS falla o para claridad -->
            <a href="index.php?action=register">O ir a Registrarse</a>
        </div>
    </div>

    <div class="contenedor_login_register">
        <!-- FORMULARIO DE LOGIN -->
        <form action="index.php?action=login" method="post" class="formulario_login" id="formulario_login_id">
            <h2>Iniciar Sesión</h2>
            <?php if (isset($error) && $error): ?>
                <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php
            // Mensaje de éxito tras registro
            if (isset($_GET['status']) && $_GET['status'] === 'registration_success'): ?>
                <p style="color:green;">¡Registro exitoso! Ahora puedes iniciar sesión.</p>
            <?php endif; ?>

            <input type="email" name="u" placeholder="Correo Electrónico" required>
            <input type="password" name="p" placeholder="Contraseña" required>
            <br><br>
            <div class="g-recaptcha" data-sitekey="6LcRjHskAAAAAEwUuhbrMYUDI4W2am3GtMfrr4dh"></div>
            <br>
            <button type="submit">Entrar</button>
            <br><br>
            <a href="restaurarPasword.php">Olvidé mi Password</a> <!-- Esto necesitará su propia ruta/controlador MVC -->
        </form>

        <!-- FORMULARIO DE REGISTRO (oculto inicialmente por JS, o en otra página) -->
        <!-- Se podría mover a views/auth/register.php y cargarla con action=register -->
        <!-- Por ahora, lo dejo aquí para replicar el comportamiento original con JS -->
        <form action="index.php?action=register" method="post" enctype="multipart/form-data" class="formulario_register" id="formulario_register_id" style="display:none;">
            <h2>Regístrarse</h2>
            <?php if (isset($error) && $error && isset($_POST['user'])): // Mostrar error de registro si aplica ?>
                <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if (isset($success) && $success): ?>
                <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>

            <input type="email" name="user" placeholder="Correo Electrónico" required value="<?php echo htmlspecialchars($_POST['user'] ?? ''); ?>">
            <input type="password" name="pass" placeholder="Contraseña" required>
            <input type="text" name="nombre" placeholder="Nombre Completo" required value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
            <input type="number" name="cc" placeholder="Documento de identidad" required value="<?php echo htmlspecialchars($_POST['cc'] ?? ''); ?>">
            <label for="photo">Foto de perfil (opcional):</label>
            <input type="file" name="photo" id="photo" accept="image/*"><br><br>
            <select name='tipo' id="tipo" required>
                <option value="">Selecciona tipo</option>
                <option value="Docente" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Docente') ? 'selected' : ''; ?>>Docente</option>
                <option value="Estudiante" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Estudiante') ? 'selected' : ''; ?>>Estudiante</option>
            </select>
            <br><br>
            <select name='grado' id="grado" required>
                <option value="">Selecciona grado</option>
                <option value="Prescolar" <?php echo (isset($_POST['grado']) && $_POST['grado'] == 'Prescolar') ? 'selected' : ''; ?>>Prescolar</option>
                <option value="primero" <?php echo (isset($_POST['grado']) && $_POST['grado'] == 'primero') ? 'selected' : ''; ?>>Primero</option>
                <option value="segundo" <?php echo (isset($_POST['grado']) && $_POST['grado'] == 'segundo') ? 'selected' : ''; ?>>Segundo</option>
                <option value="tercero" <?php echo (isset($_POST['grado']) && $_POST['grado'] == 'tercero') ? 'selected' : ''; ?>>Tercero</option>
                <option value="cuarto" <?php echo (isset($_POST['grado']) && $_POST['grado'] == 'cuarto') ? 'selected' : ''; ?>>Cuarto</option>
                <option value="quinto" <?php echo (isset($_POST['grado']) && $_POST['grado'] == 'quinto') ? 'selected' : ''; ?>>Quinto</option>
                <option value="sexto" <?php echo (isset($_POST['grado']) && $_POST['grado'] == 'sexto') ? 'selected' : ''; ?>>Sexto</option>
                <option value="septimo" <?php echo (isset($_POST['grado']) && $_POST['grado'] == 'septimo') ? 'selected' : ''; ?>>Séptimo</option>
                <option value="octavo" <?php echo (isset($_POST['grado']) && $_POST['grado'] == 'octavo') ? 'selected' : ''; ?>>Octavo</option>
                <option value="noveno" <?php echo (isset($_POST['grado']) && $_POST['grado'] == 'noveno') ? 'selected' : ''; ?>>Noveno</option>
                <option value="decimo" <?php echo (isset($_POST['grado']) && $_POST['grado'] == 'decimo') ? 'selected' : ''; ?>>Décimo</option>
                <option value="undecimo" <?php echo (isset($_POST['grado']) && $_POST['grado'] == 'undecimo') ? 'selected' : ''; ?>>Undécimo</option>
                <option value="noaplica" <?php echo (isset($_POST['grado']) && $_POST['grado'] == 'noaplica') ? 'selected' : ''; ?>>No aplica (Docentes)</option>
            </select>
            <label>En caso de ser docente seleccionar no aplica</label>
            <br><br>
            <button type="submit">Registrar</button>
        </form>
    </div>
</div>

<!-- Mantengo el script original para la animación, pero idealmente se refactorizaría -->
<script>
    // Script para la animación de los formularios (original de js/script.js)
    // Considerar moverlo a un archivo JS dedicado y enlazarlo en el footer.
    var formulario_login = document.querySelector(".formulario_login");
    var formulario_register = document.querySelector(".formulario_register");
    var contenedor_login_register = document.querySelector(".contenedor_login_register");
    var caja_trasera_login = document.querySelector(".caja_trasera_login");
    var caja_trasera_register = document.querySelector(".caja_trasera_register");

    function mostrarRegistro() {
        if (window.innerWidth > 850) {
            formulario_register.style.display = "block";
            contenedor_login_register.style.left = "410px";
            formulario_login.style.display = "none";
            caja_trasera_register.style.opacity = "0";
            caja_trasera_login.style.opacity = "1";
        } else {
            formulario_register.style.display = "block";
            contenedor_login_register.style.left = "0px";
            formulario_login.style.display = "none";
            caja_trasera_register.style.display = "none";
            caja_trasera_login.style.display = "block";
            caja_trasera_login.style.opacity = "1";
        }
    }

    function mostrarLogin() {
        if (window.innerWidth > 850) {
            formulario_register.style.display = "none";
            contenedor_login_register.style.left = "10px";
            formulario_login.style.display = "block";
            caja_trasera_register.style.opacity = "1";
            caja_trasera_login.style.opacity = "0";
        } else {
            formulario_register.style.display = "none";
            contenedor_login_register.style.left = "0px";
            formulario_login.style.display = "block";
            caja_trasera_register.style.display = "block";
            caja_trasera_login.style.display = "none";
        }
    }

    // Para asegurar que el formulario correcto se muestre si PHP recarga la página con un error.
    <?php if (isset($_POST['user'])): // Si hay un POST de registro (implica intento de registro) ?>
        mostrarRegistro();
    <?php else: // Por defecto o en intento de login ?>
        // No es necesario llamar a mostrarLogin() aquí si es el estado por defecto del CSS/HTML.
        // Pero si el CSS oculta login por defecto, entonces sí:
        // mostrarLogin();
    <?php endif; ?>

    // Event Listeners para los botones de la UI (si se mantienen)
    document.getElementById("btn_iniciar_secion_vista").addEventListener("click", mostrarLogin);
    document.getElementById("btn_Register_vista").addEventListener("click", mostrarRegistro);

</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
