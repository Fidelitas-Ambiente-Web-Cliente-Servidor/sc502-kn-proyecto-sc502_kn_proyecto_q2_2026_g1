<?php
/**
 * Vista parcial: navbar + sidebar del portal de refugio.
 * Requiere $paginaActivaRefugio ('dashboard'|'mascotas'|'solicitudes') y $refugio (fila de la tabla refugios).
 */
?>
    <nav class="navbar">
        <div class="nav-container">
            <a class="logo" href="index.php">
                <div><span>PawsMatch</span><small>Portal de refugio</small></div>
            </a>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php">Inicio público</a></li>
                <li><a href="refugio-mascotas.php">Mis mascotas</a></li>
                <li><a href="refugio-solicitudes.php">Solicitudes</a></li>
            </ul>
            <div class="nav-buttons">
                <a href="php/logout.php" class="btn-login">Cerrar sesión</a>
            </div>
            <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
        </div>
    </nav>

    <div class="admin-layout">

        <aside class="admin-sidebar">
            <div class="sidebar-title"><?php echo htmlspecialchars($refugio['nombre_refugio']); ?></div>
            <ul class="sidebar-menu">
                <li><a class="<?php echo $paginaActivaRefugio === 'dashboard' ? 'active' : ''; ?>" href="refugio-dashboard.php"><i class="fas fa-gauge-high"></i> Dashboard</a></li>
                <li><a class="<?php echo $paginaActivaRefugio === 'mascotas' ? 'active' : ''; ?>" href="refugio-mascotas.php"><i class="fas fa-paw"></i> Mis mascotas</a></li>
                <li><a class="<?php echo $paginaActivaRefugio === 'solicitudes' ? 'active' : ''; ?>" href="refugio-solicitudes.php"><i class="fas fa-clipboard-list"></i> Solicitudes</a></li>
            </ul>
        </aside>

        <main class="admin-main">

            <?php
            $bannerTextosRefugio = [
                'dashboard'   => ['¡Hola, ' . htmlspecialchars($refugio['nombre_refugio']) . '!', 'Aquí tienes el resumen de tu actividad en PawsMatch.'],
                'mascotas'    => ['Mis mascotas', 'Administra, publica y edita las mascotas disponibles para adopción.'],
                'solicitudes' => ['Solicitudes recibidas', 'Revisa y responde a las personas interesadas en adoptar.'],
            ];
            [$bannerTituloRefugio, $bannerDescRefugio] = $bannerTextosRefugio[$paginaActivaRefugio] ?? ['¡Hola, ' . htmlspecialchars($refugio['nombre_refugio']) . '!', 'Bienvenido/a de vuelta a PawsMatch.'];
            ?>
            <div class="welcome-banner">
                <div class="welcome-banner-content">
                    <h1><?php echo $bannerTituloRefugio; ?></h1>
                    <p><?php echo $bannerDescRefugio; ?></p>
                </div>
                <div class="welcome-banner-image">
                    <img src="https://images.unsplash.com/photo-1583511655826-05700d52f4d9?auto=format&fit=crop&w=500&q=70" alt="">
                </div>
            </div>
