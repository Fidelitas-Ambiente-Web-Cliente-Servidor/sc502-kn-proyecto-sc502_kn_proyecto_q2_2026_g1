<?php

/**
 * Modelo: Roles
 * El sistema mantiene únicamente ADMIN_GENERAL, REFUGIO y ADOPTANTE (sin CRUD de roles).
 */
class Rol
{
    public static function listarConConteo(PDO $conexion): array
    {
        return $conexion->query("
            SELECT r.id_rol, r.nombre, r.descripcion, COUNT(u.id_usuario) AS total_usuarios
            FROM roles r
            LEFT JOIN usuarios u ON u.id_rol = r.id_rol
            GROUP BY r.id_rol
            ORDER BY r.id_rol
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarParaExportar(PDO $conexion): array
    {
        return $conexion->query("
            SELECT rol.id_rol, rol.nombre, rol.descripcion, COUNT(u.id_usuario) AS total_usuarios
            FROM roles rol LEFT JOIN usuarios u ON u.id_rol = rol.id_rol
            GROUP BY rol.id_rol
        ")->fetchAll(PDO::FETCH_ASSOC);
    }
}
