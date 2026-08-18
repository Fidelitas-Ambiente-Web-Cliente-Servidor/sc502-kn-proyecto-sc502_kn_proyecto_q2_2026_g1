<?php

session_start();

require_once "php/config.php";
require_once "php/guard.php";
require_once "php/helpers.php";

requerirRol("ADMIN_GENERAL", "login.html");

$paginaActivaAdmin = "refugios";

$buscar       = trim($_GET["buscar"] ?? "");
$filtroEstado = $_GET["estado"] ?? "";

$refugios = Refugio::listar($conexion, $buscar, $filtroEstado);

$totalRefugios   = Refugio::contarTotal($conexion);
$totalAprobados  = Refugio::contarPorEstado($conexion, "APROBADO");
$totalPendientes = Refugio::contarPorEstado($conexion, "PENDIENTE");
$totalRechazados = Refugio::contarPorEstado($conexion, "RECHAZADO");

$estadoBadge = ["APROBADO" => "background:#dcfce7;color:#166534;", "PENDIENTE" => "background:#fef3c7;color:#92400e;", "RECHAZADO" => "background:#fee2e2;color:#991b1b;"];

?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="icon" type="image/svg+xml" href="favicon.svg"><title>Gestión de refugios | PawsMatch</title><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="css/estilos.css"><link rel="stylesheet" href="css/responsive.css"><link rel="stylesheet" href="css/admin.css?v=4"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head><body class="admin-body theme-admin" data-page="refugios">

<?php require "php/views/partials/navbar_admin.php"; ?>
<?php require "php/views/admin/refugios.php"; ?>
<?php require "php/views/partials/footer_admin.php"; ?>

<script src="js/main.js"></script>
<script src="js/toast.js"></script>
<script>
    window.abrirModal = function (id) { document.getElementById(id)?.classList.add('show'); };
    document.addEventListener('click', e => {
        const openBtn = e.target.closest('[data-open-modal]');
        if (openBtn) abrirModal(openBtn.dataset.openModal);
        const closeBtn = e.target.closest('[data-close-modal]');
        if (closeBtn) closeBtn.closest('.modal')?.classList.remove('show');
        if (e.target.classList?.contains('modal')) e.target.classList.remove('show');
    });
    document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(window.location.search);
        if (params.get('guardado') === 'ok') PawsMatchToast.show('Refugio creado correctamente.', 'success', 2500);
        if (params.get('estado') === 'ok') PawsMatchToast.show('Estado del refugio actualizado.', 'success', 2200);
        const errores = { campos: 'Completa todos los campos obligatorios.', correo: 'Ese correo ya está registrado.', sistema: 'Ocurrió un error.' };
        const error = params.get('error');
        if (error && errores[error]) PawsMatchToast.show(errores[error], 'error', 3000);
        if ([...params.keys()].length) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
</script>
</body></html>
