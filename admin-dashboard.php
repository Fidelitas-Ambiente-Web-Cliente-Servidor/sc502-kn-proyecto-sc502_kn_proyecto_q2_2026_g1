<?php

session_start();

require_once "php/config.php";
require_once "php/guard.php";
require_once "php/helpers.php";

requerirRol("ADMIN_GENERAL", "login.html");

$paginaActivaAdmin = "dashboard";

$totalUsuarios = Usuario::contarTotal($conexion);
$totalRefugios = Refugio::contarTotal($conexion);
$totalMascotas = Mascota::contarTotal($conexion);
$adopcionesMes = Adopcion::contarDelMesActual($conexion);
$alertasPendientes = Notificacion::contarNoLeidas($conexion, $_SESSION["id_usuario"]);
$ultimasSolicitudes = Solicitud::listarRecientesGlobal($conexion, 5);
$alertas = Notificacion::listarPorUsuario($conexion, $_SESSION["id_usuario"], 4);

$mascotasPorEstado = Mascota::contarPorEstadoGeneral($conexion);
$adopcionesPorMes = Adopcion::contarPorMes($conexion, 6);
$mascotasDestacadas = array_slice(Mascota::listarDisponibles($conexion), 0, 4);

$refugiosPendientes = Refugio::contarPorEstado($conexion, "PENDIENTE");
$denunciasPendientes = Reporte::contarPendientes($conexion);
$mascotasEstancadas = Mascota::contarEstancadas($conexion, 30);

$etiquetas = ["PENDIENTE" => "Pendiente", "EN_REVISION" => "En revisión", "APROBADA" => "Aprobada", "RECHAZADA" => "Rechazada", "CANCELADA" => "Cancelada"];

?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="icon" type="image/svg+xml" href="favicon.svg"><title>Dashboard administrador | PawsMatch</title><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="css/estilos.css"><link rel="stylesheet" href="css/responsive.css"><link rel="stylesheet" href="css/admin.css?v=4"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head><body class="admin-body theme-admin" data-page="dashboard">

<?php require "php/views/partials/navbar_admin.php"; ?>
<?php require "php/views/admin/dashboard.php"; ?>
<?php require "php/views/partials/footer_admin.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script src="js/main.js"></script><script src="js/toast.js"></script><script>
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('login') === 'ok') {
        PawsMatchToast.show('¡Bienvenido/a, <?php echo addslashes($_SESSION["nombre"]); ?>!', 'success', 2500);
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    const acento = '<?php echo colorTemaAdmin(); ?>';

    new Chart(document.getElementById('chartAdopcionesMes'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($adopcionesPorMes, "etiqueta")); ?>,
            datasets: [{
                label: 'Adopciones',
                data: <?php echo json_encode(array_column($adopcionesPorMes, "total")); ?>,
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

    new Chart(document.getElementById('chartMascotasEstado'), {
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
});
</script></body></html>
