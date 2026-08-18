<?php

session_start();

require_once "php/config.php";
require_once "php/guard.php";
require_once "php/helpers.php";

requerirRol("ADOPTANTE", "login.html");

$paginaActivaAdoptante = "solicitudes";

$adoptante = obtenerAdoptanteSesion($conexion);
$todas = Solicitud::listarPorAdoptante($conexion, $adoptante["id_adoptante"]);

$activas   = array_filter($todas, fn($s) => in_array($s["estado"], ["PENDIENTE", "EN_REVISION"]));
$historial = array_filter($todas, fn($s) => in_array($s["estado"], ["APROBADA", "RECHAZADA", "CANCELADA"]));

$noLeidos = Mensaje::contarNoLeidosPorSolicitudes($conexion, array_column($todas, "id_solicitud"), $_SESSION["id_usuario"]);

$badges = [
    "PENDIENTE" => "bg-warning text-dark", "EN_REVISION" => "bg-info text-dark",
    "APROBADA" => "bg-success", "RECHAZADA" => "bg-danger", "CANCELADA" => "bg-secondary",
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
    <title>Mis Solicitudes - PawsMatch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/adoptante.css?v=4">
</head>
<body>

<?php require "php/views/partials/navbar_adoptante.php"; ?>
<?php require "php/views/adoptante/solicitudes.php"; ?>
<?php require "php/views/partials/footer_adoptante.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/toast.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(window.location.search);
        if (params.get('cancelada') === 'ok') PawsMatchToast.show('Solicitud cancelada correctamente.', 'success', 2500);
        if (params.get('error') === 'nocancelable') PawsMatchToast.show('Esa solicitud ya no se puede cancelar.', 'error', 3000);
        if (params.toString() !== '') window.history.replaceState({}, document.title, window.location.pathname);
    });
</script>
</body>
</html>
