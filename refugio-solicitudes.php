<?php

session_start();

require_once "php/config.php";
require_once "php/guard.php";
require_once "php/helpers.php";

requerirRol("REFUGIO", "login.html");

$paginaActivaRefugio = "solicitudes";

$refugio   = obtenerRefugioSesion($conexion);
$idRefugio = $refugio["id_refugio"];

$todas = Solicitud::listarPorRefugio($conexion, $idRefugio);

$activas   = array_filter($todas, fn($s) => in_array($s["estado"], ["PENDIENTE", "EN_REVISION"]));
$historial = array_filter($todas, fn($s) => in_array($s["estado"], ["APROBADA", "RECHAZADA", "CANCELADA"]));

$noLeidos = Mensaje::contarNoLeidosPorSolicitudes($conexion, array_column($todas, "id_solicitud"), $_SESSION["id_usuario"]);

$total      = count($todas);
$pendientes = count($activas);
$aprobadas  = count(array_filter($todas, fn($s) => $s["estado"] === "APROBADA"));
$rechazadas = count(array_filter($todas, fn($s) => $s["estado"] === "RECHAZADA"));

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
    <title>Solicitudes recibidas | <?php echo htmlspecialchars($refugio['nombre_refugio']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/admin.css?v=4"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body <?php echo claseTemaRefugio($refugio['id_refugio']); ?>" data-page="refugio-solicitudes">

<?php require "php/views/partials/navbar_refugio.php"; ?>
<?php require "php/views/refugio/solicitudes.php"; ?>
<?php require "php/views/partials/footer_refugio.php"; ?>

<script src="js/main.js"></script>
<script src="js/toast.js"></script>
<script>
    function mostrarTab(tab) {
        const activas   = document.getElementById('panelActivas');
        const historial = document.getElementById('panelHistorial');
        const btnA      = document.getElementById('tabActivas');
        const btnH      = document.getElementById('tabHistorial');
        if (tab === 'activas') {
            activas.style.display = ''; historial.style.display = 'none';
            btnA.className = 'btn btn-primary'; btnH.className = 'btn btn-secondary';
        } else {
            activas.style.display = 'none'; historial.style.display = '';
            btnA.className = 'btn btn-secondary'; btnH.className = 'btn btn-primary';
        }
    }

    function abrirRechazo(id) {
        document.getElementById('rechazoIdSolicitud').value = id;
        document.getElementById('rechazoModal').classList.add('show');
    }

    document.addEventListener('click', function (e) {
        const closeBtn = e.target.closest('[data-close-modal]');
        if (closeBtn) closeBtn.closest('.modal')?.classList.remove('show');
        if (e.target.classList?.contains('modal')) e.target.classList.remove('show');
    });

    document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(window.location.search);
        if (params.get('resuelto') === 'aprobar') PawsMatchToast.show('Solicitud aprobada. Se generó la adopción y los seguimientos.', 'success', 3500);
        if (params.get('resuelto') === 'rechazar') PawsMatchToast.show('Solicitud rechazada.', 'info', 2500);
        const errores = { permiso: 'No tienes permiso sobre esa solicitud.', estado: 'Esa solicitud ya fue resuelta.', sistema: 'Ocurrió un error.' };
        const error = params.get('error');
        if (error && errores[error]) PawsMatchToast.show(errores[error], 'error', 3000);
        if (params.toString() !== '') window.history.replaceState({}, document.title, window.location.pathname);
    });
</script>
</body>
</html>
