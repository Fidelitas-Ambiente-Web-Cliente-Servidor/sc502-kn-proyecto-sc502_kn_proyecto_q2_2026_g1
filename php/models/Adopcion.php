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

    /** Cantidad de adoptantes distintos con al menos una adopción (para "familias felices"). */
    public static function contarFamiliasUnicas(PDO $conexion): int
    {
        return (int) $conexion->query("
            SELECT COUNT(DISTINCT sol.id_adoptante)
            FROM adopciones ad
            INNER JOIN solicitudes sol ON ad.id_solicitud = sol.id_solicitud
        ")->fetchColumn();
    }

    /**
     * Adopciones reales de los últimos $meses meses, agrupadas por mes,
     * incluyendo los meses sin ninguna (en 0) para que la gráfica no tenga huecos.
     * Retorna [['etiqueta' => 'ene 2026', 'total' => 0], ...] en orden cronológico.
     */
    public static function contarPorMes(PDO $conexion, int $meses = 6): array
    {
        $stmt = $conexion->prepare("
            SELECT DATE_FORMAT(fecha_adopcion, '%Y-%m') AS mes, COUNT(*) AS total
            FROM adopciones
            WHERE fecha_adopcion >= DATE_SUB(CURDATE(), INTERVAL :meses MONTH)
            GROUP BY mes
        ");
        $stmt->execute([":meses" => $meses]);
        $porMes = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), "total", "mes");

        $mesesEs = ["01" => "ene", "02" => "feb", "03" => "mar", "04" => "abr", "05" => "may", "06" => "jun",
                    "07" => "jul", "08" => "ago", "09" => "sep", "10" => "oct", "11" => "nov", "12" => "dic"];

        $resultado = [];
        for ($i = $meses - 1; $i >= 0; $i--) {
            $fecha = date("Y-m", strtotime("-$i months"));
            [$anio, $mes] = explode("-", $fecha);
            $resultado[] = [
                "etiqueta" => $mesesEs[$mes] . " " . $anio,
                "total" => (int) ($porMes[$fecha] ?? 0),
            ];
        }
        return $resultado;
    }
}
