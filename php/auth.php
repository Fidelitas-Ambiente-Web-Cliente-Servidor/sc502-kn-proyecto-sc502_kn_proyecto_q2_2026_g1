<?php

session_start();

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.html");
    exit;
}

$correo = trim($_POST["correo"] ?? "");
$contrasena = $_POST["contrasena"] ?? "";

if ($correo === "" || $contrasena === "") {
    header("Location: ../login.html?error=campos");
    exit;
}

try {

    $sql = "
        SELECT
            u.id_usuario,
            u.nombre,
            u.apellidos,
            u.correo,
            u.contrasena,
            u.estado,
            r.nombre AS rol
        FROM usuarios u
        INNER JOIN roles r
            ON u.id_rol = r.id_rol
        WHERE u.correo = :correo
        LIMIT 1
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ":correo" => $correo
    ]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);


    // Correo no encontrado
    if (!$usuario) {
        header("Location: ../login.html?error=correo");
        exit;
    }


    // Cuenta inactiva
    if ($usuario["estado"] !== "ACTIVO") {
        header("Location: ../login.html?error=inactivo");
        exit;
    }


    // Contraseña incorrecta
    if (!password_verify($contrasena, $usuario["contrasena"])) {
        header("Location: ../login.html?error=contrasena");
        exit;
    }


    // Guardar la información en la sesión PHP
    $_SESSION["id_usuario"] = $usuario["id_usuario"];
    $_SESSION["nombre"] = $usuario["nombre"];
    $_SESSION["apellidos"] = $usuario["apellidos"];
    $_SESSION["correo"] = $usuario["correo"];
    $_SESSION["rol"] = $usuario["rol"];

    // Guardamos mensaje de bienvenida
    $_SESSION["mensaje_login"] =
        "¡Bienvenido/a, " . $usuario["nombre"] . "!";


    // Redirección según rol
    switch ($usuario["rol"]) {

        case "ADMIN_GENERAL":
            header("Location: ../admin-dashboard.html?login=ok");
            break;

        case "REFUGIO":
            header("Location: ../refugio-dashboard.html?login=ok");
            break;

        case "ADOPTANTE":
            header("Location: ../perfil.html?login=ok");
            break;

        default:
            header("Location: ../index.html");
            break;
    }

    exit;


} catch (PDOException $e) {

    header("Location: ../login.html?error=sistema");
    exit;
}