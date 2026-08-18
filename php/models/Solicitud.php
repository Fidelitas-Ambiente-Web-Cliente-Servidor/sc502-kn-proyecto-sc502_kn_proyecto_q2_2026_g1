<?php

/**
 * Modelo: Solicitud (de adopción)
 */
class Solicitud
{
    /** Conteo por estado de las solicitudes recibidas por un refugio (para su gráfica). */
    public static function contarPorEstadoDeRefugio(PDO $conexion, int $idRefugio): array
    {
        $stmt = $conexion->prepare("
            SELECT s.estado, COUNT(*) AS total
            FROM solicitudes s
            INNER JOIN mascotas m ON s.id_mascota = m.id_mascota
            WHERE m.id_refugio = :id_refugio
            GROUP BY s.estado
        ");
        $stmt->execute([":id_refugio" => $idRefugio]);
        $mapa = ["PENDIENTE" => 0, "EN_REVISION" => 0, "APROBADA" => 0, "RECHAZADA" => 0, "CANCELADA" => 0];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $f) {
            $mapa[$f["estado"]] = (int) $f["total"];
        }
        return $mapa;
    }

    public static function existeActiva(PDO $conexion, int $idAdoptante, int $idMascota): bool
    {
        $stmt = $conexion->prepare("
            SELECT id_solicitud FROM solicitudes
            WHERE id_adoptante = :id_adoptante AND id_mascota = :id_mascota
              AND estado IN ('PENDIENTE', 'EN_REVISION', 'APROBADA')
            LIMIT 1
        ");
        $stmt->execute([":id_adoptante" => $idAdoptante, ":id_mascota" => $idMascota]);
        return (bool) $stmt->fetchColumn();
    }

    public static function crear(PDO $conexion, int $idAdoptante, int $idMascota, string $motivo): int
    {
        $stmt = $conexion->prepare("
            INSERT INTO solicitudes (id_adoptante, id_mascota, motivo, estado)
            VALUES (:id_adoptante, :id_mascota, :motivo, 'PENDIENTE')
        ");
        $stmt->execute([":id_adoptante" => $idAdoptante, ":id_mascota" => $idMascota, ":motivo" => $motivo]);
        return (int) $conexion->lastInsertId();
    }

    public static function obtenerEstadoActivoDeAdoptante(PDO $conexion, int $idAdoptante, int $idMascota): ?string
    {
        $stmt = $conexion->prepare("
            SELECT estado FROM solicitudes
            WHERE id_adoptante = :id_adoptante AND id_mascota = :id_mascota
              AND estado IN ('PENDIENTE', 'EN_REVISION', 'APROBADA')
            ORDER BY fecha_solicitud DESC LIMIT 1
        ");
        $stmt->execute([":id_adoptante" => $idAdoptante, ":id_mascota" => $idMascota]);
        $estado = $stmt->fetchColumn();
        return $estado ?: null;
    }

    public static function listarPorAdoptante(PDO $conexion, int $idAdoptante): array
    {
        $stmt = $conexion->prepare("
            SELECT s.id_solicitud, s.estado, s.fecha_solicitud, s.fecha_respuesta, s.observaciones_refugio,
                   m.id_mascota, m.nombre AS mascota, m.especie, m.foto, r.nombre_refugio
            FROM solicitudes s
            INNER JOIN mascotas m ON s.id_mascota = m.id_mascota
            INNER JOIN refugios r ON m.id_refugio = r.id_refugio
            WHERE s.id_adoptante = :id_adoptante
            ORDER BY s.fecha_solicitud DESC
        ");
        $stmt->execute([":id_adoptante" => $idAdoptante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarPorRefugio(PDO $conexion, int $idRefugio): array
    {
        $stmt = $conexion->prepare("
            SELECT s.id_solicitud, s.estado, s.fecha_solicitud, s.observaciones_refugio,
                   m.id_mascota, m.nombre AS mascota, m.especie, m.foto,
                   u.nombre AS adoptante_nombre, u.apellidos AS adoptante_apellidos
            FROM solicitudes s
            INNER JOIN mascotas m ON s.id_mascota = m.id_mascota
            INNER JOIN adoptantes a ON s.id_adoptante = a.id_adoptante
            INNER JOIN usuarios u ON a.id_usuario = u.id_usuario
            WHERE m.id_refugio = :id_refugio
            ORDER BY s.fecha_solicitud DESC
        ");
        $stmt->execute([":id_refugio" => $idRefugio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarRecientesGlobal(PDO $conexion, int $limite = 4): array
    {
        $stmt = $conexion->prepare("
            SELECT s.estado, s.fecha_solicitud, m.nombre AS mascota,
                   u.nombre AS adoptante_nombre, u.apellidos AS adoptante_apellidos
            FROM solicitudes s
            INNER JOIN mascotas m ON s.id_mascota = m.id_mascota
            INNER JOIN adoptantes a ON s.id_adoptante = a.id_adoptante
            INNER JOIN usuarios u ON a.id_usuario = u.id_usuario
            ORDER BY s.fecha_solicitud DESC LIMIT :limite
        ");
        $stmt->bindValue(":limite", $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function cancelar(PDO $conexion, int $idSolicitud, int $idAdoptante): bool
    {
        $stmt = $conexion->prepare("
            UPDATE solicitudes SET estado = 'CANCELADA', fecha_respuesta = NOW()
            WHERE id_solicitud = :id_solicitud AND id_adoptante = :id_adoptante
              AND estado IN ('PENDIENTE', 'EN_REVISION')
        ");
        $stmt->execute([":id_solicitud" => $idSolicitud, ":id_adoptante" => $idAdoptante]);
        return $stmt->rowCount() > 0;
    }

    /** Bloquea la fila (FOR UPDATE) y trae todo lo necesario para resolverla. */
    public static function obtenerParaResolucion(PDO $conexion, int $idSolicitud): ?array
    {
        $stmt = $conexion->prepare("
            SELECT s.*, m.nombre AS mascota, m.id_refugio, u.id_usuario AS id_usuario_adoptante
            FROM solicitudes s
            INNER JOIN mascotas m ON s.id_mascota = m.id_mascota
            INNER JOIN adoptantes a ON s.id_adoptante = a.id_adoptante
            INNER JOIN usuarios u ON a.id_usuario = u.id_usuario
            WHERE s.id_solicitud = :id_solicitud
            FOR UPDATE
        ");
        $stmt->execute([":id_solicitud" => $idSolicitud]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function aprobar(PDO $conexion, int $idSolicitud, ?string $observaciones): void
    {
        $conexion->prepare("
            UPDATE solicitudes SET estado = 'APROBADA', fecha_respuesta = NOW(), observaciones_refugio = :obs
            WHERE id_solicitud = :id
        ")->execute([":obs" => $observaciones, ":id" => $idSolicitud]);
    }

    public static function rechazar(PDO $conexion, int $idSolicitud, ?string $observaciones): void
    {
        $conexion->prepare("
            UPDATE solicitudes SET estado = 'RECHAZADA', fecha_respuesta = NOW(), observaciones_refugio = :obs
            WHERE id_solicitud = :id
        ")->execute([":obs" => $observaciones, ":id" => $idSolicitud]);
    }

    public static function rechazarOtrasActivas(PDO $conexion, int $idMascota, int $idSolicitudExcluir): void
    {
        $stmt = $conexion->prepare("
            UPDATE solicitudes
            SET estado = 'RECHAZADA', fecha_respuesta = NOW(),
                observaciones_refugio = 'La mascota ya fue adoptada por otra persona.'
            WHERE id_mascota = :id_mascota AND id_solicitud <> :id_solicitud
              AND estado IN ('PENDIENTE', 'EN_REVISION')
        ");
        $stmt->execute([":id_mascota" => $idMascota, ":id_solicitud" => $idSolicitudExcluir]);
    }

    public static function contarPendientesPorRefugio(PDO $conexion, int $idRefugio): int
    {
        $stmt = $conexion->prepare("
            SELECT COUNT(*) FROM solicitudes s
            INNER JOIN mascotas m ON s.id_mascota = m.id_mascota
            WHERE m.id_refugio = :id_refugio AND s.estado IN ('PENDIENTE', 'EN_REVISION')
        ");
        $stmt->execute([":id_refugio" => $idRefugio]);
        return (int) $stmt->fetchColumn();
    }

    public static function contarPendientesGlobal(PDO $conexion): int
    {
        return (int) $conexion->query("SELECT COUNT(*) FROM solicitudes WHERE estado IN ('PENDIENTE','EN_REVISION')")->fetchColumn();
    }
}
