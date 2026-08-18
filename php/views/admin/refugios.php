<div class="admin-header"><div><h1>Gestión de refugios</h1><p>Revise, apruebe y administre los refugios registrados.</p></div><div class="admin-actions"><button class="btn btn-primary" data-open-modal="refugioModal">Nuevo refugio</button><a class="btn btn-secondary" href="php/exportar_csv.php?tipo=refugios">Exportar</a></div></div>

<div class="kpi-grid">
    <div class="kpi-card"><div><p>Total refugios</p><h3><?php echo $totalRefugios; ?></h3></div></div>
    <div class="kpi-card"><div><p>Aprobados</p><h3><?php echo $totalAprobados; ?></h3></div></div>
    <div class="kpi-card"><div><p>Pendientes</p><h3><?php echo $totalPendientes; ?></h3></div></div>
    <div class="kpi-card"><div><p>Rechazados</p><h3><?php echo $totalRechazados; ?></h3></div></div>
</div>

<section class="admin-card">
    <form method="GET" class="admin-filters">
        <div><label class="form-label">Buscar</label><input type="text" name="buscar" class="form-control" placeholder="Nombre, correo o dirección" value="<?php echo htmlspecialchars($buscar); ?>"></div>
        <div><label class="form-label">Estado</label>
            <select name="estado" class="form-select" onchange="this.form.submit()">
                <option value="">Todos</option>
                <option value="APROBADO" <?php echo $filtroEstado === 'APROBADO' ? 'selected' : ''; ?>>Aprobado</option>
                <option value="PENDIENTE" <?php echo $filtroEstado === 'PENDIENTE' ? 'selected' : ''; ?>>Pendiente</option>
                <option value="RECHAZADO" <?php echo $filtroEstado === 'RECHAZADO' ? 'selected' : ''; ?>>Rechazado</option>
            </select>
        </div>
        <div style="align-self:end"><button class="btn btn-secondary">Buscar</button></div>
    </form>
</section>

<section class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead><tr><th>Refugio</th><th>Contacto</th><th>Dirección</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                <?php if (empty($refugios)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No hay refugios con esos filtros.</td></tr>
                <?php else: foreach ($refugios as $r): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($r['nombre_refugio']); ?></strong></td>
                        <td><?php echo htmlspecialchars($r['correo']); ?><br><small class="text-muted"><?php echo htmlspecialchars($r['telefono'] ?: '—'); ?></small></td>
                        <td><?php echo htmlspecialchars($r['direccion'] ?: '—'); ?></td>
                        <td><span class="badge" style="<?php echo $estadoBadge[$r['estado']] ?? ''; ?>padding:.3rem .7rem;border-radius:99px;font-size:.78rem;"><?php echo $r['estado']; ?></span></td>
                        <td class="text-end" style="white-space:nowrap">
                            <?php if ($r['estado'] !== 'APROBADO'): ?>
                                <form action="php/admin_refugio_estado.php" method="POST" style="display:inline">
                                    <input type="hidden" name="id_refugio" value="<?php echo (int) $r['id_refugio']; ?>">
                                    <input type="hidden" name="accion" value="aprobar">
                                    <button type="submit" class="btn btn-sm btn-success-outline me-1">Aprobar</button>
                                </form>
                            <?php endif; ?>
                            <?php if ($r['estado'] !== 'RECHAZADO'): ?>
                                <form action="php/admin_refugio_estado.php" method="POST" style="display:inline" onsubmit="return confirm('¿Rechazar este refugio?');">
                                    <input type="hidden" name="id_refugio" value="<?php echo (int) $r['id_refugio']; ?>">
                                    <input type="hidden" name="accion" value="rechazar">
                                    <button type="submit" class="btn btn-sm btn-danger-outline">Rechazar</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</section>

<div class="modal" id="refugioModal">
    <div class="modal-dialog">
        <form action="php/admin_refugio_crear.php" method="POST">
            <div class="modal-header"><h2>Nuevo refugio</h2><button type="button" class="close-modal" data-close-modal>&times;</button></div>
            <div class="modal-body form-grid">
                <div><label class="form-label">Nombre del refugio</label><input class="form-control" name="nombre_refugio" required></div>
                <div><label class="form-label">Correo (cuenta de acceso)</label><input class="form-control" name="correo" type="email" required></div>
                <div><label class="form-label">Contraseña temporal</label><input class="form-control" name="contrasena" type="text" minlength="6" required></div>
                <div><label class="form-label">Teléfono</label><input class="form-control" name="telefono"></div>
                <div><label class="form-label">Cédula jurídica</label><input class="form-control" name="cedula_juridica"></div>
                <div><label class="form-label">Dirección</label><input class="form-control" name="direccion"></div>
                <div style="grid-column:1/-1"><label class="form-label">Descripción</label><textarea class="form-control" name="descripcion" rows="2"></textarea></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-close-modal>Cancelar</button><button class="btn btn-primary">Crear refugio</button></div>
        </form>
    </div>
</div>
