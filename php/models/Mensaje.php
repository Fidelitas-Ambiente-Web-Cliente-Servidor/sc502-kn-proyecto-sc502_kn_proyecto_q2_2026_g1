<?php

/**
 * Modelo: Mensajería entre adoptante y refugio, ligada a una solicitud de adopción.
 */
class Mensaje
{
    /**
     * Datos de ambos participantes de la conversación de una solicitud
     * (el usuario del adoptante y el usuario del refugio), más contexto de la mascota.
     */
    public static function obtenerParticipantes(PDO $conexion, int $idSolicitud): ?array
    {
        $stmt = $conexion->prepare("
            SELECT
                s.id_solicitud, m.nombre AS mascota,
                ua.id_usuario AS id_usuario_adoptante, ua.nombre AS adoptante_nombre, ua.apellidos AS adoptante_apellidos,
                ur.id_usuario AS id_usuario_refugio, r.nombre_refugio
            FROM solicitudes s
            INNER JOIN mascotas m ON s.id_mascota = m.id_mascota
            INNER JOIN refugios r ON m.id_refugio = r.id_refugio
            INNER JOIN usuarios ur ON r.id_usuario = ur.id_usuario
            INNER JOIN adoptantes a ON s.id_adoptante = a.id_adoptante
            INNER JOIN usuarios ua ON a.id_usuario = ua.id_usuario
            WHERE s.id_solicitud = :id
            LIMIT 1
        ");
        $stmt->execute([":id" => $idSolicitud]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function enviar(PDO $conexion, int $idRemitente, int $idDestinatario, int $idSolicitud, string $texto): int
    {
        $stmt = $conexion->prepare("
            INSERT INTO mensajes (id_remitente, id_destinatario, id_solicitud, mensaje)
            VALUES (:id_remitente, :id_destinatario, :id_solicitud, :mensaje)
        ");
        $stmt->execute([
            ":id_remitente"    => $idRemitente,
            ":id_destinatario" => $idDestinatario,
            ":id_solicitud"    => $idSolicitud,
            ":mensaje"         => $texto,
        ]);
        return (int) $conexion->lastInsertId();
    }

    public static function listarPorSolicitud(PDO $conexion, int $idSolicitud): array
    {
        $stmt = $conexion->prepare("
            SELECT msg.*, u.nombre AS remitente_nombre
            FROM mensajes msg
            INNER JOIN usuarios u ON msg.id_remitente = u.id_usuario
            WHERE msg.id_solicitud = :id_solicitud
            ORDER BY msg.fecha_envio ASC
        ");
        $stmt->execute([":id_solicitud" => $idSolicitud]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function marcarLeidos(PDO $conexion, int $idSolicitud, int $idUsuarioReceptor): void
    {
        $stmt = $conexion->prepare("
            UPDATE mensajes SET leido = 1
            WHERE id_solicitud = :id_solicitud AND id_destinatario = :id_usuario AND leido = 0
        ");
        $stmt->execute([":id_solicitud" => $idSolicitud, ":id_usuario" => $idUsuarioReceptor]);
    }

    /** Retorna un mapa [id_solicitud => cantidad de mensajes sin leer] para ese usuario. */
    public static function contarNoLeidosPorSolicitudes(PDO $conexion, array $idsSolicitudes, int $idUsuario): array
    {
        if (empty($idsSolicitudes)) {
            return [];
        }
        $placeholders = implode(",", array_fill(0, count($idsSolicitudes), "?"));
        $stmt = $conexion->prepare("
            SELECT id_solicitud, COUNT(*) AS total
            FROM mensajes
            WHERE id_solicitud IN ($placeholders) AND id_destinatario = ? AND leido = 0
            GROUP BY id_solicitud
        ");
        $stmt->execute([...$idsSolicitudes, $idUsuario]);

        $mapa = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $mapa[$fila["id_solicitud"]] = (int) $fila["total"];
        }
        return $mapa;
    }
}
