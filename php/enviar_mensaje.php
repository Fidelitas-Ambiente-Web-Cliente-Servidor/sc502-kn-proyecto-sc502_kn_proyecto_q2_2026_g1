<?php

session_start();

require_once "config.php";
require_once "helpers.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.html?error=sesion");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit;
}

$idSolicitud = filter_input(INPUT_POST, "id_solicitud", FILTER_VALIDATE_INT);
$texto       = trim($_POST["mensaje"] ?? "");

if (!$idSolicitud) {
    header("Location: ../index.php");
    exit;
}

if ($texto === "") {
    header("Location: ../mensajes.php?solicitud=$idSolicitud&error=vacio");
    exit;
}

$info = Mensaje::obtenerParticipantes($conexion, $idSolicitud);

if (!$info) {
    header("Location: ../index.php");
    exit;
}

$idUsuarioActual = (int) $_SESSION["id_usuario"];

if ($idUsuarioActual === (int) $info["id_usuario_adoptante"]) {
    $idDestinatario = (int) $info["id_usuario_refugio"];
} elseif ($idUsuarioActual === (int) $info["id_usuario_refugio"]) {
    $idDestinatario = (int) $info["id_usuario_adoptante"];
} else {
    header("Location: ../index.php?error=permiso");
    exit;
}

Mensaje::enviar($conexion, $idUsuarioActual, $idDestinatario, $idSolicitud, $texto);

registrarBitacora($conexion, $idUsuarioActual, "Envió un mensaje", "Solicitud #$idSolicitud");

crearNotificacion(
    $conexion,
    $idDestinatario,
    "Nuevo mensaje",
    "{$_SESSION['nombre']} {$_SESSION['apellidos']} te escribió sobre {$info['mascota']}.",
    "MENSAJE"
);

header("Location: ../mensajes.php?solicitud=$idSolicitud");
exit;
