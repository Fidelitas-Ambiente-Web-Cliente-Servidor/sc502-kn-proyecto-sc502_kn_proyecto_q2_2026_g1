<?php

session_start();

require_once "php/config.php";
require_once "php/models/autoload.php";

$paginaActivaPublica = "inicio";

$mascotasDisponibles = Mascota::listarDisponibles($conexion);
$mascotasCarousel = array_slice($mascotasDisponibles, 0, 8);

$statAdopciones = Adopcion::contarTotal($conexion);
$statRefugios = Refugio::contarPorEstado($conexion, "APROBADO");
$statFamilias = Adopcion::contarFamiliasUnicas($conexion);
$statDisponibles = count($mascotasDisponibles);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <title>PawsMatch - Adopción responsable</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>

<?php require "php/views/partials/navbar_publico.php"; ?>

<?php require "php/views/publico/index.php"; ?>

<?php require "php/views/partials/footer_publico.php"; ?>

<script src="js/main.js"></script>
</body>
</html>
