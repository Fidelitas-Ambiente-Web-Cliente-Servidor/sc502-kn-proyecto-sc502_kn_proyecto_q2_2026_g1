<?php

require_once __DIR__ . "/models/autoload.php";

/**
 * Atajos usados por los controladores. Delegan siempre en los Modelos
 * (php/models/) — aquí no debe escribirse SQL directamente.
 */

function registrarBitacora(PDO $conexion, ?int $idUsuario, string $accion, string $descripcion = ""): void
{
    Bitacora::registrar($conexion, $idUsuario, $accion, $descripcion);
}

function crearNotificacion(PDO $conexion, int $idUsuario, string $titulo, string $mensaje, string $tipo = "SISTEMA"): void
{
    Notificacion::crear($conexion, $idUsuario, $titulo, $mensaje, $tipo);
}

function crearNotificacionParaRol(PDO $conexion, string $rol, string $titulo, string $mensaje, string $tipo = "SISTEMA"): void
{
    Notificacion::crearParaRol($conexion, $rol, $titulo, $mensaje, $tipo);
}

/**
 * Obtiene el perfil de refugio ligado al usuario en sesión.
 * Redirige si el usuario tiene rol REFUGIO pero no tiene un refugio asociado.
 */
function obtenerRefugioSesion(PDO $conexion): array
{
    $refugio = Refugio::obtenerPorUsuario($conexion, $_SESSION["id_usuario"]);

    if (!$refugio) {
        header("Location: index.php?error=refugio");
        exit;
    }

    return $refugio;
}

/**
 * Obtiene el perfil de adoptante ligado al usuario en sesión.
 */
function obtenerAdoptanteSesion(PDO $conexion): array
{
    $adoptante = Adoptante::obtenerConUsuario($conexion, $_SESSION["id_usuario"]);

    if (!$adoptante || empty($adoptante["id_adoptante"])) {
        header("Location: index.php?error=adoptante");
        exit;
    }

    return $adoptante;
}

/**
 * Paleta de acento por refugio: cada refugio recibe un color distinto entre sí
 * (ciclando por id_refugio) para diferenciar visualmente sus paneles.
 */
const PALETA_REFUGIOS = [
    0 => ["clase" => "refugio-color-0", "color" => "#14b8a6", "oscuro" => "#0f766e"],
    1 => ["clase" => "refugio-color-1", "color" => "#f97316", "oscuro" => "#c2410c"],
    2 => ["clase" => "refugio-color-2", "color" => "#6366f1", "oscuro" => "#4338ca"],
    3 => ["clase" => "refugio-color-3", "color" => "#ec4899", "oscuro" => "#9d174d"],
];

function claseTemaRefugio(int $idRefugio): string
{
    return PALETA_REFUGIOS[$idRefugio % 4]["clase"];
}

function colorTemaRefugio(int $idRefugio): string
{
    return PALETA_REFUGIOS[$idRefugio % 4]["color"];
}

function colorTemaAdmin(): string
{
    return "#f59e0b";
}
