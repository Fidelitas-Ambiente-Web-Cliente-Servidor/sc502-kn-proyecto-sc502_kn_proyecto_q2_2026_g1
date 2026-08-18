<?php

/**
 * Modelo: Reportes/denuncias de bienestar animal (tabla `reportes`)
 */
class Reporte
{
    public static function listarConDetalle(PDO $conexion): array
    {
        return $conexion->query("
            SELECT rep.id_reporte, rep.tipo, rep.descripcion, rep.estado, rep.fecha_reporte,
                   u.nombre AS usuario_nombre, u.apellidos AS usuario_apellidos,
                   m.nombre AS mascota
            FROM reportes rep
            INNER JOIN usuarios u ON rep.id_usuario = u.id_usuario
            LEFT JOIN mascotas m ON rep.id_mascota = m.id_mascota
            ORDER BY rep.fecha_reporte DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function crear(PDO $conexion, int $idUsuario, ?int $idMascota, string $tipo, string $descripcion): int
    {
        $stmt = $conexion->prepare("
            INSERT INTO reportes (id_usuario, id_mascota, tipo, descripcion, estado)
            VALUES (:id_usuario, :id_mascota, :tipo, :descripcion, 'PENDIENTE')
        ");
        $stmt->execute([
            ":id_usuario"  => $idUsuario,
            ":id_mascota"  => $idMascota,
            ":tipo"        => $tipo,
            ":descripcion" => $descripcion,
        ]);
        return (int) $conexion->lastInsertId();
    }

    public static function cambiarEstado(PDO $conexion, int $idReporte, string $estado): void
    {
        $stmt = $conexion->prepare("UPDATE reportes SET estado = :estado WHERE id_reporte = :id");
        $stmt->execute([":estado" => $estado, ":id" => $idReporte]);
    }

    public static function contarPendientes(PDO $conexion): int
    {
        return (int) $conexion->query("SELECT COUNT(*) FROM reportes WHERE estado = 'PENDIENTE'")->fetchColumn();
    }
}
