<div class="admin-header">
    <div><h1>Dashboard administrador</h1><p>Resumen general del estado de PawsMatch.</p></div>
    <div class="admin-actions"><button class="btn btn-secondary" onclick="location.reload()"><i class="fas fa-rotate-right"></i> Actualizar</button><a class="btn btn-primary" href="admin-reportes.php"><i class="fas fa-flag"></i> Reportes</a></div>
</div>

<div class="stat-grid">
    <div class="stat-card"><div class="stat-label"><i class="fas fa-users purple"></i> Usuarios registrados</div><div class="stat-value"><?php echo $totalUsuarios; ?></div></div>
    <div class="stat-card"><div class="stat-label"><i class="fas fa-house-chimney blue"></i> Refugios activos</div><div class="stat-value"><?php echo $totalRefugios; ?></div></div>
    <div class="stat-card"><div class="stat-label"><i class="fas fa-heart green"></i> Adopciones este mes</div><div class="stat-value"><?php echo $adopcionesMes; ?></div></div>
    <div class="stat-card"><div class="stat-label"><i class="fas fa-paw yellow"></i> Mascotas registradas</div><div class="stat-value"><?php echo $totalMascotas; ?></div></div>
</div>

<div class="dash-grid">
    <section class="admin-card chart-card">
        <h2><i class="fas fa-chart-column" style="color:var(--primary);margin-right:.4rem;"></i>Adopciones por mes</h2>
        <div class="chart-wrap"><canvas id="chartAdopcionesMes"></canvas></div>
    </section>
    <section class="admin-card chart-card">
        <h2><i class="fas fa-chart-pie" style="color:var(--primary);margin-right:.4rem;"></i>Mascotas por estado</h2>
        <div class="chart-wrap"><canvas id="chartMascotasEstado"></canvas></div>
    </section>
</div>

<div class="dash-grid">
    <section class="admin-card">
        <h2>Últimas solicitudes</h2>
        <div class="table-responsive">
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
        </div>
    </section>

    <section class="admin-card">
        <h2><i class="fas fa-triangle-exclamation" style="color:#f59e0b;margin-right:.4rem;"></i>Alertas</h2>
        <div class="alert-list">
            <?php if ($refugiosPendientes > 0): ?>
                <a href="admin-refugios.php" class="alert-item warn" style="text-decoration:none;">
                    <i class="fas fa-house-chimney"></i>
                    <div><strong><?php echo $refugiosPendientes; ?> refugio<?php echo $refugiosPendientes === 1 ? '' : 's'; ?> pendiente<?php echo $refugiosPendientes === 1 ? '' : 's'; ?></strong><span>Esperando aprobación</span></div>
                </a>
            <?php endif; ?>
            <?php if ($denunciasPendientes > 0): ?>
                <a href="admin-reportes.php" class="alert-item danger" style="text-decoration:none;">
                    <i class="fas fa-flag"></i>
                    <div><strong><?php echo $denunciasPendientes; ?> denuncia<?php echo $denunciasPendientes === 1 ? '' : 's'; ?> sin revisar</strong><span>Requieren atención</span></div>
                </a>
            <?php endif; ?>
            <?php if ($mascotasEstancadas > 0): ?>
                <a href="admin-estadisticas.php" class="alert-item info" style="text-decoration:none;">
                    <i class="fas fa-clock"></i>
                    <div><strong><?php echo $mascotasEstancadas; ?> mascota<?php echo $mascotasEstancadas === 1 ? '' : 's'; ?> sin adoptar</strong><span>Disponibles hace más de 30 días</span></div>
                </a>
            <?php endif; ?>
            <?php if ($refugiosPendientes === 0 && $denunciasPendientes === 0 && $mascotasEstancadas === 0): ?>
                <p class="alert-empty">No hay alertas pendientes por el momento.</p>
            <?php endif; ?>
        </div>
    </section>
</div>

<section class="admin-card">
    <h2>Mascotas destacadas</h2>
    <?php if (empty($mascotasDestacadas)): ?>
        <p class="text-muted py-3">Aún no hay mascotas disponibles.</p>
    <?php else: ?>
        <div class="featured-pets-row">
            <?php foreach ($mascotasDestacadas as $m): ?>
                <div class="featured-pet-card">
                    <img src="<?php echo htmlspecialchars($m['foto'] ?: 'https://via.placeholder.com/400x300?text=PawsMatch'); ?>" alt="<?php echo htmlspecialchars($m['nombre']); ?>">
                    <div class="fp-body">
                        <strong><?php echo htmlspecialchars($m['nombre']); ?></strong>
                        <span><?php echo htmlspecialchars($m['raza']); ?> · <?php echo htmlspecialchars($m['nombre_refugio']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
