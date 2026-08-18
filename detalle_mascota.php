<?php

session_start();

require_once "php/config.php";
require_once "php/models/autoload.php";

$paginaActivaPublica = "mascotas";

$idMascota = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$idMascota) {
    header("Location: catalogo.php");
    exit;
}

$mascota = Mascota::obtenerPorId($conexion, $idMascota);

if (!$mascota) {
    header("Location: catalogo.php");
    exit;
}

$tamanos = ["PEQUENO" => "Pequeño", "MEDIANO" => "Mediano", "GRANDE" => "Grande"];
$sexos   = ["MACHO" => "Macho", "HEMBRA" => "Hembra"];
$estados = [
    "DISPONIBLE" => "Disponible para adopción",
    "EN_PROCESO" => "En proceso de adopción",
    "ADOPTADO"   => "Ya fue adoptado",
    "INACTIVO"   => "No disponible",
];

$rasgos = [];
if ($mascota["compatible_ninos"])    $rasgos[] = "Compatible con niños";
if ($mascota["compatible_animales"]) $rasgos[] = "Compatible con otros animales";
if ($mascota["vacunado"])            $rasgos[] = "Vacunado";
if ($mascota["esterilizado"])        $rasgos[] = "Esterilizado";

/* Estado de sesión: solicitud propia + favorito */
$esAdoptante      = isset($_SESSION["rol"]) && $_SESSION["rol"] === "ADOPTANTE";
$solicitudPropia  = null;
$esFavorito       = false;
$compatibilidad   = null;

if ($esAdoptante) {
    $idAdoptante = Adoptante::obtenerIdPorUsuario($conexion, $_SESSION["id_usuario"]);
    if ($idAdoptante) {
        $solicitudPropia = Solicitud::obtenerEstadoActivoDeAdoptante($conexion, $idAdoptante, $idMascota);
        $esFavorito = Favorito::idExistente($conexion, $idAdoptante, $idMascota) !== null;

        $perfilAdoptante = Adoptante::obtenerConUsuario($conexion, $_SESSION["id_usuario"]);
        $compatibilidad  = Matching::calcularPorcentaje($perfilAdoptante, $mascota);
    }
}

$estadosSolicitud = [
    "PENDIENTE"   => "Tu solicitud está pendiente de revisión.",
    "EN_REVISION" => "Tu solicitud está en revisión por el refugio.",
    "APROBADA"    => "¡Tu solicitud fue aprobada!",
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <title><?php echo htmlspecialchars($mascota["nombre"]); ?> | PawsMatch</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>

<?php require "php/views/partials/navbar_publico.php"; ?>

<main class="detail-page">
<?php require "php/views/publico/detalle_mascota.php"; ?>
</main>

<script src="js/toast.js"></script>
<script src="js/main.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);

    if (params.get('solicitud') === 'ok') {
        PawsMatchToast.show('¡Tu solicitud de adopción fue enviada! El refugio la revisará pronto.', 'success', 3500);
    }
    if (params.get('favorito') === 'agregado') {
        PawsMatchToast.show('Mascota agregada a tus favoritos.', 'success', 2500);
    }
    if (params.get('favorito') === 'quitado') {
        PawsMatchToast.show('Mascota quitada de tus favoritos.', 'info', 2500);
    }
    if (params.get('reporte') === 'ok') {
        PawsMatchToast.show('Tu denuncia fue enviada. Un administrador la revisará.', 'success', 3500);
    }

    const errores = {
        nodisponible: 'Esta mascota ya no está disponible para adopción.',
        duplicada: 'Ya tienes una solicitud activa para esta mascota.',
        campos: 'Completa el tipo y la descripción de la denuncia.',
        sistema: 'Ocurrió un error al procesar tu solicitud.',
    };
    const error = params.get('error');
    if (error && errores[error]) {
        PawsMatchToast.show(errores[error], 'error', 3500);
    }

    if (params.toString() !== '') {
        window.history.replaceState({}, document.title, window.location.pathname + '?id=<?php echo (int) $mascota['id_mascota']; ?>');
    }
});
</script>

</body>
</html>
