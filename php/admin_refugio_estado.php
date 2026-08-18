<?php

session_start();

require_once "config.php";
require_once "guard.php";
require_once "helpers.php";

requerirRol("ADMIN_GENERAL", "../login.html");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../admin-refugios.php");
    exit;
}

$idRefugio = filter_input(INPUT_POST, "id_refugio", FILTER_VALIDATE_INT);
$accion    = $_POST["accion"] ?? "";

if (!$idRefugio || !in_array($accion, ["aprobar", "rechazar"])) {
    header("Location: ../admin-refugios.php");
    exit;
}

$refugio = Refugio::obtenerPorId($conexion, $idRefugio);

if (!$refugio) {
    header("Location: ../admin-refugios.php");
    exit;
}

$nuevoEstado = $accion === "aprobar" ? "APROBADO" : "RECHAZADO";
Refugio::cambiarEstado($conexion, $idRefugio, $nuevoEstado);

registrarBitacora($conexion, $_SESSION["id_usuario"], "Cambió estado de refugio", "{$refugio['nombre_refugio']} -> $nuevoEstado");

crearNotificacion(
    $conexion,
    (int) $refugio["id_usuario"],
    $accion === "aprobar" ? "Tu refugio fue aprobado" : "Tu refugio fue rechazado",
    $accion === "aprobar"
        ? "{$refugio['nombre_refugio']} ya está aprobado y puede publicar mascotas."
        : "{$refugio['nombre_refugio']} fue rechazado por el administrador.",
    "SISTEMA"
);

header("Location: ../admin-refugios.php?estado=ok");
exit;
