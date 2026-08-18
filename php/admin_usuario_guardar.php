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

$nombre     = trim($_POST["nombre"] ?? "");
$apellidos  = trim($_POST["apellidos"] ?? "");
$correo     = trim($_POST["correo"] ?? "");
$rol        = $_POST["rol"] ?? "";
$contrasena = $_POST["contrasena"] ?? "";

// Solo ADMIN_GENERAL y ADOPTANTE se crean desde aquí.
// Las cuentas REFUGIO se crean junto con su perfil en el módulo Refugios.
$rolesPermitidos = ["ADMIN_GENERAL" => 1, "ADOPTANTE" => 3];

if ($nombre === "" || $apellidos === "" || $correo === "" || !isset($rolesPermitidos[$rol]) || strlen($contrasena) < 6) {
    header("Location: ../admin-usuarios.php?error=campos");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../admin-usuarios.php?error=correo");
    exit;
}

try {

    if (Usuario::correoExiste($conexion, $correo)) {
        header("Location: ../admin-usuarios.php?error=correo");
        exit;
    }

    $conexion->beginTransaction();

    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $idUsuario = Usuario::crear($conexion, $rolesPermitidos[$rol], $nombre, $apellidos, $correo, $hash);

    if ($rol === "ADOPTANTE") {
        Adoptante::crear($conexion, $idUsuario);
    }

    $conexion->commit();

    registrarBitacora($conexion, $_SESSION["id_usuario"], "Creó usuario", "$nombre $apellidos ($rol)");

    header("Location: ../admin-usuarios.php?guardado=ok");
    exit;

} catch (PDOException $e) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }
    header("Location: ../admin-usuarios.php?error=sistema");
    exit;
}
