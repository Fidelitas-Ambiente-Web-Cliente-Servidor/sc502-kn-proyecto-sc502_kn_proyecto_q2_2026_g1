<?php

/**
 * Modelo: Recuperación de contraseña.
 * El token se genera aleatoriamente y solo se guarda su hash (sha256) en la base,
 * igual que nunca se guarda la contraseña en texto plano.
 */
class RecuperacionPassword
{
    private const DURACION_HORAS = 1;

    public static function crearToken(PDO $conexion, int $idUsuario): string
    {
        // Invalida cualquier token anterior sin usar de este usuario
        $conexion->prepare("UPDATE recuperaciones_password SET usado = 1 WHERE id_usuario = :id AND usado = 0")
            ->execute([":id" => $idUsuario]);

        $token = bin2hex(random_bytes(32));
        $tokenHash = hash("sha256", $token);
        $expiracion = date("Y-m-d H:i:s", strtotime("+" . self::DURACION_HORAS . " hours"));

        $stmt = $conexion->prepare("
            INSERT INTO recuperaciones_password (id_usuario, token_hash, fecha_expiracion)
            VALUES (:id_usuario, :token_hash, :expiracion)
        ");
        $stmt->execute([":id_usuario" => $idUsuario, ":token_hash" => $tokenHash, ":expiracion" => $expiracion]);

        return $token;
    }

    /** Retorna el id_usuario si el token es válido, vigente y no usado; null en caso contrario. */
    public static function validarToken(PDO $conexion, string $token): ?int
    {
        $tokenHash = hash("sha256", $token);

        $stmt = $conexion->prepare("
            SELECT id_usuario FROM recuperaciones_password
            WHERE token_hash = :token_hash AND usado = 0 AND fecha_expiracion > NOW()
            LIMIT 1
        ");
        $stmt->execute([":token_hash" => $tokenHash]);
        $idUsuario = $stmt->fetchColumn();

        return $idUsuario !== false ? (int) $idUsuario : null;
    }

    public static function marcarUsado(PDO $conexion, string $token): void
    {
        $tokenHash = hash("sha256", $token);
        $conexion->prepare("UPDATE recuperaciones_password SET usado = 1 WHERE token_hash = :token_hash")
            ->execute([":token_hash" => $tokenHash]);
    }
}
