<?php
/**
 * Vista parcial: barra superior + sidebar del panel de adoptante.
 * Requiere $paginaActivaAdoptante ('perfil'|'solicitudes'|'seguimiento'|'mensajes') y $_SESSION con datos del usuario.
 */
$notificacionesNoLeidas = Notificacion::contarNoLeidas($conexion, $_SESSION["id_usuario"]);
?>
<div class="app-shell">

    <header class="app-topbar">
        <a class="app-logo" href="index.php"><i class="fas fa-paw"></i> PawsMatch</a>
        <a class="app-search" href="catalogo.php">
            <i class="fas fa-search"></i>
            <span>Buscar mascotas, refugios...</span>
        </a>
        <div class="app-topbar-right">
            <a href="solicitudes.php" class="app-icon-btn">
                <i class="fas fa-bell"></i>
                <?php if ($notificacionesNoLeidas > 0): ?><span class="dot"></span><?php endif; ?>
            </a>
            <a href="perfil.php" class="app-user-menu">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nombre'] . ' ' . $_SESSION['apellidos']); ?>&background=3b82f6&color=fff" width="38" height="38" alt="Avatar">
                <div class="app-user-info">
                    <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong>
                    <span>Adoptante</span>
                </div>
            </a>
            <a href="php/logout.php" class="app-icon-btn" title="Cerrar sesión"><i class="fas fa-right-from-bracket"></i></a>
        </div>
    </header>

    <div class="app-body">
        <nav class="app-sidebar">
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Inicio</a></li>
                <li><a class="<?php echo $paginaActivaAdoptante === 'perfil' ? 'active' : ''; ?>" href="perfil.php"><i class="fas fa-user"></i> Mi Perfil</a></li>
                <li><a class="<?php echo $paginaActivaAdoptante === 'solicitudes' ? 'active' : ''; ?>" href="solicitudes.php"><i class="fas fa-file-alt"></i> Mis Solicitudes</a></li>
                <li><a href="perfil.php#favoritos"><i class="fas fa-heart"></i> Mis Favoritos</a></li>
                <li><a class="<?php echo $paginaActivaAdoptante === 'seguimiento' ? 'active' : ''; ?>" href="seguimiento.php"><i class="fas fa-heart-pulse"></i> Seguimiento</a></li>
                <li><a href="solicitudes.php"><i class="fas fa-envelope"></i> Mensajes</a></li>
            </ul>
        </nav>

        <main class="app-main">

            <?php
            $bannerTextos = [
                'perfil'      => ['¡Hola, ' . htmlspecialchars($_SESSION['nombre']) . '!', 'Completa tu perfil para encontrar mascotas más compatibles contigo.'],
                'solicitudes' => ['Tus solicitudes de adopción', 'Aquí puedes ver el estado de cada solicitud y conversar con el refugio.'],
                'seguimiento' => ['Seguimiento post-adopción', 'Ayúdanos a confirmar que tu mascota se está adaptando bien a su nuevo hogar.'],
            ];
            [$bannerTitulo, $bannerDesc] = $bannerTextos[$paginaActivaAdoptante] ?? ['¡Hola, ' . htmlspecialchars($_SESSION['nombre']) . '!', 'Bienvenido/a de vuelta a PawsMatch.'];
            ?>
            <div class="welcome-banner">
                <div class="welcome-banner-content">
                    <h1><?php echo $bannerTitulo; ?></h1>
                    <p><?php echo $bannerDesc; ?></p>
                </div>
                <div class="welcome-banner-image">
                    <img src="https://images.unsplash.com/photo-1543852786-1cf6624b9987?auto=format&fit=crop&w=500&q=70" alt="">
                </div>
            </div>
