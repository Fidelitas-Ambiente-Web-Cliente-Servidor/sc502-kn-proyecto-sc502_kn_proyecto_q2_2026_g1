<?php
/**
 * Vista parcial: navbar de las páginas públicas.
 * Requiere que $paginaActivaPublica esté definida ('inicio'|'nosotros'|'mascotas'|'como-adoptar'|'contacto').
 * Consciente de la sesión: si hay un usuario logueado, muestra un enlace a su panel
 * en vez de "Iniciar sesión / Registrarse".
 */

$destinoPanelPorRol = [
    "ADMIN_GENERAL" => ["admin-dashboard.php", "Panel administrador"],
    "REFUGIO"       => ["refugio-dashboard.php", "Panel del refugio"],
    "ADOPTANTE"     => ["perfil.php", "Mi Perfil"],
];

$sesionActiva = isset($_SESSION["id_usuario"], $_SESSION["rol"]);
?>
<nav class="navbar">
    <div class="nav-container">
        <div class="logo">
            <div>
                <span>PawsMatch</span>
                <small>Un hogar para siempre</small>
            </div>
        </div>

        <ul class="nav-menu" id="navMenu">
            <li><a href="index.php" class="<?php echo $paginaActivaPublica === 'inicio' ? 'active' : ''; ?>">Inicio</a></li>
            <li><a href="nosotros.php" class="<?php echo $paginaActivaPublica === 'nosotros' ? 'active' : ''; ?>">Nosotros</a></li>
            <li><a href="catalogo.php" class="<?php echo $paginaActivaPublica === 'mascotas' ? 'active' : ''; ?>">Mascotas</a></li>
            <li><a href="como-adoptar.php" class="<?php echo $paginaActivaPublica === 'como-adoptar' ? 'active' : ''; ?>">Cómo adoptar</a></li>
            <li><a href="contacto.php" class="<?php echo $paginaActivaPublica === 'contacto' ? 'active' : ''; ?>">Contacto</a></li>
        </ul>

        <div class="nav-buttons">
            <?php if ($sesionActiva && isset($destinoPanelPorRol[$_SESSION["rol"]])): ?>
                <?php [$href, $texto] = $destinoPanelPorRol[$_SESSION["rol"]]; ?>
                <a href="<?php echo $href; ?>" class="btn-login"><?php echo htmlspecialchars($texto); ?></a>
                <a href="php/logout.php" class="btn-registro">Cerrar sesión</a>
            <?php else: ?>
                <a href="login.html" class="btn-login">Iniciar sesión</a>
                <a href="registro.html" class="btn-registro">Registrarse</a>
            <?php endif; ?>
        </div>

        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</nav>
