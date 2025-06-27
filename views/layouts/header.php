<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Plataforma Educativa'; ?></title>
    <link rel="stylesheet" href="css/estilos.css"> <!-- Ajusta la ruta si es necesario -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- Si se usa Materialize o similar para el admin, se necesitarían más enlaces o lógica condicional -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- Para CKEditor u otros JS globales si se mueven a un layout general -->
</head>
<body>
    <div class="pegajoso">
        <!-- Asumo que quieres mantener esta cabecera, podría variar por página -->
        <img src="img/logo.png" alt="Logo Plataforma" style="height: 50px;"> <!-- Ejemplo de estilo -->
        <h1>
            <?php
            // Esto podría venir de una configuración o ser dinámico
            // Para simplificar, lo pongo estático, pero antes era include('includes/name.php')
            echo "Institución Educativa Horizontes";
            ?>
        </h1>
    </div>
    <br>
    <main>
    <!-- El contenido principal de cada vista irá aquí -->
</body> <!-- Se cierra en footer.php -->
