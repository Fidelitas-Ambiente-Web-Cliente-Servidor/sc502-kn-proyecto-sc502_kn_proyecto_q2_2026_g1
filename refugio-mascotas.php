<?php

session_start();

require_once "php/config.php";
require_once "php/guard.php";
require_once "php/helpers.php";

requerirRol("REFUGIO", "login.html");

$paginaActivaRefugio = "mascotas";

$refugio   = obtenerRefugioSesion($conexion);
$idRefugio = $refugio["id_refugio"];

$mascotas = Mascota::listarPorRefugio($conexion, $idRefugio);

$total       = count($mascotas);
$disponibles = count(array_filter($mascotas, fn($m) => $m["estado"] === "DISPONIBLE"));
$adoptadas   = count(array_filter($mascotas, fn($m) => $m["estado"] === "ADOPTADO"));
$inactivas   = count(array_filter($mascotas, fn($m) => $m["estado"] === "INACTIVO"));

$estadoBadge = [
    "DISPONIBLE" => "background:#dcfce7;color:#166534;", "EN_PROCESO" => "background:#dbeafe;color:#1e40af;",
    "ADOPTADO" => "background:#ede9fe;color:#5b21b6;", "INACTIVO" => "background:#f3f4f6;color:#4b5563;",
];
$estadoTexto = ["DISPONIBLE" => "Disponible", "EN_PROCESO" => "En proceso", "ADOPTADO" => "Adoptado", "INACTIVO" => "Inactiva"];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <title>Gestión de mascotas | <?php echo htmlspecialchars($refugio['nombre_refugio']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/admin.css?v=4"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body <?php echo claseTemaRefugio($refugio['id_refugio']); ?>" data-page="refugio-mascotas">

<?php require "php/views/partials/navbar_refugio.php"; ?>
<?php require "php/views/refugio/mascotas.php"; ?>
<?php require "php/views/partials/footer_refugio.php"; ?>

<script src="js/main.js"></script>
<script src="js/toast.js"></script>
<script>
    window.abrirModal = function (id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.add('show');
    };

    document.addEventListener('click', function (e) {
        const openBtn = e.target.closest('[data-open-modal]');
        if (openBtn) {
            document.getElementById('formMascota').reset();
            document.getElementById('mascotaId').value = '';
            document.getElementById('mascotaModalTitle').textContent = 'Nueva mascota';
            abrirModal(openBtn.dataset.openModal);
        }
        const closeBtn = e.target.closest('[data-close-modal]');
        if (closeBtn) closeBtn.closest('.modal')?.classList.remove('show');
        if (e.target.classList?.contains('modal')) e.target.classList.remove('show');
    });

    function abrirEdicion(m) {
        document.getElementById('mascotaId').value = m.id_mascota;
        document.getElementById('mNombre').value = m.nombre || '';
        document.getElementById('mEspecie').value = m.especie || '';
        document.getElementById('mRaza').value = m.raza || '';
        document.getElementById('mEdad').value = m.edad ?? '';
        document.getElementById('mSexo').value = m.sexo || '';
        document.getElementById('mTamano').value = m.tamano || '';
        document.getElementById('mEnergia').value = m.nivel_energia || '';
        document.getElementById('mFoto').value = m.foto || '';
        document.getElementById('mDescripcion').value = m.descripcion || '';
        document.getElementById('mVacunado').checked = Number(m.vacunado) === 1;
        document.getElementById('mEsterilizado').checked = Number(m.esterilizado) === 1;
        document.getElementById('mNinos').checked = Number(m.compatible_ninos) === 1;
        document.getElementById('mAnimales').checked = Number(m.compatible_animales) === 1;
        document.getElementById('mascotaModalTitle').textContent = 'Editar mascota';
        abrirModal('mascotaModal');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(window.location.search);
        const mensajes = {
            guardado: ['Mascota guardada correctamente.', 'success'],
            eliminado: ['Mascota eliminada.', 'info'],
            visibilidad: ['Visibilidad actualizada.', 'success'],
        };
        for (const [key, [msg, tipo]] of Object.entries(mensajes)) {
            if (params.get(key) === 'ok') PawsMatchToast.show(msg, tipo, 2500);
        }
        const errores = {
            campos: 'Completa los campos obligatorios.',
            noaprobado: 'Tu refugio todavía no está aprobado.',
            permiso: 'No tienes permiso sobre esa mascota.',
            adoptada: 'No puedes eliminar una mascota ya adoptada.',
            sistema: 'Ocurrió un error al guardar.',
        };
        const error = params.get('error');
        if (error && errores[error]) PawsMatchToast.show(errores[error], 'error', 3000);

        if (params.toString() !== '') {
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
</script>
</body>
</html>
