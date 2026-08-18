            <div class="admin-header">
                <div>
                    <h1>Dashboard del refugio</h1>
                    <p>Resumen general de <?php echo htmlspecialchars($refugio['nombre_refugio']); ?>.</p>
                </div>
                <div class="admin-actions">
                    <a href="refugio-mascotas.php" class="btn btn-primary"><i class="fas fa-paw"></i> Gestionar mascotas</a>
                    <a href="refugio-solicitudes.php" class="btn btn-secondary"><i class="fas fa-clipboard-list"></i> Ver solicitudes</a>
                </div>
            </div>

            <?php if ($refugio['estado'] === 'PENDIENTE'): ?>
                <div class="admin-card" style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;margin-bottom:1.2rem;padding:1rem 1.2rem;">
                    <i class="fas fa-circle-exclamation"></i> Tu refugio está <strong>pendiente de aprobación</strong> por un administrador. Podrás registrar y publicar
                    mascotas en cuanto tu cuenta sea aprobada.
                </div>
            <?php elseif ($refugio['estado'] === 'RECHAZADO'): ?>
                <div class="admin-card" style="background:#fee2e2;border:1px solid #fecaca;color:#991b1b;margin-bottom:1.2rem;padding:1rem 1.2rem;">
                    <i class="fas fa-circle-xmark"></i> Tu refugio fue <strong>rechazado</strong> por un administrador. Contacta a soporte si crees que esto es un error.
                </div>
            <?php endif; ?>

            <div class="stat-grid">
                <div class="stat-card"><div class="stat-label"><i class="fas fa-paw purple"></i> Total mascotas</div><div class="stat-value"><?php echo (int) $kpiMascotas['total']; ?></div></div>
                <div class="stat-card"><div class="stat-label"><i class="fas fa-heart green"></i> Disponibles</div><div class="stat-value"><?php echo (int) $kpiMascotas['disponibles']; ?></div></div>
                <div class="stat-card"><div class="stat-label"><i class="fas fa-house-circle-check blue"></i> Adoptadas</div><div class="stat-value"><?php echo (int) $kpiMascotas['adoptadas']; ?></div></div>
                <div class="stat-card"><div class="stat-label"><i class="fas fa-clock yellow"></i> Solicitudes pendientes</div><div class="stat-value"><?php echo $pendientes; ?></div></div>
            </div>

            <div class="dash-grid">
                <section class="admin-card chart-card">
                    <h2><i class="fas fa-chart-column" style="color:var(--primary);margin-right:.4rem;"></i>Solicitudes por estado</h2>
                    <div class="chart-wrap"><canvas id="chartSolicitudesEstado"></canvas></div>
                </section>
                <section class="admin-card chart-card">
                    <h2><i class="fas fa-chart-pie" style="color:var(--primary);margin-right:.4rem;"></i>Mascotas por estado</h2>
                    <div class="chart-wrap"><canvas id="chartMascotasEstadoRefugio"></canvas></div>
                </section>
            </div>

            <section class="admin-card">
                <h2 style="margin-bottom:1rem">Últimas solicitudes recibidas</h2>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Mascota</th><th>Adoptante</th><th>Fecha</th><th>Estado</th><th class="text-end">Acción</th></tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ultimasSolicitudes)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">Todavía no has recibido solicitudes.</td></tr>
                            <?php else: ?>
                                <?php foreach ($ultimasSolicitudes as $s): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($s['mascota']); ?></strong><br><small class="text-muted"><?php echo htmlspecialchars($s['especie']); ?></small></td>
                                        <td><?php echo htmlspecialchars($s['adoptante_nombre'] . ' ' . $s['adoptante_apellidos']); ?></td>
                                        <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($s['fecha_solicitud']))); ?></td>
                                        <td><span class="badge" style="<?php echo $badges[$s['estado']]; ?>padding:.3rem .7rem;border-radius:99px;font-size:.78rem;"><?php echo $etiquetas[$s['estado']]; ?></span></td>
                                        <td class="text-end"><a href="refugio-solicitudes.php" class="btn btn-sm btn-outline">Ver todas</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="admin-card">
                <h2>Mis mascotas disponibles</h2>
                <?php if (empty($mascotasDestacadas)): ?>
                    <p class="text-muted py-3">Aún no tienes mascotas disponibles publicadas.</p>
                <?php else: ?>
                    <div class="featured-pets-row">
                        <?php foreach ($mascotasDestacadas as $m): ?>
                            <div class="featured-pet-card">
                                <img src="<?php echo htmlspecialchars($m['foto'] ?: 'https://via.placeholder.com/400x300?text=PawsMatch'); ?>" alt="<?php echo htmlspecialchars($m['nombre']); ?>">
                                <div class="fp-body">
                                    <strong><?php echo htmlspecialchars($m['nombre']); ?></strong>
                                    <span><?php echo htmlspecialchars($m['raza']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
