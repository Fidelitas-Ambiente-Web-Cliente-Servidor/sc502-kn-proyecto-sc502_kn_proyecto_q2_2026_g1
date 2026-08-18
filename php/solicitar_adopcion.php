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
$motivo    = trim($_POST["motivo"] ?? "");

if (!$idMascota) {
    header("Location: ../catalogo.php");
    exit;
}

$adoptante = obtenerAdoptanteSesion($conexion);

try {

    $conexion->beginTransaction();

    $mascota = Mascota::obtenerParaActualizar($conexion, $idMascota);

    if (!$mascota || $mascota["estado"] !== "DISPONIBLE") {
        $conexion->rollBack();
        header("Location: ../detalle_mascota.php?id=$idMascota&error=nodisponible");
        exit;
    }

    if (Solicitud::existeActiva($conexion, $adoptante["id_adoptante"], $idMascota)) {
        $conexion->rollBack();
        header("Location: ../detalle_mascota.php?id=$idMascota&error=duplicada");
        exit;
    }

    Solicitud::crear($conexion, $adoptante["id_adoptante"], $idMascota, $motivo);

    $conexion->commit();

    registrarBitacora($conexion, $_SESSION["id_usuario"], "Solicitó adopción", "Mascota #$idMascota");

    $info = Mascota::obtenerConRefugioParaNotificar($conexion, $idMascota);
    if ($info) {
        crearNotificacion(
            $conexion,
            (int) $info["id_usuario"],
            "Nueva solicitud de adopción",
            "{$_SESSION['nombre']} {$_SESSION['apellidos']} solicitó adoptar a {$info['nombre']}.",
            "SOLICITUD"
        );
    }

    header("Location: ../detalle_mascota.php?id=$idMascota&solicitud=ok");
    exit;

} catch (PDOException $e) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }
    header("Location: ../detalle_mascota.php?id=$idMascota&error=sistema");
    exit;
}
