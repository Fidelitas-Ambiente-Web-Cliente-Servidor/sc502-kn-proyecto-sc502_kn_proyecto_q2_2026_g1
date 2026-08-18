<?php

session_start();

require_once "config.php";
require_once "guard.php";
require_once "helpers.php";

requerirRol("ADMIN_GENERAL", "../login.html");

$tipo = $_GET["tipo"] ?? "";

$fuentes = [
    "usuarios" => fn() => Usuario::listarParaExportar($conexion),
    "refugios" => fn() => Refugio::listarParaExportar($conexion),
    "roles"    => fn() => Rol::listarParaExportar($conexion),
    "bitacora" => fn() => Bitacora::listarParaExportar($conexion),
];

if (!isset($fuentes[$tipo])) {
    header("Location: ../admin-reportes.php?error=tipo");
    exit;
}

$filas = $fuentes[$tipo]();

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$tipo.csv\"");

$salida = fopen("php://output", "w");
fprintf($salida, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM para Excel

if (!empty($filas)) {
    fputcsv($salida, array_keys($filas[0]));
    foreach ($filas as $fila) {
        fputcsv($salida, $fila);
    }
} else {
    fputcsv($salida, ["Sin datos"]);
}

fclose($salida);
exit;
