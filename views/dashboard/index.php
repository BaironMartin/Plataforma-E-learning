<?php
// $pageTitle ya está definida por el DashboardController
// $userData también está definida y contiene los datos del usuario

include __DIR__ . '/../layouts/header.php'; // Incluye el encabezado común
?>

<div class="pegajoso">
    <!-- El h1 del layout ya muestra el nombre de la institución -->
    <!-- Si se quiere un título específico para esta página además del <title>, se puede agregar aquí -->
    <!-- <h2><?php //echo htmlspecialchars($pageTitle); ?></h2> -->
    <div class="container2">
        <!-- Enlace para editar perfil (necesitará su propio controlador y vista) -->
        <a class="editar" href="index.php?action=editProfile">Editar Perfil</a>
        <a class="cerrar" href="index.php?action=logout">Cerrar Sesión</a>
    </div>
</div>
<br>
<article class="card">
    <header class="card__header">
        <?php
        $bannerUrl = !empty($userData['baner']) ? htmlspecialchars($userData['baner']) : 'https://png.pngtree.com/thumb_back/fw800/background/20210115/pngtree-abstract-yellow-zoom-line-empty-banner-background-image_519823.jpg';
        $profileImageUrl = "archivos/" . htmlspecialchars($userData['Email']) . "" . htmlspecialchars($userData['Foto']);
        // Verificar si la imagen de perfil existe, si no, usar una por defecto.
        // Esto es una simplificación, idealmente la ruta completa al archivo se construiría y verificaría.
        // Para este ejemplo, asumimos que $userData['Foto'] tiene un valor y la ruta es correcta.
        // Una comprobación más robusta:
        // if (empty($userData['Foto']) || !file_exists(__DIR__ . '/../../archivos/' . $userData['Email'] . $userData['Foto'])) {
        //     $profileImageUrl = 'img/default-profile.png'; // Asegúrate que esta imagen exista
        // }
        ?>
        <img src="<?php echo $bannerUrl; ?>" alt="Banner del perfil" class="card__header-image" />
        <img src="<?php echo $profileImageUrl; ?>" alt="Imagen de perfil" class="card__header-profile" />
    </header>

    <section class="card__body">
        <p class="card__text"><strong>Nombre:</strong> <?php echo htmlspecialchars($userData['Nombre']); ?></p>
        <p class="card__text"><strong>Documento:</strong> <?php echo htmlspecialchars($userData['cc']); ?></p>
        <p class="card__text"><strong>Email:</strong> <?php echo htmlspecialchars($userData['Email']); ?></p>
        <p class="card__text"><strong>Tipo:</strong> <?php echo htmlspecialchars($userData['Tipo']); ?></p>
        <?php if (isset($userData['grado']) && $userData['grado'] !== 'noaplica'): ?>
            <p class="card__text"><strong>Grado:</strong> <?php echo htmlspecialchars($userData['grado']); ?></p>
        <?php endif; ?>
    </section>

    <div class="card__footer">
        <?php
        $gestionClasesAction = ($userData['Tipo'] == 'Docente') ? "manageClases" : "joinClases";
        $gestionClasesText = ($userData['Tipo'] == 'Docente') ? "Gestionar Mis Clases" : "Unirme/Ver Clases";
        // Estos 'actions' necesitarán ser definidos en el router (index.php) y tener sus controladores.
        ?>
        <a class="crear" href="index.php?action=<?php echo $gestionClasesAction; ?>"><?php echo $gestionClasesText; ?></a>
    </div>
</article>

<?php
include __DIR__ . '/../layouts/footer.php'; // Incluye el pie de página común
?>
