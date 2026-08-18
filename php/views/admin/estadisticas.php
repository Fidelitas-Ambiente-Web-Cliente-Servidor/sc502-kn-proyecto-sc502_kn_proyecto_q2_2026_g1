<div class="admin-header"><div><h1>Estadísticas</h1><p>Indicadores calculados en vivo desde MySQL.</p></div><div class="admin-actions"><button class="btn btn-secondary" onclick="location.reload()">Actualizar</button></div></div>

<div class="kpi-grid">
    <div class="kpi-card"><div><p>Usuarios</p><h3><?php echo $totalUsuarios; ?></h3></div></div>
    <div class="kpi-card"><div><p>Refugios</p><h3><?php echo $totalRefugios; ?></h3></div></div>
    <div class="kpi-card"><div><p>Mascotas</p><h3><?php echo $totalMascotas; ?></h3></div></div>
    <div class="kpi-card"><div><p>Solicitudes pendientes</p><h3><?php echo $solicitudesPend; ?></h3></div></div>
</div>

<div class="chart-grid">
    <section class="admin-card">
        <h2>Resumen operativo</h2>
        <div class="table-responsive">
            <table class="admin-table">
                <tbody>
                    <tr><td>Usuarios activos</td><td><?php echo $usuariosActivos; ?></td></tr>
                    <tr><td>Refugios aprobados</td><td><?php echo $refugiosAprobados; ?></td></tr>
                    <tr><td>Mascotas disponibles</td><td><?php echo $mascotasDisponibles; ?></td></tr>
                    <tr><td>Adopciones registradas</td><td><?php echo $adopcionesTotales; ?></td></tr>
                </tbody>
            </table>
        </div>
    </section>
    <section class="admin-card">
        <h2>Mascotas por especie</h2>
        <div class="table-responsive">
            <table class="admin-table">
                <tbody>
                    <?php if (empty($porEspecie)): ?>
                        <tr><td colspan="2" class="text-center text-muted py-3">Sin mascotas registradas.</td></tr>
                    <?php else: foreach ($porEspecie as $e): ?>
                        <tr><td><?php echo htmlspecialchars($e['especie']); ?></td><td><?php echo (int) $e['total']; ?></td></tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
