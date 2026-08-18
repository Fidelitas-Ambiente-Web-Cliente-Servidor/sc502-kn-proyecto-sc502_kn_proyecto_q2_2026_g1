<?php

session_start();

require_once "config.php";
require_once "guard.php";
require_once "helpers.php";

requerirRol("ADOPTANTE", "../login.html");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../seguimiento.php");
    exit;
}

$idSeguimiento = filter_input(INPUT_POST, "id_seguimiento", FILTER_VALIDATE_INT);
$estadoSalud   = trim($_POST["estado_salud"] ?? "");
$adaptacion    = trim($_POST["adaptacion"] ?? "");
$observaciones = trim($_POST["observaciones"] ?? "");

if (!$idSeguimiento) {
    header("Location: ../seguimiento.php");
    exit;
}

$adoptante = obtenerAdoptanteSesion($conexion);

if (!Seguimiento::perteneceAAdoptante($conexion, $idSeguimiento, $adoptante["id_adoptante"])) {
    header("Location: ../seguimiento.php?error=sistema");
    exit;
}

Seguimiento::completar($conexion, $idSeguimiento, $estadoSalud, $adaptacion, $observaciones);

registrarBitacora($conexion, $_SESSION["id_usuario"], "Envió reporte de seguimiento", "Seguimiento #$idSeguimiento");

header("Location: ../seguimiento.php?reporte=ok");
exit;
