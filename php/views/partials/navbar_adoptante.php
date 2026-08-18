<?php
/**
 * Vista parcial: navbar + sidebar del panel de adoptante.
 * Requiere $paginaActivaAdoptante ('perfil'|'solicitudes'|'seguimiento') y $_SESSION con datos del usuario.
 */
?>
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><i class="fas fa-paw me-2"></i>PawsMatch</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item px-2"><a class="nav-link" href="catalogo.php"><i class="fas fa-search me-1"></i>Explorar</a></li>
                <li class="nav-item dropdown ms-lg-4">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nombre'] . ' ' . $_SESSION['apellidos']); ?>&background=6c5ce7&color=fff" class="rounded-circle me-2" width="32" height="32" alt="Avatar">
                        <span><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-user me-2"></i>Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="php/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-2"></i> Inicio</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo $paginaActivaAdoptante === 'perfil' ? 'active' : ''; ?>" href="perfil.php"><i class="fas fa-user-edit me-2"></i> Mi Perfil</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo $paginaActivaAdoptante === 'solicitudes' ? 'active' : ''; ?>" href="solicitudes.php"><i class="fas fa-file-alt me-2"></i> Mis Solicitudes</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo $paginaActivaAdoptante === 'seguimiento' ? 'active' : ''; ?>" href="seguimiento.php"><i class="fas fa-heartbeat me-2"></i> Seguimiento</a></li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
