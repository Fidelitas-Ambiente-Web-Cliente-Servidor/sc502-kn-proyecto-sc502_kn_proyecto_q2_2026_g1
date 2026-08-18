<?php

require_once "config.php";
require_once "helpers.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../registro-refugio.html");
    exit;
}

$nombreRefugio  = trim($_POST["nombre_refugio"] ?? "");
$correo         = trim($_POST["correo"] ?? "");
$telefono       = trim($_POST["telefono"] ?? "");
$direccion      = trim($_POST["direccion"] ?? "");
$cedulaJuridica = trim($_POST["cedula_juridica"] ?? "");
$descripcion    = trim($_POST["descripcion"] ?? "");
$contrasena     = $_POST["contrasena"] ?? "";
$confirmar      = $_POST["confirmar_contrasena"] ?? "";

if ($nombreRefugio === "" || $correo === "" || $direccion === "" || $contrasena === "") {
    header("Location: ../registro-refugio.html?error=campos");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../registro-refugio.html?error=correo_invalido");
    exit;
}

if ($contrasena !== $confirmar) {
    header("Location: ../registro-refugio.html?error=clave_no_coincide");
    exit;
}

if (strlen($contrasena) < 6) {
    header("Location: ../registro-refugio.html?error=clave_corta");
    exit;
}

if (Usuario::correoExiste($conexion, $correo)) {
    header("Location: ../registro-refugio.html?error=correo_existe");
    exit;
}

try {

    $idRefugio = Refugio::crearConUsuario($conexion, [
        "nombre_refugio"  => $nombreRefugio,
        "correo"          => $correo,
        "contrasena"      => $contrasena,
        "telefono"        => $telefono,
        "cedula_juridica" => $cedulaJuridica,
        "direccion"       => $direccion,
        "descripcion"     => $descripcion,
    ], "PENDIENTE");

    $refugio = Refugio::obtenerPorId($conexion, $idRefugio);

    registrarBitacora($conexion, $refugio["id_usuario"], "Registro público de refugio", "$nombreRefugio quedó pendiente de aprobación.");

    crearNotificacionParaRol(
        $conexion,
        "ADMIN_GENERAL",
        "Nuevo refugio pendiente de aprobación",
        "$nombreRefugio ($correo) se registró y espera revisión.",
        "SISTEMA"
    );

    header("Location: ../login.html?refugio_registrado=ok");
    exit;

} catch (PDOException $e) {
    header("Location: ../registro-refugio.html?error=sistema");
    exit;
}
