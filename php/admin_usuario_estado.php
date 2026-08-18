<?php

session_start();

require_once "config.php";
require_once "guard.php";
require_once "helpers.php";

requerirRol("ADMIN_GENERAL", "../login.html");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../admin-usuarios.php");
    exit;
}

$idUsuario = filter_input(INPUT_POST, "id_usuario", FILTER_VALIDATE_INT);

if (!$idUsuario) {
    header("Location: ../admin-usuarios.php");
    exit;
}

if ($idUsuario === (int) $_SESSION["id_usuario"]) {
    header("Location: ../admin-usuarios.php?error=autobloqueo");
    exit;
}

$usuario = Usuario::obtenerPorId($conexion, $idUsuario);

if (!$usuario) {
    header("Location: ../admin-usuarios.php");
    exit;
}

$nuevoEstado = $usuario["estado"] === "ACTIVO" ? "INACTIVO" : "ACTIVO";
Usuario::cambiarEstado($conexion, $idUsuario, $nuevoEstado);

registrarBitacora($conexion, $_SESSION["id_usuario"], "Cambió estado de usuario", "{$usuario['nombre']} -> $nuevoEstado");

header("Location: ../admin-usuarios.php?estado=ok");
exit;
