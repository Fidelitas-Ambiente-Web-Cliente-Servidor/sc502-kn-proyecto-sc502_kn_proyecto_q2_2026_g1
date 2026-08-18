<?php

session_start();

require_once "php/config.php";
require_once "php/guard.php";
require_once "php/helpers.php";

requerirRol("ADMIN_GENERAL", "login.html");

$paginaActivaAdmin = "estadisticas";

$totalUsuarios       = Usuario::contarTotal($conexion);
$totalRefugios       = Refugio::contarTotal($conexion);
$totalMascotas       = Mascota::contarTotal($conexion);
$solicitudesPend     = Solicitud::contarPendientesGlobal($conexion);
$usuariosActivos     = Usuario::contarPorEstado($conexion, "ACTIVO");
$refugiosAprobados   = Refugio::contarPorEstado($conexion, "APROBADO");
$mascotasDisponibles = Mascota::contarDisponibles($conexion);
$adopcionesTotales   = Adopcion::contarTotal($conexion);
$porEspecie          = Mascota::contarPorEspecie($conexion);

?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="icon" type="image/svg+xml" href="favicon.svg"><title>Estadísticas | PawsMatch</title><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="css/estilos.css"><link rel="stylesheet" href="css/responsive.css"><link rel="stylesheet" href="css/admin.css?v=4"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head><body class="admin-body theme-admin" data-page="estadisticas">

<?php require "php/views/partials/navbar_admin.php"; ?>
<?php require "php/views/admin/estadisticas.php"; ?>
<?php require "php/views/partials/footer_admin.php"; ?>

<script src="js/main.js"></script>
</body></html>
