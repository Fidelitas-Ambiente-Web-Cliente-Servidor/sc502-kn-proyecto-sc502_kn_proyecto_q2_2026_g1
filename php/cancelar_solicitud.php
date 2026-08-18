<?php

session_start();

require_once "config.php";
require_once "guard.php";
require_once "helpers.php";

requerirRol("ADOPTANTE", "../login.html");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../solicitudes.php");
    exit;
}

$idSolicitud = filter_input(INPUT_POST, "id_solicitud", FILTER_VALIDATE_INT);

if (!$idSolicitud) {
    header("Location: ../solicitudes.php");
    exit;
}

$adoptante = obtenerAdoptanteSesion($conexion);

$cancelada = Solicitud::cancelar($conexion, $idSolicitud, $adoptante["id_adoptante"]);

if ($cancelada) {
    registrarBitacora($conexion, $_SESSION["id_usuario"], "Canceló solicitud", "Solicitud #$idSolicitud");
    header("Location: ../solicitudes.php?cancelada=ok");
    exit;
}

header("Location: ../solicitudes.php?error=nocancelable");
exit;
