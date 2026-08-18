<div class="admin-header"><div><h1>Notificaciones</h1><p>Avisos generados automáticamente por eventos del sistema (nuevos registros, refugios, etc.).</p></div></div>

<section class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead><tr><th>Notificación</th><th>Tipo</th><th>Fecha</th><th>Estado</th><th class="text-end">Acción</th></tr></thead>
            <tbody>
                <?php if (empty($notificaciones)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No tienes notificaciones. Se generan automáticamente cuando ocurren eventos como un nuevo registro de adoptante.</td></tr>
                <?php else: foreach ($notificaciones as $n): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($n['titulo']); ?></strong><br><small class="text-muted"><?php echo htmlspecialchars($n['mensaje']); ?></small></td>
                        <td><?php echo htmlspecialchars($n['tipo']); ?></td>
                        <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($n['fecha_creacion']))); ?></td>
                        <td><span class="badge" style="<?php echo $n['leida'] ? 'background:#f3f4f6;color:#4b5563;' : 'background:#dbeafe;color:#1e40af;'; ?>padding:.3rem .7rem;border-radius:99px;font-size:.78rem;"><?php echo $n['leida'] ? 'Leída' : 'Nueva'; ?></span></td>
                        <td class="text-end">
                            <?php if (!$n['leida']): ?>
                                <form action="php/admin_notificacion_leida.php" method="POST">
                                    <input type="hidden" name="id_notificacion" value="<?php echo (int) $n['id_notificacion']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline">Marcar leída</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</section>
