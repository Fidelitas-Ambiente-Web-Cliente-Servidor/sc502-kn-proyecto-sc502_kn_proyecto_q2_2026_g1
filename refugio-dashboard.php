<?php

session_start();

require_once "php/config.php";
require_once "php/guard.php";
require_once "php/helpers.php";

requerirRol("REFUGIO", "login.html");

$paginaActivaRefugio = "dashboard";

$refugio    = obtenerRefugioSesion($conexion);
$idRefugio  = $refugio["id_refugio"];

$kpiMascotas = Mascota::contarPorRefugio($conexion, $idRefugio);
$pendientes  = Solicitud::contarPendientesPorRefugio($conexion, $idRefugio);
$ultimasSolicitudes = array_slice(Solicitud::listarPorRefugio($conexion, $idRefugio), 0, 5);

$badges = [
    "PENDIENTE" => "background:#fef3c7;color:#92400e;", "EN_REVISION" => "background:#dbeafe;color:#1e40af;",
    "APROBADA" => "background:#dcfce7;color:#166534;", "RECHAZADA" => "background:#fee2e2;color:#991b1b;",
    "CANCELADA" => "background:#f3f4f6;color:#4b5563;",
];
$etiquetas = [
    "PENDIENTE" => "Pendiente", "EN_REVISION" => "En revisión", "APROBADA" => "Aprobada",
    "RECHAZADA" => "Rechazada", "CANCELADA" => "Cancelada",
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <title>Dashboard | <?php echo htmlspecialchars($refugio['nombre_refugio']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="admin-body" data-page="refugio-dashboard">

<?php require "php/views/partials/navbar_refugio.php"; ?>
<?php require "php/views/refugio/dashboard.php"; ?>
<?php require "php/views/partials/footer_refugio.php"; ?>

<script src="js/main.js"></script>
<script src="js/toast.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('login') === 'ok') {
        PawsMatchToast.show('¡Bienvenido/a, <?php echo addslashes($_SESSION["nombre"]); ?>!', 'success', 2500);
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
</script>
</body>
</html>
