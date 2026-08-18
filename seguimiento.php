<?php

session_start();

require_once "php/config.php";
require_once "php/guard.php";
require_once "php/helpers.php";

requerirRol("ADOPTANTE", "login.html");

$paginaActivaAdoptante = "seguimiento";

$adoptante  = obtenerAdoptanteSesion($conexion);
$adopciones = Adopcion::listarPorAdoptante($conexion, $adoptante["id_adoptante"]);

$idsAdopciones = array_column($adopciones, "id_adopcion");
$seguimientosPorAdopcion = Seguimiento::listarPorAdopciones($conexion, $idsAdopciones);

$tiposSeguimiento = ["SEMANA" => "1 Semana", "MES" => "1 Mes", "TRES_MESES" => "3 Meses", "OTRO" => "Seguimiento"];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <title>Seguimiento Post-Adopción - PawsMatch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/adoptante.css?v=4">
</head>
<body>

<?php require "php/views/partials/navbar_adoptante.php"; ?>
<?php require "php/views/adoptante/seguimiento.php"; ?>
<?php require "php/views/partials/footer_adoptante.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/toast.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(window.location.search);
        if (params.get('reporte') === 'ok') PawsMatchToast.show('Reporte de seguimiento enviado correctamente.', 'success', 2500);
        if (params.get('devolucion') === 'ok') PawsMatchToast.show('El proceso de devolución fue iniciado.', 'info', 3000);
        if (params.toString() !== '') window.history.replaceState({}, document.title, window.location.pathname);
    });
</script>
</body>
</html>
