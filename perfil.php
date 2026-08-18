<?php

session_start();

require_once "php/config.php";
require_once "php/guard.php";
require_once "php/helpers.php";

requerirRol("ADOPTANTE", "login.html");

$paginaActivaAdoptante = "perfil";

$usuario = Adoptante::obtenerConUsuario($conexion, $_SESSION["id_usuario"]);

if (!$usuario) {
    session_destroy();
    header("Location: login.html");
    exit;
}

$nombreCompleto = trim($usuario["nombre"] . " " . $usuario["apellidos"]);

$favoritos = [];
$solicitudesActivas = 0;
$proximoSeguimiento = null;
$mascotasRecomendadas = [];

if (!empty($usuario["id_adoptante"])) {
    $idAdoptante = (int) $usuario["id_adoptante"];

    $favoritos = Favorito::listarPorAdoptante($conexion, $idAdoptante);

    $todasSolicitudes = Solicitud::listarPorAdoptante($conexion, $idAdoptante);
    $solicitudesActivas = count(array_filter($todasSolicitudes, fn($s) => in_array($s["estado"], ["PENDIENTE", "EN_REVISION", "APROBADA"])));

    $adopciones = Adopcion::listarPorAdoptante($conexion, $idAdoptante);
    $seguimientosPorAdopcion = Seguimiento::listarPorAdopciones($conexion, array_column($adopciones, "id_adopcion"));
    foreach ($seguimientosPorAdopcion as $lista) {
        foreach ($lista as $seg) {
            if ($seg["estado"] === "PENDIENTE" && ($proximoSeguimiento === null || $seg["fecha_programada"] < $proximoSeguimiento["fecha_programada"])) {
                $proximoSeguimiento = $seg;
            }
        }
    }

    $mascotasRecomendadas = array_slice(Matching::ordenarPorCompatibilidad(Mascota::listarDisponibles($conexion), $usuario), 0, 4);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <title>Perfil del Adoptante | PawsMatch</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/adoptante.css?v=4">
</head>
<body>

<?php require "php/views/partials/navbar_adoptante.php"; ?>
<?php require "php/views/adoptante/perfil.php"; ?>
<?php require "php/views/partials/footer_adoptante.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/toast.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);

    if (params.get('login') === 'ok') {
        PawsMatchToast.show('¡Bienvenido/a, <?php echo addslashes($usuario["nombre"]); ?>! Has iniciado sesión correctamente.', 'success', 2500);
    }
    if (params.get('actualizado') === 'ok') {
        PawsMatchToast.show('Perfil actualizado correctamente.', 'success', 2500);
    }

    const errores = {
        campos: 'Completa los campos obligatorios.',
        correo: 'Ese correo ya pertenece a otra cuenta.',
        sistema: 'Ocurrió un error al actualizar el perfil.',
    };
    const error = params.get('error');
    if (error && errores[error]) {
        PawsMatchToast.show(errores[error], 'error', 3000);
    }

    if (params.toString() !== '') {
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
</script>

</body>
</html>
