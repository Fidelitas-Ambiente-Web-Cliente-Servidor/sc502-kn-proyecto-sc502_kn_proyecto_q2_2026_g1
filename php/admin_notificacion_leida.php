<?php

session_start();

require_once "config.php";
require_once "guard.php";
require_once "helpers.php";

requerirRol("ADMIN_GENERAL", "../login.html");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../admin-notificaciones.php");
    exit;
}

$idNotificacion = filter_input(INPUT_POST, "id_notificacion", FILTER_VALIDATE_INT);

if ($idNotificacion) {
    Notificacion::marcarLeida($conexion, $idNotificacion, $_SESSION["id_usuario"]);
}

header("Location: ../admin-notificaciones.php");
exit;
