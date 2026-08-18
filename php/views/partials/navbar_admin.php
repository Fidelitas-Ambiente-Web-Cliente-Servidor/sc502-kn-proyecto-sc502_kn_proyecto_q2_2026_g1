<?php
/**
 * Vista parcial: navbar + sidebar del panel de administración.
 * Requiere $paginaActivaAdmin definida antes del include.
 */
$itemsMenuAdmin = [
    "dashboard"      => ["admin-dashboard.php", "fa-gauge-high", "Dashboard"],
    "usuarios"       => ["admin-usuarios.php", "fa-users", "Usuarios"],
    "refugios"       => ["admin-refugios.php", "fa-house-chimney", "Refugios"],
    "roles"          => ["admin-roles.php", "fa-shield-halved", "Roles"],
    "estadisticas"   => ["admin-estadisticas.php", "fa-chart-line", "Estadísticas"],
    "reportes"       => ["admin-reportes.php", "fa-flag", "Reportes"],
    "bitacora"       => ["admin-bitacora.php", "fa-clock-rotate-left", "Bitácora"],
    "notificaciones" => ["admin-notificaciones.php", "fa-bell", "Notificaciones"],
];
?>
<nav class="navbar">
  <div class="nav-container">
    <a class="logo" href="index.php">
      <div><span>PawsMatch</span><small>Panel administrativo</small></div>
    </a>
    <ul class="nav-menu" id="navMenu">
      <li><a href="index.php">Inicio público</a></li>
      <li><a href="admin-dashboard.php">Admin</a></li>
      <li><a href="admin-reportes.php">Reportes</a></li>
    </ul>
    <div class="nav-buttons"><a href="php/logout.php" class="btn-login"><i class="fas fa-right-from-bracket me-1"></i> Cerrar sesión</a></div>
    <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
  </div>
</nav>
<div class="admin-layout">
<aside class="admin-sidebar">
<div class="sidebar-title">Administrador General</div>
<ul class="sidebar-menu">
<?php foreach ($itemsMenuAdmin as $clave => [$href, $icono, $texto]): ?>
    <li><a class="<?php echo $clave === $paginaActivaAdmin ? 'active' : ''; ?>" href="<?php echo $href; ?>"><i class="fas <?php echo $icono; ?>"></i> <?php echo $texto; ?></a></li>
<?php endforeach; ?>
</ul>
</aside>
<main class="admin-main">
