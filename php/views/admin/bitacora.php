<div class="admin-header"><div><h1>Bitácora</h1><p>Historial de acciones realizadas en el sistema (últimos 200 registros).</p></div></div>

<section class="admin-card">
    <form method="GET" class="admin-filters">
        <div><label class="form-label">Buscar</label><input type="text" name="buscar" class="form-control" placeholder="Usuario, acción o descripción" value="<?php echo htmlspecialchars($buscar); ?>"></div>
        <div style="align-self:end"><button class="btn btn-secondary">Buscar</button></div>
    </form>
</section>

<section class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead><tr><th>Fecha</th><th>Usuario</th><th>Acción</th><th>Detalle</th><th>IP</th></tr></thead>
            <tbody>
                <?php if (empty($registros)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Sin registros de actividad todavía.</td></tr>
                <?php else: foreach ($registros as $b): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($b['fecha']))); ?></td>
                        <td><?php echo htmlspecialchars(trim(($b['usuario_nombre'] ?? '') . ' ' . ($b['usuario_apellidos'] ?? '')) ?: 'Sistema'); ?></td>
                        <td><?php echo htmlspecialchars($b['accion']); ?></td>
                        <td><small class="text-muted"><?php echo htmlspecialchars($b['descripcion'] ?: '—'); ?></small></td>
                        <td><small class="text-muted"><?php echo htmlspecialchars($b['ip'] ?: '—'); ?></small></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</section>
