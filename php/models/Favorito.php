<?php

/**
 * Modelo: Favoritos del adoptante
 */
class Favorito
{
    public static function idExistente(PDO $conexion, int $idAdoptante, int $idMascota): ?int
    {
        $stmt = $conexion->prepare("SELECT id_favorito FROM favoritos WHERE id_adoptante = :id_adoptante AND id_mascota = :id_mascota");
        $stmt->execute([":id_adoptante" => $idAdoptante, ":id_mascota" => $idMascota]);
        $id = $stmt->fetchColumn();
        return $id !== false ? (int) $id : null;
    }

    /** Alterna el favorito y retorna 'agregado' o 'quitado'. */
    public static function alternar(PDO $conexion, int $idAdoptante, int $idMascota): string
    {
        $idExistente = self::idExistente($conexion, $idAdoptante, $idMascota);

        if ($idExistente) {
            $conexion->prepare("DELETE FROM favoritos WHERE id_favorito = :id")->execute([":id" => $idExistente]);
            return "quitado";
        }

        $conexion->prepare("INSERT INTO favoritos (id_adoptante, id_mascota) VALUES (:id_adoptante, :id_mascota)")
            ->execute([":id_adoptante" => $idAdoptante, ":id_mascota" => $idMascota]);
        return "agregado";
    }

    public static function listarPorAdoptante(PDO $conexion, int $idAdoptante): array
    {
        $stmt = $conexion->prepare("
            SELECT m.id_mascota, m.nombre, m.especie, m.foto
            FROM favoritos f
            INNER JOIN mascotas m ON f.id_mascota = m.id_mascota
            WHERE f.id_adoptante = :id_adoptante
            ORDER BY f.fecha_registro DESC
        ");
        $stmt->execute([":id_adoptante" => $idAdoptante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
