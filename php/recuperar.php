<?php

require_once "config.php";
require_once "helpers.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../recuperar_contraseña.html");
    exit;
}

$correo = trim($_POST["correo"] ?? "");

if ($correo === "" || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../recuperar_contraseña.html?error=correo");
    exit;
}

$usuario = Usuario::obtenerPorCorreoConRol($conexion, $correo);

if (!$usuario) {
    header("Location: ../recuperar_contraseña.html?error=correo");
    exit;
}

$token = RecuperacionPassword::crearToken($conexion, $usuario["id_usuario"]);

registrarBitacora($conexion, $usuario["id_usuario"], "Solicitó recuperación de contraseña");

$enlace = "../restablecer.php?token=" . urlencode($token);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <title>Recuperar contraseña | PawsMatch</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/responsive.css">
</head>
<body>
<main class="auth-app">
    <section class="auth-shell" style="grid-template-columns:1fr;max-width:520px;margin:4rem auto;">
        <div class="auth-form-panel">
            <div class="auth-form-header">
                <h2>Revisa tus instrucciones</h2>
                <p>Le enviamos un enlace de recuperación a <strong><?php echo htmlspecialchars($correo); ?></strong>.</p>
            </div>

            <div style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;border-radius:12px;padding:1rem 1.2rem;margin:1rem 0;font-size:.9rem;">
                Este entorno de desarrollo no tiene un servidor de correo configurado, así que el enlace
                que normalmente llegaría por email se muestra aquí directamente. Es válido por 1 hora y solo se puede usar una vez.
            </div>

            <p style="word-break:break-all;background:#f9fafb;border-radius:8px;padding:.9rem;font-size:.85rem;">
                <a href="<?php echo htmlspecialchars($enlace); ?>"><?php echo htmlspecialchars($enlace); ?></a>
            </p>

            <div class="back-home">
                <a href="../login.html">← Volver a iniciar sesión</a>
            </div>
        </div>
    </section>
</main>
</body>
</html>
