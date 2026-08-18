<?php

session_start();

require_once "config.php";
require_once "helpers.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.html?error=sesion");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../catalogo.php");
    exit;
}

$idMascota   = filter_input(INPUT_POST, "id_mascota", FILTER_VALIDATE_INT) ?: null;
$tipo        = $_POST["tipo"] ?? "";
$descripcion = trim($_POST["descripcion"] ?? "");

$volverA   = $idMascota ? "../detalle_mascota.php?id=$idMascota" : "../catalogo.php";
$separador = str_contains($volverA, "?") ? "&" : "?";

if (!in_array($tipo, ["MALTRATO", "BIENESTAR", "OTRO"]) || $descripcion === "") {
    header("Location: $volverA{$separador}error=campos");
    exit;
}

Reporte::crear($conexion, $_SESSION["id_usuario"], $idMascota, $tipo, $descripcion);

registrarBitacora($conexion, $_SESSION["id_usuario"], "Creó una denuncia", $idMascota ? "Mascota #$idMascota — $tipo" : $tipo);

crearNotificacionParaRol(
    $conexion,
    "ADMIN_GENERAL",
    "Nueva denuncia recibida",
    "{$_SESSION['nombre']} {$_SESSION['apellidos']} reportó un caso de tipo $tipo.",
    "SISTEMA"
);

header("Location: $volverA{$separador}reporte=ok");
exit;
