            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Mis Solicitudes de Adopción</h1>
            </div>

            <!-- Solicitudes activas -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Solicitudes Activas</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($activas)): ?>
                        <p class="text-muted text-center py-4 mb-0">No tienes solicitudes activas. <a href="catalogo.php">Explora el catálogo</a> para encontrar a tu compañero ideal.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mascota</th>
                                        <th>Refugio</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($activas as $s): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="<?php echo htmlspecialchars($s['foto'] ?: 'https://via.placeholder.com/40'); ?>" width="40" height="40" style="border-radius:10px;object-fit:cover" alt="">
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($s['mascota']); ?></strong><br>
                                                        <small class="text-muted"><?php echo htmlspecialchars($s['especie']); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($s['nombre_refugio']); ?></td>
                                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($s['fecha_solicitud']))); ?></td>
                                            <td><span class="badge <?php echo $badges[$s['estado']]; ?>"><?php echo $etiquetas[$s['estado']]; ?></span></td>
                                            <td class="text-end" style="white-space:nowrap">
                                                <a href="mensajes.php?solicitud=<?php echo (int) $s['id_solicitud']; ?>" class="btn btn-sm btn-outline-primary position-relative me-1">
                                                    Mensajes
                                                    <?php if (!empty($noLeidos[$s['id_solicitud']])): ?>
                                                        <span class="badge rounded-pill bg-danger" style="position:absolute;top:-8px;right:-8px;"><?php echo $noLeidos[$s['id_solicitud']]; ?></span>
                                                    <?php endif; ?>
                                                </a>
                                                <form action="php/cancelar_solicitud.php" method="POST" style="display:inline" onsubmit="return confirm('¿Cancelar esta solicitud de adopción?');">
                                                    <input type="hidden" name="id_solicitud" value="<?php echo (int) $s['id_solicitud']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Historial -->
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Historial de Solicitudes</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($historial)): ?>
                        <p class="text-muted text-center py-4 mb-0">Aún no tienes solicitudes resueltas.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mascota</th>
                                        <th>Refugio</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Observaciones</th>
                                        <th class="text-end">Mensajes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historial as $s): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($s['mascota']); ?></td>
                                            <td><?php echo htmlspecialchars($s['nombre_refugio']); ?></td>
                                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($s['fecha_solicitud']))); ?></td>
                                            <td><span class="badge <?php echo $badges[$s['estado']]; ?>"><?php echo $etiquetas[$s['estado']]; ?></span></td>
                                            <td><small class="text-muted"><?php echo htmlspecialchars($s['observaciones_refugio'] ?: '—'); ?></small></td>
                                            <td class="text-end">
                                                <a href="mensajes.php?solicitud=<?php echo (int) $s['id_solicitud']; ?>" class="btn btn-sm btn-outline-primary position-relative">
                                                    Ver
                                                    <?php if (!empty($noLeidos[$s['id_solicitud']])): ?>
                                                        <span class="badge rounded-pill bg-danger" style="position:absolute;top:-8px;right:-8px;"><?php echo $noLeidos[$s['id_solicitud']]; ?></span>
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
