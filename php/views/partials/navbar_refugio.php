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
                <li><a class="<?php echo $paginaActivaRefugio === 'dashboard' ? 'active' : ''; ?>" href="refugio-dashboard.php"><span>&#9635;</span> Dashboard</a></li>
                <li><a class="<?php echo $paginaActivaRefugio === 'mascotas' ? 'active' : ''; ?>" href="refugio-mascotas.php"><span>&#9635;</span> Mis mascotas</a></li>
                <li><a class="<?php echo $paginaActivaRefugio === 'solicitudes' ? 'active' : ''; ?>" href="refugio-solicitudes.php"><span>&#9635;</span> Solicitudes</a></li>
            </ul>
        </aside>

        <main class="admin-main">
