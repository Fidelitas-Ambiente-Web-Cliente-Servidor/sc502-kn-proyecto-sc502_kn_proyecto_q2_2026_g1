<?php

session_start();

require_once "php/config.php";
require_once "php/models/autoload.php";

$paginaActivaPublica = "";

$token = $_GET["token"] ?? "";
$tokenValido = $token !== "" && RecuperacionPassword::validarToken($conexion, $token) !== null;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <title>Restablecer contraseña | PawsMatch</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>

<main class="register-app">
<?php require "php/views/publico/restablecer.php"; ?>
</main>

<script src="js/toast.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const errores = {
        contrasena: 'Las contraseñas no coinciden o son muy cortas (mínimo 6 caracteres).',
        token: 'Ese enlace ya no es válido. Solicita uno nuevo.',
    };
    const error = params.get('error');
    if (error && errores[error]) {
        PawsMatchToast.show(errores[error], 'error', 3500);
    }
});
</script>

</body>
</html>
