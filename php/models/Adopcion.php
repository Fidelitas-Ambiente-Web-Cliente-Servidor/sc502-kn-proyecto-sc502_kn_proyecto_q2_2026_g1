<?php

/**
 * Modelo: Adopción
 */
class Adopcion
{
    public static function crear(PDO $conexion, int $idSolicitud): int
    {
        $stmt = $conexion->prepare("
            INSERT INTO adopciones (id_solicitud, fecha_adopcion, estado)
            VALUES (:id_solicitud, CURDATE(), 'ACTIVA')
        ");
        $stmt->execute([":id_solicitud" => $idSolicitud]);
        return (int) $conexion->lastInsertId();
    }

    public static function listarPorAdoptante(PDO $conexion, int $idAdoptante): array
    {
        $stmt = $conexion->prepare("
            SELECT ad.id_adopcion, ad.fecha_adopcion, ad.estado AS estado_adopcion,
                   m.id_mascota, m.nombre AS mascota
            FROM adopciones ad
            INNER JOIN solicitudes sol ON ad.id_solicitud = sol.id_solicitud
            INNER JOIN mascotas m ON sol.id_mascota = m.id_mascota
            WHERE sol.id_adoptante = :id_adoptante
            ORDER BY ad.fecha_adopcion DESC
        ");
        $stmt->execute([":id_adoptante" => $idAdoptante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerActivaDeAdoptante(PDO $conexion, int $idAdopcion, int $idAdoptante): ?array
    {
        $stmt = $conexion->prepare("
            SELECT ad.id_adopcion, sol.id_mascota, m.nombre, r.id_usuario AS id_usuario_refugio
            FROM adopciones ad
            INNER JOIN solicitudes sol ON ad.id_solicitud = sol.id_solicitud
            INNER JOIN mascotas m ON sol.id_mascota = m.id_mascota
            INNER JOIN refugios r ON m.id_refugio = r.id_refugio
            WHERE ad.id_adopcion = :id_adopcion AND sol.id_adoptante = :id_adoptante AND ad.estado = 'ACTIVA'
            LIMIT 1
        ");
        $stmt->execute([":id_adopcion" => $idAdopcion, ":id_adoptante" => $idAdoptante]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function marcarDevuelta(PDO $conexion, int $idAdopcion): void
    {
        $conexion->prepare("UPDATE adopciones SET estado = 'DEVUELTA' WHERE id_adopcion = :id")
            ->execute([":id" => $idAdopcion]);
    }

    public static function contarDelMesActual(PDO $conexion): int
    {
        return (int) $conexion->query("
            SELECT COUNT(*) FROM adopciones
            WHERE MONTH(fecha_adopcion) = MONTH(CURDATE()) AND YEAR(fecha_adopcion) = YEAR(CURDATE())
        ")->fetchColumn();
    }

    public static function contarTotal(PDO $conexion): int
    {
        return (int) $conexion->query("SELECT COUNT(*) FROM adopciones")->fetchColumn();
    }
}
