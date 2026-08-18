<?php

session_start();

require_once "php/config.php";
require_once "php/guard.php";
require_once "php/helpers.php";

requerirRol("ADMIN_GENERAL", "login.html");

$paginaActivaAdmin = "bitacora";

$buscar = trim($_GET["buscar"] ?? "");
$registros = Bitacora::listar($conexion, $buscar);

?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="icon" type="image/svg+xml" href="favicon.svg"><title>Bitácora | PawsMatch</title><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="css/estilos.css"><link rel="stylesheet" href="css/responsive.css"><link rel="stylesheet" href="css/admin.css"></head><body class="admin-body" data-page="bitacora">

<?php require "php/views/partials/navbar_admin.php"; ?>
<?php require "php/views/admin/bitacora.php"; ?>
<?php require "php/views/partials/footer_admin.php"; ?>

<script src="js/main.js"></script>
</body></html>
