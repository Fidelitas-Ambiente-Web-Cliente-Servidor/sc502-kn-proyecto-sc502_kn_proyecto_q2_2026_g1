<?php

session_start();

require_once "config.php";
require_once "guard.php";
require_once "helpers.php";

requerirRol("ADMIN_GENERAL", "../login.html");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../admin-refugios.php");
    exit;
}

$nombreRefugio  = trim($_POST["nombre_refugio"] ?? "");
$correo         = trim($_POST["correo"] ?? "");
$contrasena     = $_POST["contrasena"] ?? "";

if ($nombreRefugio === "" || $correo === "" || strlen($contrasena) < 6) {
    header("Location: ../admin-refugios.php?error=campos");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../admin-refugios.php?error=correo");
    exit;
}

if (Usuario::correoExiste($conexion, $correo)) {
    header("Location: ../admin-refugios.php?error=correo");
    exit;
}

try {

    Refugio::crearConUsuario($conexion, [
        "nombre_refugio"  => $nombreRefugio,
        "correo"          => $correo,
        "contrasena"      => $contrasena,
        "telefono"        => trim($_POST["telefono"] ?? ""),
        "cedula_juridica" => trim($_POST["cedula_juridica"] ?? ""),
        "direccion"       => trim($_POST["direccion"] ?? ""),
        "descripcion"     => trim($_POST["descripcion"] ?? ""),
    ]);

    registrarBitacora($conexion, $_SESSION["id_usuario"], "Registró refugio", $nombreRefugio);

    header("Location: ../admin-refugios.php?guardado=ok");
    exit;

} catch (Throwable $e) {
    header("Location: ../admin-refugios.php?error=sistema");
    exit;
}
