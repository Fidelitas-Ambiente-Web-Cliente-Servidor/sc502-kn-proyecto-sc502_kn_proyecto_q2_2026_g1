<?php

session_start();

require_once "config.php";
require_once "guard.php";
require_once "helpers.php";

requerirRol("ADMIN_GENERAL", "../login.html");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../admin-reportes.php");
    exit;
}

$idReporte = filter_input(INPUT_POST, "id_reporte", FILTER_VALIDATE_INT);
$estado    = $_POST["estado"] ?? "";

if (!$idReporte || !in_array($estado, ["PENDIENTE", "EN_REVISION", "RESUELTO"])) {
    header("Location: ../admin-reportes.php");
    exit;
}

Reporte::cambiarEstado($conexion, $idReporte, $estado);

registrarBitacora($conexion, $_SESSION["id_usuario"], "Actualizó estado de denuncia", "Denuncia #$idReporte -> $estado");

header("Location: ../admin-reportes.php?estado=ok");
exit;
