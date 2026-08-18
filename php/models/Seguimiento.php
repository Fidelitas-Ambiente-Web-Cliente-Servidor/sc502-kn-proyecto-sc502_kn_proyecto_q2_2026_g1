<?php

/**
 * Modelo: Seguimiento post-adopción
 */
class Seguimiento
{
    public static function crearProgramados(PDO $conexion, int $idAdopcion): void
    {
        $stmt = $conexion->prepare("
            INSERT INTO seguimientos (id_adopcion, fecha_programada, tipo, estado)
            VALUES (:id_adopcion, :fecha, :tipo, 'PENDIENTE')
        ");
        $stmt->execute([":id_adopcion" => $idAdopcion, ":fecha" => date("Y-m-d", strtotime("+7 days")), ":tipo" => "SEMANA"]);
        $stmt->execute([":id_adopcion" => $idAdopcion, ":fecha" => date("Y-m-d", strtotime("+30 days")), ":tipo" => "MES"]);
        $stmt->execute([":id_adopcion" => $idAdopcion, ":fecha" => date("Y-m-d", strtotime("+90 days")), ":tipo" => "TRES_MESES"]);
    }

    public static function listarPorAdopciones(PDO $conexion, array $idsAdopciones): array
    {
        if (empty($idsAdopciones)) {
            return [];
        }
        $placeholders = implode(",", array_fill(0, count($idsAdopciones), "?"));
        $stmt = $conexion->prepare("SELECT * FROM seguimientos WHERE id_adopcion IN ($placeholders) ORDER BY fecha_programada ASC");
        $stmt->execute($idsAdopciones);

        $agrupados = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $seg) {
            $agrupados[$seg["id_adopcion"]][] = $seg;
        }
        return $agrupados;
    }

    public static function perteneceAAdoptante(PDO $conexion, int $idSeguimiento, int $idAdoptante): bool
    {
        $stmt = $conexion->prepare("
            SELECT s.id_seguimiento
            FROM seguimientos s
            INNER JOIN adopciones ad ON s.id_adopcion = ad.id_adopcion
            INNER JOIN solicitudes sol ON ad.id_solicitud = sol.id_solicitud
            WHERE s.id_seguimiento = :id_seguimiento AND sol.id_adoptante = :id_adoptante
            LIMIT 1
        ");
        $stmt->execute([":id_seguimiento" => $idSeguimiento, ":id_adoptante" => $idAdoptante]);
        return (bool) $stmt->fetchColumn();
    }

    public static function completar(PDO $conexion, int $idSeguimiento, ?string $estadoSalud, ?string $adaptacion, ?string $observaciones): void
    {
        $stmt = $conexion->prepare("
            UPDATE seguimientos
            SET estado_salud = :estado_salud, adaptacion = :adaptacion, observaciones = :observaciones,
                estado = 'COMPLETADO', fecha_realizada = NOW()
            WHERE id_seguimiento = :id_seguimiento
        ");
        $stmt->execute([
            ":estado_salud" => $estadoSalud ?: null,
            ":adaptacion" => $adaptacion ?: null,
            ":observaciones" => $observaciones ?: null,
            ":id_seguimiento" => $idSeguimiento,
        ]);
    }
}
