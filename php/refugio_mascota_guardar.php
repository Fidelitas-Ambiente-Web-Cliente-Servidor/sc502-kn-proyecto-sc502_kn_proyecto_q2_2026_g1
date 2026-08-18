<?php

session_start();

require_once "config.php";
require_once "guard.php";
require_once "helpers.php";

requerirRol("REFUGIO", "../login.html");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../refugio-mascotas.php");
    exit;
}

$refugio = obtenerRefugioSesion($conexion);

$idMascota = filter_input(INPUT_POST, "id_mascota", FILTER_VALIDATE_INT);
$nombre    = trim($_POST["nombre"] ?? "");
$especie   = trim($_POST["especie"] ?? "");

if ($nombre === "" || $especie === "") {
    header("Location: ../refugio-mascotas.php?error=campos");
    exit;
}

if (!$idMascota && $refugio["estado"] !== "APROBADO") {
    header("Location: ../refugio-mascotas.php?error=noaprobado");
    exit;
}

$sexo    = in_array($_POST["sexo"] ?? "", ["MACHO", "HEMBRA"]) ? $_POST["sexo"] : null;
$tamano  = in_array($_POST["tamano"] ?? "", ["PEQUENO", "MEDIANO", "GRANDE"]) ? $_POST["tamano"] : null;
$energia = in_array($_POST["nivel_energia"] ?? "", ["BAJO", "MEDIO", "ALTO"]) ? $_POST["nivel_energia"] : null;
$edad    = filter_input(INPUT_POST, "edad", FILTER_VALIDATE_INT);

$datos = [
    "nombre" => $nombre,
    "especie" => $especie,
    "raza" => trim($_POST["raza"] ?? ""),
    "edad" => $edad !== false ? $edad : null,
    "sexo" => $sexo,
    "tamano" => $tamano,
    "descripcion" => trim($_POST["descripcion"] ?? ""),
    "foto" => trim($_POST["foto"] ?? ""),
    "vacunado" => isset($_POST["vacunado"]) ? 1 : 0,
    "esterilizado" => isset($_POST["esterilizado"]) ? 1 : 0,
    "compatible_ninos" => isset($_POST["compatible_ninos"]) ? 1 : 0,
    "compatible_animales" => isset($_POST["compatible_animales"]) ? 1 : 0,
    "nivel_energia" => $energia,
];

try {

    if ($idMascota) {
        if (!Mascota::obtenerDeRefugio($conexion, $idMascota, $refugio["id_refugio"])) {
            header("Location: ../refugio-mascotas.php?error=permiso");
            exit;
        }
        Mascota::actualizar($conexion, $idMascota, $refugio["id_refugio"], $datos);
        $accionBitacora = "Editó mascota";
    } else {
        Mascota::crear($conexion, $refugio["id_refugio"], $datos);
        $accionBitacora = "Registró mascota";
    }

    registrarBitacora($conexion, $_SESSION["id_usuario"], $accionBitacora, $nombre);

    header("Location: ../refugio-mascotas.php?guardado=ok");
    exit;

} catch (PDOException $e) {
    header("Location: ../refugio-mascotas.php?error=sistema");
    exit;
}
