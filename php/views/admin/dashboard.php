<div class="admin-header"><div><h1>Dashboard administrador</h1><p>Resumen general del estado de PawsMatch.</p></div><div class="admin-actions"><button class="btn btn-secondary" onclick="location.reload()">Actualizar</button><a class="btn btn-primary" href="admin-reportes.php">Reportes</a></div></div>
<div class="kpi-grid">
    <div class="kpi-card"><div><p>Usuarios</p><h3><?php echo $totalUsuarios; ?></h3></div></div>
    <div class="kpi-card"><div><p>Refugios</p><h3><?php echo $totalRefugios; ?></h3></div></div>
    <div class="kpi-card"><div><p>Adopciones este mes</p><h3><?php echo $adopcionesMes; ?></h3></div></div>
    <div class="kpi-card"><div><p>Alertas</p><h3><?php echo $alertasPendientes; ?></h3></div></div>
</div>
<div class="chart-grid">
    <section class="admin-card"><h2>Últimas solicitudes</h2><div class="table-responsive">
        <?php if (empty($ultimasSolicitudes)): ?>
            <p class="text-muted py-3">Aún no hay solicitudes registradas.</p>
        <?php else: foreach ($ultimasSolicitudes as $s): ?>
            <div class="list-group-item" style="padding:.7rem 0;border-bottom:1px solid #eee;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <div><strong><?php echo htmlspecialchars($s['adoptante_nombre'] . ' ' . $s['adoptante_apellidos']); ?></strong> solicita a <?php echo htmlspecialchars($s['mascota']); ?><br><small class="text-muted"><?php echo htmlspecialchars(date('d/m/Y', strtotime($s['fecha_solicitud']))); ?></small></div>
                    <span class="badge" style="background:#f3f4f6;padding:.3rem .7rem;border-radius:99px;font-size:.78rem;"><?php echo $etiquetas[$s['estado']] ?? $s['estado']; ?></span>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div></section>
    <section class="admin-card"><h2>Tus notificaciones</h2><div class="table-responsive">
        <?php if (empty($alertas)): ?>
            <p class="text-muted py-3">No tienes notificaciones nuevas.</p>
        <?php else: foreach ($alertas as $n): ?>
            <div class="list-group-item" style="padding:.7rem 0;border-bottom:1px solid #eee;">
                <strong><?php echo htmlspecialchars($n['titulo']); ?></strong><br>
                <small class="text-muted"><?php echo htmlspecialchars($n['mensaje']); ?></small>
            </div>
        <?php endforeach; endif; ?>
    </div></section>
</div>
