<?php

/**
 * Modelo: Notificaciones
 */
class Notificacion
{
    public static function crear(PDO $conexion, int $idUsuario, string $titulo, string $mensaje, string $tipo = "SISTEMA"): void
    {
        $stmt = $conexion->prepare("
            INSERT INTO notificaciones (id_usuario, titulo, mensaje, tipo)
            VALUES (:id_usuario, :titulo, :mensaje, :tipo)
        ");
        $stmt->execute([":id_usuario" => $idUsuario, ":titulo" => $titulo, ":mensaje" => $mensaje, ":tipo" => $tipo]);
    }

    public static function crearParaRol(PDO $conexion, string $rol, string $titulo, string $mensaje, string $tipo = "SISTEMA"): void
    {
        $stmt = $conexion->prepare("
            SELECT u.id_usuario FROM usuarios u
            INNER JOIN roles r ON u.id_rol = r.id_rol
            WHERE r.nombre = :rol AND u.estado = 'ACTIVO'
        ");
        $stmt->execute([":rol" => $rol]);

        foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $idUsuario) {
            self::crear($conexion, (int) $idUsuario, $titulo, $mensaje, $tipo);
        }
    }

    public static function listarPorUsuario(PDO $conexion, int $idUsuario, int $limite = null): array
    {
        $sql = "SELECT * FROM notificaciones WHERE id_usuario = :id_usuario ORDER BY fecha_creacion DESC";
        if ($limite) {
            $sql .= " LIMIT " . (int) $limite;
        }
        $stmt = $conexion->prepare($sql);
        $stmt->execute([":id_usuario" => $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function marcarLeida(PDO $conexion, int $idNotificacion, int $idUsuario): void
    {
        $stmt = $conexion->prepare("UPDATE notificaciones SET leida = 1 WHERE id_notificacion = :id AND id_usuario = :id_usuario");
        $stmt->execute([":id" => $idNotificacion, ":id_usuario" => $idUsuario]);
    }

    public static function contarNoLeidas(PDO $conexion, int $idUsuario): int
    {
        $stmt = $conexion->prepare("SELECT COUNT(*) FROM notificaciones WHERE id_usuario = :id_usuario AND leida = 0");
        $stmt->execute([":id_usuario" => $idUsuario]);
        return (int) $stmt->fetchColumn();
    }
}
