<?php

session_start();

require_once "php/config.php";
require_once "php/models/autoload.php";

$paginaActivaPublica = "mascotas";
$mascotas = Mascota::listarDisponibles($conexion);

/* Matching: si es un adoptante logueado, ordenar por compatibilidad */
$perfilAdoptante = null;
$esAdoptanteSinPreferencias = false;

if (isset($_SESSION["rol"]) && $_SESSION["rol"] === "ADOPTANTE") {
    $perfilAdoptante = Adoptante::obtenerConUsuario($conexion, $_SESSION["id_usuario"]);

    $tienePreferencias = !empty($perfilAdoptante["preferencia_especie"])
        || !empty($perfilAdoptante["preferencia_tamano"])
        || !empty($perfilAdoptante["tiene_ninos"])
        || !empty($perfilAdoptante["tiene_otros_animales"])
        || !empty($perfilAdoptante["experiencia_mascotas"])
        || !empty($perfilAdoptante["tiempo_disponible"])
        || !empty($perfilAdoptante["tipo_vivienda"]);

    if ($tienePreferencias) {
        $mascotas = Matching::ordenarPorCompatibilidad($mascotas, $perfilAdoptante);
    } else {
        $esAdoptanteSinPreferencias = true;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <title>Catálogo | PawsMatch</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>

<?php require "php/views/partials/navbar_publico.php"; ?>

<main class="catalog-page">
<?php require "php/views/publico/catalogo.php"; ?>
</main>

<script src="js/main.js"></script>

</body>
</html>
