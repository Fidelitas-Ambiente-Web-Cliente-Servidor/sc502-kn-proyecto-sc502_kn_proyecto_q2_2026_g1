<?php

/**
 * Modelo: Mascota
 */
class Mascota
{
    /**
     * @param string|null $busqueda Filtra por nombre o raza (LIKE).
     * @param string|null $especie  "Perro" o "Gato".
     * @param bool $soloCachorros   Solo mascotas menores a 1 año (edad = 0).
     */
    public static function listarDisponibles(PDO $conexion, ?string $busqueda = null, ?string $especie = null, bool $soloCachorros = false): array
    {
        $condiciones = ["m.estado = 'DISPONIBLE'", "r.estado = 'APROBADO'"];
        $parametros = [];

        if ($busqueda !== null && $busqueda !== "") {
            $condiciones[] = "(m.nombre LIKE :busqueda OR m.raza LIKE :busqueda)";
            $parametros[":busqueda"] = "%$busqueda%";
        }
        if ($especie !== null && $especie !== "") {
            $condiciones[] = "m.especie = :especie";
            $parametros[":especie"] = $especie;
        }
        if ($soloCachorros) {
            $condiciones[] = "m.edad = 0";
        }

        $stmt = $conexion->prepare("
            SELECT m.id_mascota, m.nombre, m.especie, m.raza, m.edad, m.tamano, m.nivel_energia,
                   m.compatible_ninos, m.compatible_animales, m.vacunado, m.foto, r.nombre_refugio
            FROM mascotas m
            INNER JOIN refugios r ON m.id_refugio = r.id_refugio
            WHERE " . implode(" AND ", $condiciones) . "
            ORDER BY m.fecha_registro DESC
        ");
        $stmt->execute($parametros);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Solo retorna la mascota si su refugio ya está aprobado (visibilidad pública). */
    public static function obtenerPorId(PDO $conexion, int $idMascota): ?array
    {
        $stmt = $conexion->prepare("
            SELECT m.*, r.nombre_refugio, r.direccion AS refugio_direccion
            FROM mascotas m
            INNER JOIN refugios r ON m.id_refugio = r.id_refugio
            WHERE m.id_mascota = :id AND r.estado = 'APROBADO'
            LIMIT 1
        ");
        $stmt->execute([":id" => $idMascota]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Lectura con bloqueo de fila, para usarse dentro de una transacción. */
    public static function obtenerParaActualizar(PDO $conexion, int $idMascota): ?array
    {
        $stmt = $conexion->prepare("SELECT id_mascota, estado FROM mascotas WHERE id_mascota = :id FOR UPDATE");
        $stmt->execute([":id" => $idMascota]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function obtenerConRefugioParaNotificar(PDO $conexion, int $idMascota): ?array
    {
        $stmt = $conexion->prepare("
            SELECT r.id_usuario, m.nombre
            FROM mascotas m
            INNER JOIN refugios r ON m.id_refugio = r.id_refugio
            WHERE m.id_mascota = :id
        ");
        $stmt->execute([":id" => $idMascota]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function listarPorRefugio(PDO $conexion, int $idRefugio): array
    {
        $stmt = $conexion->prepare("SELECT * FROM mascotas WHERE id_refugio = :id_refugio ORDER BY fecha_registro DESC");
        $stmt->execute([":id_refugio" => $idRefugio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerDeRefugio(PDO $conexion, int $idMascota, int $idRefugio): ?array
    {
        $stmt = $conexion->prepare("SELECT * FROM mascotas WHERE id_mascota = :id AND id_refugio = :id_refugio");
        $stmt->execute([":id" => $idMascota, ":id_refugio" => $idRefugio]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function crear(PDO $conexion, int $idRefugio, array $datos): int
    {
        $stmt = $conexion->prepare("
            INSERT INTO mascotas
                (id_refugio, nombre, especie, raza, edad, sexo, tamano, descripcion, foto,
                 vacunado, esterilizado, compatible_ninos, compatible_animales, nivel_energia, estado)
            VALUES
                (:id_refugio, :nombre, :especie, :raza, :edad, :sexo, :tamano, :descripcion, :foto,
                 :vacunado, :esterilizado, :compatible_ninos, :compatible_animales, :nivel_energia, 'DISPONIBLE')
        ");
        $stmt->execute(self::parametrosComunes($datos) + [":id_refugio" => $idRefugio]);
        return (int) $conexion->lastInsertId();
    }

    public static function actualizar(PDO $conexion, int $idMascota, int $idRefugio, array $datos): bool
    {
        $stmt = $conexion->prepare("
            UPDATE mascotas SET
                nombre = :nombre, especie = :especie, raza = :raza, edad = :edad,
                sexo = :sexo, tamano = :tamano, descripcion = :descripcion, foto = :foto,
                vacunado = :vacunado, esterilizado = :esterilizado,
                compatible_ninos = :compatible_ninos, compatible_animales = :compatible_animales,
                nivel_energia = :nivel_energia
            WHERE id_mascota = :id_mascota AND id_refugio = :id_refugio
        ");
        $stmt->execute(self::parametrosComunes($datos) + [":id_mascota" => $idMascota, ":id_refugio" => $idRefugio]);
        return $stmt->rowCount() > 0;
    }

    private static function parametrosComunes(array $datos): array
    {
        return [
            ":nombre" => $datos["nombre"],
            ":especie" => $datos["especie"],
            ":raza" => $datos["raza"] ?: null,
            ":edad" => $datos["edad"],
            ":sexo" => $datos["sexo"],
            ":tamano" => $datos["tamano"],
            ":descripcion" => $datos["descripcion"] ?: null,
            ":foto" => $datos["foto"] ?: null,
            ":vacunado" => $datos["vacunado"],
            ":esterilizado" => $datos["esterilizado"],
            ":compatible_ninos" => $datos["compatible_ninos"],
            ":compatible_animales" => $datos["compatible_animales"],
            ":nivel_energia" => $datos["nivel_energia"],
        ];
    }

    public static function eliminar(PDO $conexion, int $idMascota, int $idRefugio): void
    {
        $stmt = $conexion->prepare("DELETE FROM mascotas WHERE id_mascota = :id AND id_refugio = :id_refugio");
        $stmt->execute([":id" => $idMascota, ":id_refugio" => $idRefugio]);
    }

    public static function alternarVisibilidad(PDO $conexion, int $idMascota, int $idRefugio, string $estadoActual): ?string
    {
        if (!in_array($estadoActual, ["DISPONIBLE", "INACTIVO"])) {
            return null;
        }
        $nuevoEstado = $estadoActual === "DISPONIBLE" ? "INACTIVO" : "DISPONIBLE";
        $stmt = $conexion->prepare("UPDATE mascotas SET estado = :estado WHERE id_mascota = :id AND id_refugio = :id_refugio");
        $stmt->execute([":estado" => $nuevoEstado, ":id" => $idMascota, ":id_refugio" => $idRefugio]);
        return $nuevoEstado;
    }

    public static function marcarAdoptada(PDO $conexion, int $idMascota): void
    {
        $conexion->prepare("UPDATE mascotas SET estado = 'ADOPTADO' WHERE id_mascota = :id")->execute([":id" => $idMascota]);
    }

    public static function marcarDisponible(PDO $conexion, int $idMascota): void
    {
        $conexion->prepare("UPDATE mascotas SET estado = 'DISPONIBLE' WHERE id_mascota = :id")->execute([":id" => $idMascota]);
    }

    public static function contarPorRefugio(PDO $conexion, int $idRefugio): array
    {
        $stmt = $conexion->prepare("
            SELECT COUNT(*) AS total, SUM(estado = 'DISPONIBLE') AS disponibles,
                   SUM(estado = 'ADOPTADO') AS adoptadas, SUM(estado = 'INACTIVO') AS inactivas
            FROM mascotas WHERE id_refugio = :id_refugio
        ");
        $stmt->execute([":id_refugio" => $idRefugio]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function contarTotal(PDO $conexion): int
    {
        return (int) $conexion->query("SELECT COUNT(*) FROM mascotas")->fetchColumn();
    }

    public static function contarDisponibles(PDO $conexion): int
    {
        return (int) $conexion->query("SELECT COUNT(*) FROM mascotas WHERE estado = 'DISPONIBLE'")->fetchColumn();
    }

    public static function contarPorEspecie(PDO $conexion): array
    {
        return $conexion->query("SELECT especie, COUNT(*) AS total FROM mascotas GROUP BY especie ORDER BY total DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Conteo global por estado (para el donut del admin). */
    public static function contarPorEstadoGeneral(PDO $conexion): array
    {
        $filas = $conexion->query("SELECT estado, COUNT(*) AS total FROM mascotas GROUP BY estado")->fetchAll(PDO::FETCH_ASSOC);
        $mapa = ["DISPONIBLE" => 0, "EN_PROCESO" => 0, "ADOPTADO" => 0, "INACTIVO" => 0];
        foreach ($filas as $f) {
            $mapa[$f["estado"]] = (int) $f["total"];
        }
        return $mapa;
    }

    /** Conteo por estado de un refugio específico (para su propio donut). */
    public static function contarPorEstadoDeRefugio(PDO $conexion, int $idRefugio): array
    {
        $stmt = $conexion->prepare("SELECT estado, COUNT(*) AS total FROM mascotas WHERE id_refugio = :id_refugio GROUP BY estado");
        $stmt->execute([":id_refugio" => $idRefugio]);
        $mapa = ["DISPONIBLE" => 0, "EN_PROCESO" => 0, "ADOPTADO" => 0, "INACTIVO" => 0];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $f) {
            $mapa[$f["estado"]] = (int) $f["total"];
        }
        return $mapa;
    }

    /** Mascotas disponibles hace más de $dias días sin ser adoptadas (para alertas). */
    public static function contarEstancadas(PDO $conexion, int $dias = 30): int
    {
        $stmt = $conexion->prepare("
            SELECT COUNT(*) FROM mascotas
            WHERE estado = 'DISPONIBLE' AND fecha_registro <= DATE_SUB(NOW(), INTERVAL :dias DAY)
        ");
        $stmt->execute([":dias" => $dias]);
        return (int) $stmt->fetchColumn();
    }
}
