<?php

session_start();

require_once "php/config.php";
require_once "php/guard.php";
require_once "php/helpers.php";

requerirRol("ADMIN_GENERAL", "login.html");

$paginaActivaAdmin = "usuarios";

$buscar       = trim($_GET["buscar"] ?? "");
$filtroRol    = $_GET["rol"] ?? "";
$filtroEstado = $_GET["estado"] ?? "";

$usuarios = Usuario::listar($conexion, $buscar, $filtroRol, $filtroEstado);

$totalTodos     = Usuario::contarTotal($conexion);
$totalActivos   = Usuario::contarPorEstado($conexion, "ACTIVO");
$totalInactivos = Usuario::contarPorEstado($conexion, "INACTIVO");
$totalAdmins    = Usuario::contarPorRol($conexion, 1);

$rolBadge = ["ADMIN_GENERAL" => "background:#ede9fe;color:#5b21b6;", "REFUGIO" => "background:#dbeafe;color:#1e40af;", "ADOPTANTE" => "background:#dcfce7;color:#166534;"];

?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="icon" type="image/svg+xml" href="favicon.svg"><title>Gestión de usuarios | PawsMatch</title><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="css/estilos.css"><link rel="stylesheet" href="css/responsive.css"><link rel="stylesheet" href="css/admin.css"></head><body class="admin-body" data-page="usuarios">

<?php require "php/views/partials/navbar_admin.php"; ?>
<?php require "php/views/admin/usuarios.php"; ?>
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
        if (params.get('guardado') === 'ok') PawsMatchToast.show('Usuario creado correctamente.', 'success', 2500);
        if (params.get('estado') === 'ok') PawsMatchToast.show('Estado actualizado.', 'success', 2200);
        const errores = { campos: 'Completa todos los campos (contraseña mín. 6 caracteres).', correo: 'Ese correo ya está registrado.', autobloqueo: 'No puedes cambiar tu propio estado.', sistema: 'Ocurrió un error.' };
        const error = params.get('error');
        if (error && errores[error]) PawsMatchToast.show(errores[error], 'error', 3000);
        if (params.has('guardado') || params.has('estado') || params.has('error')) {
            const url = new URL(window.location);
            ['guardado', 'estado', 'error'].forEach(k => url.searchParams.delete(k));
            window.history.replaceState({}, document.title, url.pathname + url.search);
        }
    });
</script>
</body></html>
