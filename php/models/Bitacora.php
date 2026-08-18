<?php

/**
 * Modelo: Bitácora del sistema
 */
class Bitacora
{
    public static function registrar(PDO $conexion, ?int $idUsuario, string $accion, string $descripcion = ""): void
    {
        $stmt = $conexion->prepare("
            INSERT INTO bitacora (id_usuario, accion, descripcion, ip)
            VALUES (:id_usuario, :accion, :descripcion, :ip)
        ");
        $stmt->execute([
            ":id_usuario" => $idUsuario,
            ":accion" => $accion,
            ":descripcion" => $descripcion,
            ":ip" => $_SERVER["REMOTE_ADDR"] ?? "0.0.0.0",
        ]);
    }

    public static function listar(PDO $conexion, string $buscar = "", int $limite = 200): array
    {
        $sql = "
            SELECT b.id_bitacora, b.accion, b.descripcion, b.ip, b.fecha,
                   u.nombre AS usuario_nombre, u.apellidos AS usuario_apellidos
            FROM bitacora b
            LEFT JOIN usuarios u ON b.id_usuario = u.id_usuario
            WHERE 1 = 1
        ";
        $params = [];
        if ($buscar !== "") {
            $sql .= " AND (b.accion LIKE :buscar OR b.descripcion LIKE :buscar OR u.nombre LIKE :buscar OR u.apellidos LIKE :buscar)";
            $params[":buscar"] = "%$buscar%";
        }
        $sql .= " ORDER BY b.fecha DESC LIMIT " . (int) $limite;

        $stmt = $conexion->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarParaExportar(PDO $conexion): array
    {
        return $conexion->query("
            SELECT b.id_bitacora, CONCAT(u.nombre, ' ', u.apellidos) AS usuario, b.accion, b.descripcion, b.ip, b.fecha
            FROM bitacora b LEFT JOIN usuarios u ON b.id_usuario = u.id_usuario
            ORDER BY b.fecha DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }
}
