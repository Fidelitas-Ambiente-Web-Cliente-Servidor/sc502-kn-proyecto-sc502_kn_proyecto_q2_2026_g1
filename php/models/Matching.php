<?php

/**
 * Modelo: Matching adoptante ↔ mascota.
 * Calcula un porcentaje de compatibilidad comparando las preferencias
 * guardadas en el perfil del adoptante contra los atributos reales de cada mascota.
 * No usa IA: es un sistema de puntos determinístico, fácil de explicar y auditar.
 */
class Matching
{
    /**
     * Calcula puntos obtenidos vs. puntos posibles para un par adoptante/mascota.
     * Solo suma "puntos posibles" en los criterios que el adoptante realmente definió
     * en su perfil, para no penalizar a quien no ha llenado todos los campos.
     */
    public static function calcularCompatibilidad(array $adoptante, array $mascota): array
    {
        $puntos = 0;
        $maximo = 0;

        // Especie preferida
        if (!empty($adoptante["preferencia_especie"])) {
            $maximo += 3;
            if ($adoptante["preferencia_especie"] === $mascota["especie"]) {
                $puntos += 3;
            }
        }

        // Tamaño preferido
        if (!empty($adoptante["preferencia_tamano"])) {
            $maximo += 2;
            if ($adoptante["preferencia_tamano"] === $mascota["tamano"]) {
                $puntos += 2;
            }
        }

        // Hogar con niños: siempre relevante si aplica (es un tema de seguridad, no de gusto)
        if (!empty($adoptante["tiene_ninos"])) {
            $maximo += 2;
            $puntos += $mascota["compatible_ninos"] ? 2 : -2;
        }

        // Hogar con otras mascotas
        if (!empty($adoptante["tiene_otros_animales"])) {
            $maximo += 2;
            $puntos += $mascota["compatible_animales"] ? 2 : -2;
        }

        // Experiencia del adoptante vs. nivel de energía de la mascota
        if (!empty($adoptante["experiencia_mascotas"]) && !empty($mascota["nivel_energia"])) {
            $maximo += 2;
            $experimentado = in_array($adoptante["experiencia_mascotas"], ["INTERMEDIA", "ALTA"]);
            if ($mascota["nivel_energia"] === "ALTO") {
                $puntos += $experimentado ? 2 : -1;
            } else {
                $puntos += 1;
            }
        }

        // Tiempo disponible vs. nivel de energía
        if (!empty($adoptante["tiempo_disponible"]) && !empty($mascota["nivel_energia"])) {
            $maximo += 1;
            if ($mascota["nivel_energia"] === "ALTO" && $adoptante["tiempo_disponible"] === "BAJO") {
                $puntos -= 1;
            } elseif ($mascota["nivel_energia"] === "ALTO" && $adoptante["tiempo_disponible"] === "ALTO") {
                $puntos += 1;
            }
        }

        // Tipo de vivienda vs. tamaño de la mascota
        if (!empty($adoptante["tipo_vivienda"]) && !empty($mascota["tamano"])) {
            $maximo += 1;
            $espacioReducido = $mascota["tamano"] === "GRANDE" && $adoptante["tipo_vivienda"] === "APARTAMENTO" && empty($adoptante["tiene_patio"]);
            $espacioAmplio   = $mascota["tamano"] === "GRANDE" && ($adoptante["tipo_vivienda"] === "CASA" || !empty($adoptante["tiene_patio"]));
            if ($espacioReducido) $puntos -= 1;
            if ($espacioAmplio)   $puntos += 1;
        }

        return ["puntos" => $puntos, "maximo" => $maximo];
    }

    /**
     * Retorna el porcentaje de compatibilidad (0-100), o null si el adoptante
     * no ha llenado suficientes preferencias como para calcular nada.
     */
    public static function calcularPorcentaje(array $adoptante, array $mascota): ?int
    {
        $resultado = self::calcularCompatibilidad($adoptante, $mascota);

        if ($resultado["maximo"] === 0) {
            return null;
        }

        $porcentaje = round(($resultado["puntos"] / $resultado["maximo"]) * 100);
        return max(0, min(100, $porcentaje));
    }

    /**
     * Agrega 'compatibilidad' a cada mascota de la lista y las ordena de mayor a menor.
     * Si $adoptante es null (visitante o sin perfil), retorna la lista sin modificar.
     */
    public static function ordenarPorCompatibilidad(array $mascotas, ?array $adoptante): array
    {
        if (!$adoptante) {
            return $mascotas;
        }

        foreach ($mascotas as &$mascota) {
            $mascota["compatibilidad"] = self::calcularPorcentaje($adoptante, $mascota);
        }
        unset($mascota);

        usort($mascotas, function ($a, $b) {
            return ($b["compatibilidad"] ?? -1) <=> ($a["compatibilidad"] ?? -1);
        });

        return $mascotas;
    }
}
