<div class="admin-header"><div><h1>Reportes</h1><p>Exporte datos del sistema en CSV y revise denuncias recibidas.</p></div></div>

<section class="admin-card">
    <h2 style="margin-bottom:1rem">Exportar datos (CSV real desde MySQL)</h2>
    <div class="report-grid">
        <div class="report-card"><h3>Usuarios</h3><p class="text-muted">Listado de usuarios y roles.</p><a class="btn btn-primary" href="php/exportar_csv.php?tipo=usuarios">Exportar CSV</a></div>
        <div class="report-card"><h3>Refugios</h3><p class="text-muted">Estado de refugios registrados.</p><a class="btn btn-primary" href="php/exportar_csv.php?tipo=refugios">Exportar CSV</a></div>
        <div class="report-card"><h3>Roles</h3><p class="text-muted">Roles y usuarios asociados.</p><a class="btn btn-primary" href="php/exportar_csv.php?tipo=roles">Exportar CSV</a></div>
        <div class="report-card"><h3>Bitácora</h3><p class="text-muted">Historial de acciones del sistema.</p><a class="btn btn-primary" href="php/exportar_csv.php?tipo=bitacora">Exportar CSV</a></div>
    </div>
</section>

<section class="admin-card" style="margin-top:1.4rem">
    <h2 style="margin-bottom:1rem">Denuncias y reportes de bienestar</h2>
    <div class="kpi-grid" style="margin-bottom:1.2rem">
        <div class="kpi-card"><div><p>Total denuncias</p><h3><?php echo count($denuncias); ?></h3></div></div>
        <div class="kpi-card"><div><p>Pendientes</p><h3><?php echo $pendientesDenuncia; ?></h3></div></div>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead><tr><th>Fecha</th><th>Usuario</th><th>Mascota</th><th>Tipo</th><th>Descripción</th><th>Estado</th><th class="text-end">Acción</th></tr></thead>
            <tbody>
                <?php if (empty($denuncias)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No se han recibido denuncias o reportes de bienestar.</td></tr>
                <?php else: foreach ($denuncias as $d): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($d['fecha_reporte']))); ?></td>
                        <td><?php echo htmlspecialchars($d['usuario_nombre'] . ' ' . $d['usuario_apellidos']); ?></td>
                        <td><?php echo htmlspecialchars($d['mascota'] ?: '—'); ?></td>
                        <td><?php echo htmlspecialchars($d['tipo']); ?></td>
                        <td><small class="text-muted"><?php echo htmlspecialchars($d['descripcion']); ?></small></td>
                        <td><span class="badge" style="<?php echo $estadoBadge[$d['estado']] ?? ''; ?>padding:.3rem .7rem;border-radius:99px;font-size:.78rem;"><?php echo $d['estado']; ?></span></td>
                        <td class="text-end" style="white-space:nowrap">
                            <?php if ($d['estado'] === 'PENDIENTE'): ?>
                                <form action="php/admin_reporte_estado.php" method="POST" style="display:inline">
                                    <input type="hidden" name="id_reporte" value="<?php echo (int) $d['id_reporte']; ?>">
                                    <input type="hidden" name="estado" value="EN_REVISION">
                                    <button type="submit" class="btn btn-sm btn-outline">Poner en revisión</button>
                                </form>
                            <?php elseif ($d['estado'] === 'EN_REVISION'): ?>
                                <form action="php/admin_reporte_estado.php" method="POST" style="display:inline">
                                    <input type="hidden" name="id_reporte" value="<?php echo (int) $d['id_reporte']; ?>">
                                    <input type="hidden" name="estado" value="RESUELTO">
                                    <button type="submit" class="btn btn-sm btn-success-outline">Marcar resuelto</button>
                                </form>
                            <?php else: ?>
                                <small class="text-muted">—</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</section>
