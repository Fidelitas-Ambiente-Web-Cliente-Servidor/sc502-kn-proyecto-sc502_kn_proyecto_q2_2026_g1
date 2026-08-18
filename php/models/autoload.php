<?php
/**
 * Carga todos los modelos del sistema.
 * Incluir una sola vez desde cada controlador: require_once "php/models/autoload.php";
 */
require_once __DIR__ . "/Usuario.php";
require_once __DIR__ . "/Adoptante.php";
require_once __DIR__ . "/Refugio.php";
require_once __DIR__ . "/Mascota.php";
require_once __DIR__ . "/Solicitud.php";
require_once __DIR__ . "/Adopcion.php";
require_once __DIR__ . "/Seguimiento.php";
require_once __DIR__ . "/Favorito.php";
require_once __DIR__ . "/Notificacion.php";
require_once __DIR__ . "/Bitacora.php";
require_once __DIR__ . "/Rol.php";
require_once __DIR__ . "/Reporte.php";
require_once __DIR__ . "/RecuperacionPassword.php";
require_once __DIR__ . "/Matching.php";
require_once __DIR__ . "/Mensaje.php";
