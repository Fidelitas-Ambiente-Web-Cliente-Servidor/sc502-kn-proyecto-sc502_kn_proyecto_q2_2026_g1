<?php

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Método no permitido.");
}

$nombre = trim($_POST["nombre"] ?? "");
$apellidos = trim($_POST["apellidos"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$contrasena = $_POST["contrasena"] ?? "";
$confirmar = $_POST["confirmar_contrasena"] ?? "";


// Validar campos obligatorios
if (
    $nombre === "" ||
    $apellidos === "" ||
    $correo === "" ||
    $contrasena === ""
) {
    die("Todos los campos obligatorios deben completarse.");
}


// Validar correo
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("El correo electrónico no es válido.");
}


// Validar contraseñas
if ($contrasena !== $confirmar) {
    die("Las contraseñas no coinciden.");
}


// Longitud mínima
if (strlen($contrasena) < 6) {
    die("La contraseña debe tener al menos 6 caracteres.");
}


try {

    // Revisar si el correo ya existe
    $sqlCorreo = "
        SELECT id_usuario
        FROM usuarios
        WHERE correo = :correo
    ";

    $stmtCorreo = $conexion->prepare($sqlCorreo);

    $stmtCorreo->execute([
        ":correo" => $correo
    ]);


    if ($stmtCorreo->fetch()) {
        die("Ya existe una cuenta registrada con ese correo.");
    }


    // Crear hash de la contraseña
    $hash = password_hash(
        $contrasena,
        PASSWORD_DEFAULT
    );


    // Rol 3 = ADOPTANTE
    $idRolAdoptante = 3;


    // Insertar usuario
    $sqlUsuario = "
        INSERT INTO usuarios
        (
            id_rol,
            nombre,
            apellidos,
            correo,
            contrasena,
            telefono,
            estado
        )
        VALUES
        (
            :id_rol,
            :nombre,
            :apellidos,
            :correo,
            :contrasena,
            :telefono,
            'ACTIVO'
        )
    ";


    $stmtUsuario = $conexion->prepare($sqlUsuario);


    $stmtUsuario->execute([
        ":id_rol" => $idRolAdoptante,
        ":nombre" => $nombre,
        ":apellidos" => $apellidos,
        ":correo" => $correo,
        ":contrasena" => $hash,
        ":telefono" => $telefono
    ]);


    // Obtener ID del usuario recién creado
    $idUsuario = $conexion->lastInsertId();


    // Crear perfil de adoptante
    $sqlAdoptante = "
        INSERT INTO adoptantes
        (
            id_usuario
        )
        VALUES
        (
            :id_usuario
        )
    ";


    $stmtAdoptante = $conexion->prepare($sqlAdoptante);


    $stmtAdoptante->execute([
        ":id_usuario" => $idUsuario
    ]);


    echo "
        <h2>Registro realizado correctamente.</h2>
        <p>Tu cuenta fue creada exitosamente.</p>
        <a href='../login.html'>Ir a iniciar sesión</a>
    ";


} catch (PDOException $e) {

    echo "Error al registrar el usuario: " . $e->getMessage();

}