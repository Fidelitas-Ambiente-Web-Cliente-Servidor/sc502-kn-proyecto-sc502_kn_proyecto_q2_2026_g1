<?php

session_start();

require_once "config.php";
require_once "guard.php";
require_once "helpers.php";

requerirRol("REFUGIO", "../login.html");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../refugio-mascotas.php");
    exit;
}

$refugio   = obtenerRefugioSesion($conexion);
$idMascota = filter_input(INPUT_POST, "id_mascota", FILTER_VALIDATE_INT);

if (!$idMascota) {
    header("Location: ../refugio-mascotas.php");
    exit;
}

$mascota = Mascota::obtenerDeRefugio($conexion, $idMascota, $refugio["id_refugio"]);

if (!$mascota) {
    header("Location: ../refugio-mascotas.php?error=permiso");
    exit;
}

$nuevoEstado = Mascota::alternarVisibilidad($conexion, $idMascota, $refugio["id_refugio"], $mascota["estado"]);

if (!$nuevoEstado) {
    header("Location: ../refugio-mascotas.php?error=permiso");
    exit;
}

registrarBitacora($conexion, $_SESSION["id_usuario"], "Cambió visibilidad de mascota", "{$mascota['nombre']} -> $nuevoEstado");

header("Location: ../refugio-mascotas.php?visibilidad=ok");
exit;
