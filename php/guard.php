<?php

/**
 * Protección de páginas por rol de sesión.
 * Requiere que session_start() ya se haya ejecutado.
 *
 * @param string $rolRequerido  'ADMIN_GENERAL' | 'REFUGIO' | 'ADOPTANTE'
 * @param string $loginPath     Ruta relativa al login desde la página que llama
 */
function requerirRol(string $rolRequerido, string $loginPath = "login.html"): void
{
    if (!isset($_SESSION["id_usuario"]) || !isset($_SESSION["rol"])) {
        header("Location: $loginPath?error=sesion");
        exit;
    }

    if ($_SESSION["rol"] !== $rolRequerido) {
        header("Location: $loginPath?error=permiso");
        exit;
    }
}
