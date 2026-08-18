            <div class="admin-header">
                <div>
                    <h1>Dashboard del refugio</h1>
                    <p>Resumen general de <?php echo htmlspecialchars($refugio['nombre_refugio']); ?>.</p>
                </div>
                <div class="admin-actions">
                    <a href="refugio-mascotas.php" class="btn btn-primary">Gestionar mascotas</a>
                    <a href="refugio-solicitudes.php" class="btn btn-secondary">Ver solicitudes</a>
                </div>
            </div>

            <?php if ($refugio['estado'] === 'PENDIENTE'): ?>
                <div class="admin-card" style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;margin-bottom:1.2rem;padding:1rem 1.2rem;">
                    Tu refugio está <strong>pendiente de aprobación</strong> por un administrador. Podrás registrar y publicar
                    mascotas en cuanto tu cuenta sea aprobada.
                </div>
            <?php elseif ($refugio['estado'] === 'RECHAZADO'): ?>
                <div class="admin-card" style="background:#fee2e2;border:1px solid #fecaca;color:#991b1b;margin-bottom:1.2rem;padding:1rem 1.2rem;">
                    Tu refugio fue <strong>rechazado</strong> por un administrador. Contacta a soporte si crees que esto es un error.
                </div>
            <?php endif; ?>

            <div class="kpi-grid">
                <div class="kpi-card"><div><p>Total mascotas</p><h3><?php echo (int) $kpiMascotas['total']; ?></h3></div></div>
                <div class="kpi-card"><div><p>Disponibles</p><h3><?php echo (int) $kpiMascotas['disponibles']; ?></h3></div></div>
                <div class="kpi-card"><div><p>Adoptadas</p><h3><?php echo (int) $kpiMascotas['adoptadas']; ?></h3></div></div>
                <div class="kpi-card"><div><p>Solicitudes pendientes</p><h3><?php echo $pendientes; ?></h3></div></div>
            </div>

            <div class="chart-grid" style="margin-bottom:1.4rem">
                <a href="refugio-mascotas.php" class="admin-card" style="text-decoration:none;display:block">
                    <h3 style="margin-bottom:.3rem">Gestión de mascotas</h3>
                    <p class="text-muted" style="font-size:.9rem">Registrar, editar y publicar mascotas en el catálogo.</p>
                </a>
                <a href="refugio-solicitudes.php" class="admin-card" style="text-decoration:none;display:block">
                    <h3 style="margin-bottom:.3rem">Solicitudes recibidas</h3>
                    <p class="text-muted" style="font-size:.9rem">Aprobar o rechazar solicitudes de adopción.</p>
                </a>
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
