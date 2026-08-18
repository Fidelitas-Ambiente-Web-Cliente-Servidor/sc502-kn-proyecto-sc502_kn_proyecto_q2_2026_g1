<?php

/**
 * Modelo: Usuario
 * Toda la interacción con la tabla `usuarios` vive aquí.
 */
class Usuario
{
    public static function obtenerPorCorreoConRol(PDO $conexion, string $correo): ?array
    {
        $stmt = $conexion->prepare("
            SELECT u.*, r.nombre AS rol
            FROM usuarios u
            INNER JOIN roles r ON u.id_rol = r.id_rol
            WHERE u.correo = :correo
            LIMIT 1
        ");
        $stmt->execute([":correo" => $correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function obtenerPorId(PDO $conexion, int $idUsuario): ?array
    {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuario = :id LIMIT 1");
        $stmt->execute([":id" => $idUsuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function correoExiste(PDO $conexion, string $correo, ?int $excluirId = null): bool
    {
        $sql = "SELECT id_usuario FROM usuarios WHERE correo = :correo";
        $params = [":correo" => $correo];
        if ($excluirId !== null) {
            $sql .= " AND id_usuario <> :excluir";
            $params[":excluir"] = $excluirId;
        }
        $stmt = $conexion->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetchColumn();
    }

    public static function crear(PDO $conexion, int $idRol, string $nombre, string $apellidos, string $correo, string $hashContrasena, ?string $telefono = null): int
    {
        $stmt = $conexion->prepare("
            INSERT INTO usuarios (id_rol, nombre, apellidos, correo, contrasena, telefono, estado)
            VALUES (:id_rol, :nombre, :apellidos, :correo, :contrasena, :telefono, 'ACTIVO')
        ");
        $stmt->execute([
            ":id_rol" => $idRol,
            ":nombre" => $nombre,
            ":apellidos" => $apellidos,
            ":correo" => $correo,
            ":contrasena" => $hashContrasena,
            ":telefono" => $telefono,
        ]);
        return (int) $conexion->lastInsertId();
    }

    public static function actualizarDatosBasicos(PDO $conexion, int $idUsuario, string $nombre, string $apellidos, string $correo, ?string $telefono): void
    {
        $stmt = $conexion->prepare("
            UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, correo = :correo, telefono = :telefono
            WHERE id_usuario = :id_usuario
        ");
        $stmt->execute([
            ":nombre" => $nombre, ":apellidos" => $apellidos, ":correo" => $correo,
            ":telefono" => $telefono, ":id_usuario" => $idUsuario,
        ]);
    }

    public static function cambiarEstado(PDO $conexion, int $idUsuario, string $estado): void
    {
        $stmt = $conexion->prepare("UPDATE usuarios SET estado = :estado WHERE id_usuario = :id");
        $stmt->execute([":estado" => $estado, ":id" => $idUsuario]);
    }

    public static function listar(PDO $conexion, string $buscar = "", string $rol = "", string $estado = ""): array
    {
        $sql = "
            SELECT u.id_usuario, u.nombre, u.apellidos, u.correo, u.estado, u.fecha_registro, r.nombre AS rol
            FROM usuarios u INNER JOIN roles r ON u.id_rol = r.id_rol
            WHERE 1 = 1
        ";
        $params = [];
        if ($buscar !== "") {
            $sql .= " AND (u.nombre LIKE :buscar OR u.apellidos LIKE :buscar OR u.correo LIKE :buscar)";
            $params[":buscar"] = "%$buscar%";
        }
        if (in_array($rol, ["ADMIN_GENERAL", "REFUGIO", "ADOPTANTE"])) {
            $sql .= " AND r.nombre = :rol";
            $params[":rol"] = $rol;
        }
        if (in_array($estado, ["ACTIVO", "INACTIVO"])) {
            $sql .= " AND u.estado = :estado";
            $params[":estado"] = $estado;
        }
        $sql .= " ORDER BY u.fecha_registro DESC";

        $stmt = $conexion->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarParaExportar(PDO $conexion): array
    {
        return $conexion->query("
            SELECT u.id_usuario, u.nombre, u.apellidos, u.correo, r.nombre AS rol, u.estado, u.fecha_registro
            FROM usuarios u INNER JOIN roles r ON u.id_rol = r.id_rol
            ORDER BY u.fecha_registro DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function contarTotal(PDO $conexion): int
    {
        return (int) $conexion->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    }

    public static function contarPorEstado(PDO $conexion, string $estado): int
    {
        $stmt = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE estado = :estado");
        $stmt->execute([":estado" => $estado]);
        return (int) $stmt->fetchColumn();
    }

    public static function contarPorRol(PDO $conexion, int $idRol): int
    {
        $stmt = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE id_rol = :id_rol");
        $stmt->execute([":id_rol" => $idRol]);
        return (int) $stmt->fetchColumn();
    }
}
