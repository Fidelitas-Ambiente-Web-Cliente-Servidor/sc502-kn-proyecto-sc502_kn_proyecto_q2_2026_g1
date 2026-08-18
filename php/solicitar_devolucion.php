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

$idAdopcion = filter_input(INPUT_POST, "id_adopcion", FILTER_VALIDATE_INT);

if (!$idAdopcion) {
    header("Location: ../seguimiento.php");
    exit;
}

$adoptante = obtenerAdoptanteSesion($conexion);

try {

    $conexion->beginTransaction();

    $info = Adopcion::obtenerActivaDeAdoptante($conexion, $idAdopcion, $adoptante["id_adoptante"]);

    if (!$info) {
        $conexion->rollBack();
        header("Location: ../seguimiento.php?error=sistema");
        exit;
    }

    Adopcion::marcarDevuelta($conexion, $idAdopcion);
    Mascota::marcarDisponible($conexion, $info["id_mascota"]);

    $conexion->commit();

    registrarBitacora($conexion, $_SESSION["id_usuario"], "Inició proceso de devolución", "Mascota: {$info['nombre']}");

    crearNotificacion(
        $conexion,
        (int) $info["id_usuario_refugio"],
        "Devolución de mascota",
        "{$_SESSION['nombre']} {$_SESSION['apellidos']} inició la devolución de {$info['nombre']}.",
        "SISTEMA"
    );

    header("Location: ../seguimiento.php?devolucion=ok");
    exit;

} catch (PDOException $e) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }
    header("Location: ../seguimiento.php?error=sistema");
    exit;
}
