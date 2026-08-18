<?php
/**
 * Vista parcial: navbar + sidebar del panel de administración.
 * Requiere $paginaActivaAdmin definida antes del include.
 */
$itemsMenuAdmin = [
    "dashboard"      => ["admin-dashboard.php", "Dashboard"],
    "usuarios"       => ["admin-usuarios.php", "Usuarios"],
    "refugios"       => ["admin-refugios.php", "Refugios"],
    "roles"          => ["admin-roles.php", "Roles"],
    "estadisticas"   => ["admin-estadisticas.php", "Estadísticas"],
    "reportes"       => ["admin-reportes.php", "Reportes"],
    "bitacora"       => ["admin-bitacora.php", "Bitácora"],
    "notificaciones" => ["admin-notificaciones.php", "Notificaciones"],
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
    <div class="nav-buttons"><a href="php/logout.php" class="btn-login">Cerrar sesión</a></div>
    <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
  </div>
</nav>
<div class="admin-layout">
<aside class="admin-sidebar">
<div class="sidebar-title">Administrador General</div>
<ul class="sidebar-menu">
<?php foreach ($itemsMenuAdmin as $clave => [$href, $texto]): ?>
    <li><a class="<?php echo $clave === $paginaActivaAdmin ? 'active' : ''; ?>" href="<?php echo $href; ?>"><?php echo $texto; ?></a></li>
<?php endforeach; ?>
</ul>
</aside>
<main class="admin-main">
