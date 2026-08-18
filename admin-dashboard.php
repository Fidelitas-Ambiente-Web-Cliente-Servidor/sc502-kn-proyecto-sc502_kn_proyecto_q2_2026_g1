<?php

session_start();

require_once "php/config.php";
require_once "php/guard.php";
require_once "php/helpers.php";

requerirRol("ADMIN_GENERAL", "login.html");

$paginaActivaAdmin = "dashboard";

$totalUsuarios = Usuario::contarTotal($conexion);
$totalRefugios = Refugio::contarTotal($conexion);
$adopcionesMes = Adopcion::contarDelMesActual($conexion);
$alertasPendientes = Notificacion::contarNoLeidas($conexion, $_SESSION["id_usuario"]);
$ultimasSolicitudes = Solicitud::listarRecientesGlobal($conexion, 4);
$alertas = Notificacion::listarPorUsuario($conexion, $_SESSION["id_usuario"], 4);

$etiquetas = ["PENDIENTE" => "Pendiente", "EN_REVISION" => "En revisión", "APROBADA" => "Aprobada", "RECHAZADA" => "Rechazada", "CANCELADA" => "Cancelada"];

?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="icon" type="image/svg+xml" href="favicon.svg"><title>Dashboard administrador | PawsMatch</title><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="css/estilos.css"><link rel="stylesheet" href="css/responsive.css"><link rel="stylesheet" href="css/admin.css"></head><body class="admin-body" data-page="dashboard">

<?php require "php/views/partials/navbar_admin.php"; ?>
<?php require "php/views/admin/dashboard.php"; ?>
<?php require "php/views/partials/footer_admin.php"; ?>

<script src="js/main.js"></script><script src="js/toast.js"></script><script>
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('login') === 'ok') {
        PawsMatchToast.show('¡Bienvenido/a, <?php echo addslashes($_SESSION["nombre"]); ?>!', 'success', 2500);
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
</script></body></html>
