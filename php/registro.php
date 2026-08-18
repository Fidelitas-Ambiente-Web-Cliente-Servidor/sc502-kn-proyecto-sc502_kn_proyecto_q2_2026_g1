<?php

require_once "config.php";
require_once "helpers.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../registro.html");
    exit;
}

$nombre = trim($_POST["nombre"] ?? "");
$apellidos = trim($_POST["apellidos"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$contrasena = $_POST["contrasena"] ?? "";
$confirmar = $_POST["confirmar_contrasena"] ?? "";

// Validar campos obligatorios
if ($nombre === "" || $apellidos === "" || $correo === "" || $contrasena === "") {
    header("Location: ../registro.html?error=campos");
    exit;
}

// Validar correo
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../registro.html?error=correo_invalido");
    exit;
}

// Validar contraseñas
if ($contrasena !== $confirmar) {
    header("Location: ../registro.html?error=clave_no_coincide");
    exit;
}

// Longitud mínima
if (strlen($contrasena) < 6) {
    header("Location: ../registro.html?error=clave_corta");
    exit;
}

try {

    if (Usuario::correoExiste($conexion, $correo)) {
        header("Location: ../registro.html?error=correo_existe");
        exit;
    }

    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Rol 3 = ADOPTANTE
    $idUsuario = Usuario::crear($conexion, 3, $nombre, $apellidos, $correo, $hash, $telefono);

    Adoptante::crear($conexion, $idUsuario);

    registrarBitacora($conexion, $idUsuario, "Registro de usuario", "$nombre $apellidos se registró como adoptante.");

    crearNotificacionParaRol(
        $conexion,
        "ADMIN_GENERAL",
        "Nuevo adoptante registrado",
        "$nombre $apellidos ($correo) se registró como adoptante.",
        "SISTEMA"
    );

    header("Location: ../login.html?registrado=ok");
    exit;

} catch (PDOException $e) {

    header("Location: ../registro.html?error=sistema");
    exit;

}
