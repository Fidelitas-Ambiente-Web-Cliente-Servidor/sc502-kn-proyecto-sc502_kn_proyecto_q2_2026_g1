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

$mascotasPorEstado = Mascota::contarPorEstadoDeRefugio($conexion, $idRefugio);
$solicitudesPorEstado = Solicitud::contarPorEstadoDeRefugio($conexion, $idRefugio);
$mascotasPropias = Mascota::listarPorRefugio($conexion, $idRefugio);
$mascotasDestacadas = array_slice(array_filter($mascotasPropias, fn($m) => $m["estado"] === "DISPONIBLE"), 0, 4);

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
    <link rel="stylesheet" href="css/admin.css?v=4"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body <?php echo claseTemaRefugio($refugio['id_refugio']); ?>" data-page="refugio-dashboard">

<?php require "php/views/partials/navbar_refugio.php"; ?>
<?php require "php/views/refugio/dashboard.php"; ?>
<?php require "php/views/partials/footer_refugio.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script src="js/main.js"></script>
<script src="js/toast.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('login') === 'ok') {
        PawsMatchToast.show('¡Bienvenido/a, <?php echo addslashes($_SESSION["nombre"]); ?>!', 'success', 2500);
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    const acento = '<?php echo colorTemaRefugio($idRefugio); ?>';

    new Chart(document.getElementById('chartMascotasEstadoRefugio'), {
        type: 'doughnut',
        data: {
            labels: ['Disponibles', 'En proceso', 'Adoptadas', 'Inactivas'],
            datasets: [{
                data: <?php echo json_encode(array_values($mascotasPorEstado)); ?>,
                backgroundColor: ['#10b981', '#3b82f6', acento, '#d1d5db'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { family: 'Poppins', size: 11 } } } }
        }
    });

    new Chart(document.getElementById('chartSolicitudesEstado'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_map(fn($k) => $etiquetas[$k], array_keys($solicitudesPorEstado))); ?>,
            datasets: [{
                data: <?php echo json_encode(array_values($solicitudesPorEstado)); ?>,
                backgroundColor: acento,
                borderRadius: 8,
                maxBarThickness: 34,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
});
</script>
</body>
</html>
