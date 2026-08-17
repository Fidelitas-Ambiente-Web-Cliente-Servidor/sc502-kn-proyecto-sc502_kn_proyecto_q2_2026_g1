<?php

$host = "db";
$puerto = "3306";
$base_datos = "pawsmatch";
$usuario = "pawsmatch_user";
$contrasena = "pawsmatch123";

try {
    $conexion = new PDO(
        "mysql:host=$host;port=$puerto;dbname=$base_datos;charset=utf8mb4",
        $usuario,
        $contrasena
    );

    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
