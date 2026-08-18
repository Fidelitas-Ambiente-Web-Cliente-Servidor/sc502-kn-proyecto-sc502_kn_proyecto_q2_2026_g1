<?php

session_start();

require_once "php/config.php";
require_once "php/models/autoload.php";
require_once "php/helpers.php";

if (!isset($_SESSION["id_usuario"]) || !in_array($_SESSION["rol"], ["ADOPTANTE", "REFUGIO"])) {
    header("Location: login.html?error=sesion");
    exit;
}

$idSolicitud = filter_input(INPUT_GET, "solicitud", FILTER_VALIDATE_INT);

if (!$idSolicitud) {
    header("Location: index.php");
    exit;
}

$info = Mensaje::obtenerParticipantes($conexion, $idSolicitud);

if (!$info) {
    header("Location: index.php");
    exit;
}

$idUsuarioActual = (int) $_SESSION["id_usuario"];
$esAdoptante = $idUsuarioActual === (int) $info["id_usuario_adoptante"];
$esRefugio   = $idUsuarioActual === (int) $info["id_usuario_refugio"];

if (!$esAdoptante && !$esRefugio) {
    header("Location: index.php?error=permiso");
    exit;
}

$otroParticipante = $esAdoptante
    ? $info["nombre_refugio"]
    : trim($info["adoptante_nombre"] . " " . $info["adoptante_apellidos"]);

$volverA = $esAdoptante ? "solicitudes.php" : "refugio-solicitudes.php";

Mensaje::marcarLeidos($conexion, $idSolicitud, $idUsuarioActual);
$mensajes = Mensaje::listarPorSolicitud($conexion, $idSolicitud);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <title>Mensajes | PawsMatch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/adoptante.css">
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><i class="fas fa-paw me-2"></i>PawsMatch</a>
        <div class="ms-auto">
            <a href="php/logout.php" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container py-4" style="max-width:800px;">
<?php require "php/views/mensajes/conversacion.php"; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
