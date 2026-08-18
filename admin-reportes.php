<?php

session_start();

require_once "php/config.php";
require_once "php/guard.php";
require_once "php/helpers.php";

requerirRol("ADMIN_GENERAL", "login.html");

$paginaActivaAdmin = "reportes";

$denuncias = Reporte::listarConDetalle($conexion);
$pendientesDenuncia = Reporte::contarPendientes($conexion);

$estadoBadge = ["PENDIENTE" => "background:#fef3c7;color:#92400e;", "EN_REVISION" => "background:#dbeafe;color:#1e40af;", "RESUELTO" => "background:#dcfce7;color:#166534;"];

?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="icon" type="image/svg+xml" href="favicon.svg"><title>Reportes | PawsMatch</title><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="css/estilos.css"><link rel="stylesheet" href="css/responsive.css"><link rel="stylesheet" href="css/admin.css?v=4"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head><body class="admin-body theme-admin" data-page="reportes">

<?php require "php/views/partials/navbar_admin.php"; ?>
<?php require "php/views/admin/reportes.php"; ?>
<?php require "php/views/partials/footer_admin.php"; ?>

<script src="js/main.js"></script>
<script src="js/toast.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('estado') === 'ok') PawsMatchToast.show('Estado de la denuncia actualizado.', 'success', 2200);
    if (params.toString() !== '') window.history.replaceState({}, document.title, window.location.pathname);
});
</script>
</body></html>
