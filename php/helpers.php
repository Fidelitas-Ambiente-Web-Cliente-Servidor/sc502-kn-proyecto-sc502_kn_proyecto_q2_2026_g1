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
