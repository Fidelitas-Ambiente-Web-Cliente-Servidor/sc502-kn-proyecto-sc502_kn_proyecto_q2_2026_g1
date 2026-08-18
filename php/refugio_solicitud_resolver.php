<?php

session_start();

require_once "config.php";
require_once "guard.php";
require_once "helpers.php";

requerirRol("REFUGIO", "../login.html");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../refugio-solicitudes.php");
    exit;
}

$refugio       = obtenerRefugioSesion($conexion);
$idSolicitud   = filter_input(INPUT_POST, "id_solicitud", FILTER_VALIDATE_INT);
$accion        = $_POST["accion"] ?? "";
$observaciones = trim($_POST["observaciones"] ?? "");

if (!$idSolicitud || !in_array($accion, ["aprobar", "rechazar"])) {
    header("Location: ../refugio-solicitudes.php");
    exit;
}

try {

    $conexion->beginTransaction();

    $solicitud = Solicitud::obtenerParaResolucion($conexion, $idSolicitud);

    if (!$solicitud || (int) $solicitud["id_refugio"] !== (int) $refugio["id_refugio"]) {
        $conexion->rollBack();
        header("Location: ../refugio-solicitudes.php?error=permiso");
        exit;
    }

    if (!in_array($solicitud["estado"], ["PENDIENTE", "EN_REVISION"])) {
        $conexion->rollBack();
        header("Location: ../refugio-solicitudes.php?error=estado");
        exit;
    }

    if ($accion === "aprobar") {

        Solicitud::aprobar($conexion, $idSolicitud, $observaciones ?: null);
        Mascota::marcarAdoptada($conexion, $solicitud["id_mascota"]);

        $idAdopcion = Adopcion::crear($conexion, $idSolicitud);
        Seguimiento::crearProgramados($conexion, $idAdopcion);

        Solicitud::rechazarOtrasActivas($conexion, $solicitud["id_mascota"], $idSolicitud);

        $tituloNotif    = "¡Tu solicitud fue aprobada!";
        $mensajeNotif   = "Tu solicitud para adoptar a {$solicitud['mascota']} fue aprobada. ¡Felicidades!";
        $accionBitacora = "Aprobó solicitud de adopción";

    } else {

        Solicitud::rechazar($conexion, $idSolicitud, $observaciones ?: null);

        $tituloNotif    = "Tu solicitud fue rechazada";
        $mensajeNotif   = "Tu solicitud para adoptar a {$solicitud['mascota']} fue rechazada." . ($observaciones ? " Motivo: $observaciones" : "");
        $accionBitacora = "Rechazó solicitud de adopción";
    }

    $conexion->commit();

    registrarBitacora($conexion, $_SESSION["id_usuario"], $accionBitacora, "Solicitud #$idSolicitud — {$solicitud['mascota']}");
    crearNotificacion($conexion, (int) $solicitud["id_usuario_adoptante"], $tituloNotif, $mensajeNotif, "SOLICITUD");

    header("Location: ../refugio-solicitudes.php?resuelto=$accion");
    exit;

} catch (PDOException $e) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }
    header("Location: ../refugio-solicitudes.php?error=sistema");
    exit;
}
