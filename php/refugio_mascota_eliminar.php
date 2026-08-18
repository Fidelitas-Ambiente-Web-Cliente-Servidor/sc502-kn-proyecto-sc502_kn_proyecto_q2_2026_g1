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

if ($mascota["estado"] === "ADOPTADO") {
    header("Location: ../refugio-mascotas.php?error=adoptada");
    exit;
}

Mascota::eliminar($conexion, $idMascota, $refugio["id_refugio"]);

registrarBitacora($conexion, $_SESSION["id_usuario"], "Eliminó mascota", $mascota["nombre"]);

header("Location: ../refugio-mascotas.php?eliminado=ok");
exit;
