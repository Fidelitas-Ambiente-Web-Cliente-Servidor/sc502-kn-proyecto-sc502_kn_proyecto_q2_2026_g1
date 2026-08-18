<?php

session_start();

require_once "config.php";
require_once "guard.php";
require_once "helpers.php";

requerirRol("ADOPTANTE", "../login.html");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../catalogo.php");
    exit;
}

$idMascota = filter_input(INPUT_POST, "id_mascota", FILTER_VALIDATE_INT);
$volverA   = $_POST["volver_a"] ?? "../catalogo.php";

if (!$idMascota) {
    header("Location: ../catalogo.php");
    exit;
}

$adoptante = obtenerAdoptanteSesion($conexion);
$accion    = Favorito::alternar($conexion, $adoptante["id_adoptante"], $idMascota);

$separador = str_contains($volverA, "?") ? "&" : "?";
header("Location: $volverA{$separador}favorito=$accion");
exit;
