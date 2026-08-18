<div class="admin-header"><div><h1>Gestión de roles</h1><p>PawsMatch trabaja con 3 roles fijos del sistema. Esta vista es de solo lectura.</p></div></div>

<div class="kpi-grid">
    <div class="kpi-card"><div><p>Roles del sistema</p><h3><?php echo count($roles); ?></h3></div></div>
    <div class="kpi-card"><div><p>Usuarios totales</p><h3><?php echo $totalUsuarios; ?></h3></div></div>
    <div class="kpi-card"><div><p>Administradores</p><h3><?php echo $totalAdmins; ?></h3></div></div>
</div>

<section class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead><tr><th>Rol</th><th>Descripción</th><th>Permisos del módulo</th><th>Usuarios</th></tr></thead>
            <tbody>
                <?php foreach ($roles as $r): ?>
                    <tr>
                        <td><span class="badge" style="background:#ede9fe;color:#5b21b6;padding:.3rem .7rem;border-radius:99px;font-size:.78rem;"><?php echo htmlspecialchars($r['nombre']); ?></span></td>
                        <td><?php echo htmlspecialchars($r['descripcion']); ?></td>
                        <td><small class="text-muted"><?php echo htmlspecialchars($permisos[$r['nombre']] ?? ''); ?></small></td>
                        <td><?php echo (int) $r['total_usuarios']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <p class="text-muted" style="margin-top:1rem;font-size:.85rem;">
        Por diseño del sistema, los roles no se crean ni eliminan desde esta pantalla: PawsMatch mantiene únicamente
        ADMIN_GENERAL, REFUGIO y ADOPTANTE.
    </p>
</section>
