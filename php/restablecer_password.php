<?php

require_once "config.php";
require_once "helpers.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.html");
    exit;
}

$token       = $_POST["token"] ?? "";
$contrasena  = $_POST["contrasena"] ?? "";
$confirmar   = $_POST["confirmar_contrasena"] ?? "";

if ($token === "") {
    header("Location: ../login.html?error=sistema");
    exit;
}

if (strlen($contrasena) < 6 || $contrasena !== $confirmar) {
    header("Location: ../restablecer.php?token=" . urlencode($token) . "&error=contrasena");
    exit;
}

$idUsuario = RecuperacionPassword::validarToken($conexion, $token);

if (!$idUsuario) {
    header("Location: ../restablecer.php?token=" . urlencode($token) . "&error=token");
    exit;
}

$hash = password_hash($contrasena, PASSWORD_DEFAULT);

$conexion->prepare("UPDATE usuarios SET contrasena = :contrasena WHERE id_usuario = :id")
    ->execute([":contrasena" => $hash, ":id" => $idUsuario]);

RecuperacionPassword::marcarUsado($conexion, $token);

registrarBitacora($conexion, $idUsuario, "Restableció su contraseña");

header("Location: ../login.html?restablecido=ok");
exit;
