            <div class="admin-header">
                <div>
                    <h1>Solicitudes recibidas</h1>
                    <p>Gestione las solicitudes de adopción de su refugio.</p>
                </div>
            </div>

            <div class="kpi-grid">
                <div class="kpi-card"><div><p>Total solicitudes</p><h3><?php echo $total; ?></h3></div></div>
                <div class="kpi-card"><div><p>Pendientes</p><h3><?php echo $pendientes; ?></h3></div></div>
                <div class="kpi-card"><div><p>Aprobadas</p><h3><?php echo $aprobadas; ?></h3></div></div>
                <div class="kpi-card"><div><p>Rechazadas</p><h3><?php echo $rechazadas; ?></h3></div></div>
            </div>

            <div style="display:flex;gap:.6rem;margin-bottom:1.2rem">
                <button class="btn btn-primary" id="tabActivas" onclick="mostrarTab('activas')">Solicitudes activas</button>
                <button class="btn btn-secondary" id="tabHistorial" onclick="mostrarTab('historial')">Historial</button>
            </div>

            <div id="panelActivas">
                <section class="admin-card">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr><th>Mascota</th><th>Adoptante</th><th>Fecha</th><th>Estado</th><th class="text-end">Acciones</th></tr>
                            </thead>
                            <tbody>
                                <?php if (empty($activas)): ?>
                                    <tr><td colspan="5" class="text-center text-muted py-4">No hay solicitudes pendientes por revisar.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($activas as $s): ?>
                                        <tr>
                                            <td>
                                                <div style="display:flex;align-items:center;gap:.8rem">
                                                    <img src="<?php echo htmlspecialchars($s['foto'] ?: 'https://via.placeholder.com/40'); ?>" width="40" height="40" style="border-radius:10px;object-fit:cover" alt="">
                                                    <div><strong><?php echo htmlspecialchars($s['mascota']); ?></strong><br><small class="text-muted"><?php echo htmlspecialchars($s['especie']); ?></small></div>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($s['adoptante_nombre'] . ' ' . $s['adoptante_apellidos']); ?></td>
                                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($s['fecha_solicitud']))); ?></td>
                                            <td><span class="badge" style="<?php echo $badges[$s['estado']]; ?>padding:.3rem .7rem;border-radius:99px;font-size:.78rem;"><?php echo $etiquetas[$s['estado']]; ?></span></td>
                                            <td class="text-end" style="white-space:nowrap">
                                                <a href="mensajes.php?solicitud=<?php echo (int) $s['id_solicitud']; ?>" class="btn btn-sm btn-outline me-1" style="position:relative;">
                                                    Mensajes
                                                    <?php if (!empty($noLeidos[$s['id_solicitud']])): ?>
                                                        <span style="position:absolute;top:-8px;right:-8px;background:#ef4444;color:#fff;border-radius:99px;font-size:.68rem;padding:.05rem .4rem;"><?php echo $noLeidos[$s['id_solicitud']]; ?></span>
                                                    <?php endif; ?>
                                                </a>
                                                <form action="php/refugio_solicitud_resolver.php" method="POST" style="display:inline" onsubmit="return confirm('¿Aprobar esta solicitud de adopción?');">
                                                    <input type="hidden" name="id_solicitud" value="<?php echo (int) $s['id_solicitud']; ?>">
                                                    <input type="hidden" name="accion" value="aprobar">
                                                    <button type="submit" class="btn btn-sm btn-success-outline me-1">Aprobar</button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-danger-outline" onclick="abrirRechazo(<?php echo (int) $s['id_solicitud']; ?>)">Rechazar</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div id="panelHistorial" style="display:none">
                <section class="admin-card">
                    <h2 style="margin-bottom:1rem">Historial de solicitudes resueltas</h2>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr><th>Mascota</th><th>Adoptante</th><th>Fecha</th><th>Estado</th><th>Observaciones</th><th class="text-end">Mensajes</th></tr>
                            </thead>
                            <tbody>
                                <?php if (empty($historial)): ?>
                                    <tr><td colspan="6" class="text-center text-muted py-4">Sin solicitudes resueltas aún.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($historial as $s): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($s['mascota']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($s['adoptante_nombre'] . ' ' . $s['adoptante_apellidos']); ?></td>
                                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($s['fecha_solicitud']))); ?></td>
                                            <td><span class="badge" style="<?php echo $badges[$s['estado']]; ?>padding:.3rem .7rem;border-radius:99px;font-size:.78rem;"><?php echo $etiquetas[$s['estado']]; ?></span></td>
                                            <td><small class="text-muted"><?php echo htmlspecialchars($s['observaciones_refugio'] ?: '—'); ?></small></td>
                                            <td class="text-end">
                                                <a href="mensajes.php?solicitud=<?php echo (int) $s['id_solicitud']; ?>" class="btn btn-sm btn-outline" style="position:relative;">
                                                    Ver
                                                    <?php if (!empty($noLeidos[$s['id_solicitud']])): ?>
                                                        <span style="position:absolute;top:-8px;right:-8px;background:#ef4444;color:#fff;border-radius:99px;font-size:.68rem;padding:.05rem .4rem;"><?php echo $noLeidos[$s['id_solicitud']]; ?></span>
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- MODAL RECHAZO -->
            <div class="modal" id="rechazoModal">
                <div class="modal-dialog">
                    <form action="php/refugio_solicitud_resolver.php" method="POST">
                        <input type="hidden" name="accion" value="rechazar">
                        <input type="hidden" id="rechazoIdSolicitud" name="id_solicitud">
                        <div class="modal-header">
                            <h2>Rechazar solicitud</h2>
                            <button type="button" class="close-modal" data-close-modal>&times;</button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Motivo del rechazo (opcional)</label>
                            <textarea name="observaciones" class="form-control" rows="3" placeholder="Ej. No cumple con los requisitos de espacio…"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-close-modal>Cancelar</button>
                            <button class="btn btn-primary">Confirmar rechazo</button>
                        </div>
                    </form>
                </div>
            </div>
