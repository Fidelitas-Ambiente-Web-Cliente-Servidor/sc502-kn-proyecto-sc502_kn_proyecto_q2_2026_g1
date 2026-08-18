<?php

session_start();

require_once "php/config.php";
require_once "php/guard.php";
require_once "php/helpers.php";

requerirRol("ADMIN_GENERAL", "login.html");

$paginaActivaAdmin = "configuracion";

?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="icon" type="image/svg+xml" href="favicon.svg"><title>Configuración | PawsMatch</title><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="css/estilos.css"><link rel="stylesheet" href="css/responsive.css"><link rel="stylesheet" href="css/admin.css"></head><body class="admin-body" data-page="configuracion">

<?php require "php/views/partials/navbar_admin.php"; ?>

<div class="admin-header"><div><h1>Configuración</h1><p>Parámetros generales del sistema PawsMatch.</p></div></div>

<div class="admin-card" style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;margin-bottom:1.2rem;padding:1rem 1.2rem;">
    Este módulo todavía no está conectado a MySQL: no existe una tabla de configuración en el esquema actual.
    El formulario de abajo es una demostración visual y no persiste los cambios de forma real.
</div>

<section class="admin-card">
    <form class="form-grid" onsubmit="return false;">
        <div><label class="form-label">Nombre del sistema</label><input class="form-control" value="PawsMatch" disabled></div>
        <div><label class="form-label">Correo de soporte</label><input class="form-control" type="email" value="soporte@pawsmatch.com" disabled></div>
        <div><label class="form-label">Tiempo de sesión en minutos</label><input class="form-control" type="number" value="30" disabled></div>
        <div><label class="form-label">Aprobación manual de refugios</label><select class="form-select" disabled><option>Sí</option></select></div>
        <div><label class="form-label">Notificaciones por correo</label><select class="form-select" disabled><option>Sí</option></select></div>
        <div style="grid-column:1/-1"><button class="btn btn-secondary" disabled>Guardar configuración</button></div>
    </form>
</section>

<?php require "php/views/partials/footer_admin.php"; ?>

<script src="js/main.js"></script>
</body></html>
