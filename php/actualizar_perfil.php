<?php

session_start();

require_once "config.php";
require_once "guard.php";
require_once "helpers.php";

requerirRol("ADOPTANTE", "../login.html");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../perfil.php");
    exit;
}

$idUsuario = $_SESSION["id_usuario"];

$nombre = trim($_POST["nombre"] ?? "");
$apellidos = trim($_POST["apellidos"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");

$datosAdoptante = [
    "tipo_vivienda" => $_POST["tipo_vivienda"] ?? null,
    "tiempo_disponible" => $_POST["tiempo_disponible"] ?? null,
    "experiencia_mascotas" => $_POST["experiencia_mascotas"] ?? null,
    "preferencia_especie" => $_POST["preferencia_especie"] ?? null,
    "preferencia_tamano" => $_POST["preferencia_tamano"] ?? null,
    "tiene_patio" => isset($_POST["tiene_patio"]) ? 1 : 0,
    "tiene_otros_animales" => isset($_POST["tiene_otros_animales"]) ? 1 : 0,
    "tiene_ninos" => isset($_POST["tiene_ninos"]) ? 1 : 0,
];

if ($nombre === "" || $apellidos === "" || $correo === "") {
    header("Location: ../perfil.php?error=campos");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../perfil.php?error=correo");
    exit;
}

try {

    $conexion->beginTransaction();

    if (Usuario::correoExiste($conexion, $correo, $idUsuario)) {
        $conexion->rollBack();
        header("Location: ../perfil.php?error=correo");
        exit;
    }

    Usuario::actualizarDatosBasicos($conexion, $idUsuario, $nombre, $apellidos, $correo, $telefono);
    Adoptante::actualizarPerfil($conexion, $idUsuario, $datosAdoptante);

    $_SESSION["nombre"] = $nombre;
    $_SESSION["apellidos"] = $apellidos;
    $_SESSION["correo"] = $correo;

    $conexion->commit();

    header("Location: ../perfil.php?actualizado=ok");
    exit;

} catch (PDOException $e) {

    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }

    header("Location: ../perfil.php?error=sistema");
    exit;
}
