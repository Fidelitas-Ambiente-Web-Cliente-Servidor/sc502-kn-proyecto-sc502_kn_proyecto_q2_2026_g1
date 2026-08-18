<?php

/**
 * Modelo: Refugio
 */
class Refugio
{
    public static function obtenerPorUsuario(PDO $conexion, int $idUsuario): ?array
    {
        $stmt = $conexion->prepare("SELECT * FROM refugios WHERE id_usuario = :id_usuario LIMIT 1");
        $stmt->execute([":id_usuario" => $idUsuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function obtenerPorId(PDO $conexion, int $idRefugio): ?array
    {
        $stmt = $conexion->prepare("SELECT * FROM refugios WHERE id_refugio = :id LIMIT 1");
        $stmt->execute([":id" => $idRefugio]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function listar(PDO $conexion, string $buscar = "", string $estado = ""): array
    {
        $sql = "
            SELECT r.id_refugio, r.nombre_refugio, r.telefono, r.direccion, r.estado, r.fecha_registro, u.correo, u.id_usuario
            FROM refugios r INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
            WHERE 1 = 1
        ";
        $params = [];
        if ($buscar !== "") {
            $sql .= " AND (r.nombre_refugio LIKE :buscar OR u.correo LIKE :buscar OR r.direccion LIKE :buscar)";
            $params[":buscar"] = "%$buscar%";
        }
        if (in_array($estado, ["PENDIENTE", "APROBADO", "RECHAZADO"])) {
            $sql .= " AND r.estado = :estado";
            $params[":estado"] = $estado;
        }
        $sql .= " ORDER BY r.fecha_registro DESC";

        $stmt = $conexion->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarParaExportar(PDO $conexion): array
    {
        return $conexion->query("
            SELECT r.id_refugio, r.nombre_refugio, u.correo, r.telefono, r.direccion, r.estado, r.fecha_registro
            FROM refugios r INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
            ORDER BY r.fecha_registro DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crea la cuenta de usuario (rol REFUGIO) y su perfil de refugio en una sola transacción.
     * Retorna el id_refugio creado.
     */
    public static function crearConUsuario(PDO $conexion, array $datos, string $estadoInicial = "APROBADO"): int
    {
        $conexion->beginTransaction();
        try {
            $partesNombre = explode(" ", $datos["nombre_refugio"], 2);

            $idUsuario = Usuario::crear(
                $conexion,
                2, // REFUGIO
                $partesNombre[0],
                $partesNombre[1] ?? "Refugio",
                $datos["correo"],
                password_hash($datos["contrasena"], PASSWORD_DEFAULT),
                $datos["telefono"] ?: null
            );

            $stmt = $conexion->prepare("
                INSERT INTO refugios (id_usuario, nombre_refugio, cedula_juridica, telefono, direccion, descripcion, estado)
                VALUES (:id_usuario, :nombre_refugio, :cedula, :telefono, :direccion, :descripcion, :estado)
            ");
            $stmt->execute([
                ":id_usuario" => $idUsuario,
                ":nombre_refugio" => $datos["nombre_refugio"],
                ":cedula" => $datos["cedula_juridica"] ?: null,
                ":telefono" => $datos["telefono"] ?: null,
                ":direccion" => $datos["direccion"] ?: null,
                ":descripcion" => $datos["descripcion"] ?: null,
                ":estado" => $estadoInicial,
            ]);
            $idRefugio = (int) $conexion->lastInsertId();

            $conexion->commit();
            return $idRefugio;
        } catch (Throwable $e) {
            if ($conexion->inTransaction()) $conexion->rollBack();
            throw $e;
        }
    }

    public static function cambiarEstado(PDO $conexion, int $idRefugio, string $estado): void
    {
        $stmt = $conexion->prepare("UPDATE refugios SET estado = :estado WHERE id_refugio = :id");
        $stmt->execute([":estado" => $estado, ":id" => $idRefugio]);
    }

    public static function contarTotal(PDO $conexion): int
    {
        return (int) $conexion->query("SELECT COUNT(*) FROM refugios")->fetchColumn();
    }

    public static function contarPorEstado(PDO $conexion, string $estado): int
    {
        $stmt = $conexion->prepare("SELECT COUNT(*) FROM refugios WHERE estado = :estado");
        $stmt->execute([":estado" => $estado]);
        return (int) $stmt->fetchColumn();
    }
}
