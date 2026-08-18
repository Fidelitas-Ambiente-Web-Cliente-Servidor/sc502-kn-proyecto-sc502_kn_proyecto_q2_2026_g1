<?php

/**
 * Modelo: Adoptante
 * Perfil extendido de un usuario con rol ADOPTANTE.
 */
class Adoptante
{
    public static function crear(PDO $conexion, int $idUsuario): void
    {
        $stmt = $conexion->prepare("INSERT INTO adoptantes (id_usuario) VALUES (:id_usuario)");
        $stmt->execute([":id_usuario" => $idUsuario]);
    }

    public static function obtenerConUsuario(PDO $conexion, int $idUsuario): ?array
    {
        $stmt = $conexion->prepare("
            SELECT
                u.id_usuario, u.nombre, u.apellidos, u.correo, u.telefono,
                a.id_adoptante, a.tipo_vivienda, a.tiene_patio, a.tiene_otros_animales,
                a.tiene_ninos, a.experiencia_mascotas, a.tiempo_disponible,
                a.preferencia_especie, a.preferencia_tamano
            FROM usuarios u
            LEFT JOIN adoptantes a ON u.id_usuario = a.id_usuario
            WHERE u.id_usuario = :id_usuario
            LIMIT 1
        ");
        $stmt->execute([":id_usuario" => $idUsuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function obtenerIdPorUsuario(PDO $conexion, int $idUsuario): ?int
    {
        $stmt = $conexion->prepare("SELECT id_adoptante FROM adoptantes WHERE id_usuario = :id_usuario");
        $stmt->execute([":id_usuario" => $idUsuario]);
        $id = $stmt->fetchColumn();
        return $id !== false ? (int) $id : null;
    }

    public static function actualizarPerfil(PDO $conexion, int $idUsuario, array $datos): void
    {
        $stmt = $conexion->prepare("
            UPDATE adoptantes SET
                tipo_vivienda = :tipo_vivienda,
                tiene_patio = :tiene_patio,
                tiene_otros_animales = :tiene_otros_animales,
                tiene_ninos = :tiene_ninos,
                experiencia_mascotas = :experiencia_mascotas,
                tiempo_disponible = :tiempo_disponible,
                preferencia_especie = :preferencia_especie,
                preferencia_tamano = :preferencia_tamano
            WHERE id_usuario = :id_usuario
        ");
        $stmt->execute([
            ":tipo_vivienda" => $datos["tipo_vivienda"] ?: null,
            ":tiene_patio" => $datos["tiene_patio"],
            ":tiene_otros_animales" => $datos["tiene_otros_animales"],
            ":tiene_ninos" => $datos["tiene_ninos"],
            ":experiencia_mascotas" => $datos["experiencia_mascotas"] ?: null,
            ":tiempo_disponible" => $datos["tiempo_disponible"] ?: null,
            ":preferencia_especie" => $datos["preferencia_especie"] ?: null,
            ":preferencia_tamano" => $datos["preferencia_tamano"] ?: null,
            ":id_usuario" => $idUsuario,
        ]);
    }
}
