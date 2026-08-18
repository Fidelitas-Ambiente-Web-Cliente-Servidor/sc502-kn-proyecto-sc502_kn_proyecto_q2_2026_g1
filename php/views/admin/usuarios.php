<div class="admin-header"><div><h1>Gestión de usuarios</h1><p>Administre los usuarios registrados y sus roles.</p></div><div class="admin-actions"><button class="btn btn-primary" data-open-modal="usuarioModal">Nuevo usuario</button><a class="btn btn-secondary" href="php/exportar_csv.php?tipo=usuarios">Exportar</a></div></div>

<div class="kpi-grid">
    <div class="kpi-card"><div><p>Total usuarios</p><h3><?php echo $totalTodos; ?></h3></div></div>
    <div class="kpi-card"><div><p>Activos</p><h3><?php echo $totalActivos; ?></h3></div></div>
    <div class="kpi-card"><div><p>Inactivos</p><h3><?php echo $totalInactivos; ?></h3></div></div>
    <div class="kpi-card"><div><p>Administradores</p><h3><?php echo $totalAdmins; ?></h3></div></div>
</div>

<section class="admin-card">
    <form method="GET" class="admin-filters">
        <div><label class="form-label">Buscar</label><input type="text" name="buscar" class="form-control" placeholder="Nombre o correo" value="<?php echo htmlspecialchars($buscar); ?>"></div>
        <div><label class="form-label">Rol</label>
            <select name="rol" class="form-select" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php foreach (["ADMIN_GENERAL", "REFUGIO", "ADOPTANTE"] as $r): ?>
                    <option value="<?php echo $r; ?>" <?php echo $filtroRol === $r ? 'selected' : ''; ?>><?php echo $r; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div><label class="form-label">Estado</label>
            <select name="estado" class="form-select" onchange="this.form.submit()">
                <option value="">Todos</option>
                <option value="ACTIVO" <?php echo $filtroEstado === 'ACTIVO' ? 'selected' : ''; ?>>Activo</option>
                <option value="INACTIVO" <?php echo $filtroEstado === 'INACTIVO' ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>
        <div style="align-self:end"><button class="btn btn-secondary">Buscar</button></div>
    </form>
</section>

<section class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead><tr><th>Usuario</th><th>Rol</th><th>Registro</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No se encontraron usuarios con esos filtros.</td></tr>
                <?php else: foreach ($usuarios as $u): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($u['nombre'] . ' ' . $u['apellidos']); ?></strong><br><small class="text-muted"><?php echo htmlspecialchars($u['correo']); ?></small></td>
                        <td><span class="badge" style="<?php echo $rolBadge[$u['rol']] ?? ''; ?>padding:.3rem .7rem;border-radius:99px;font-size:.78rem;"><?php echo $u['rol']; ?></span></td>
                        <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($u['fecha_registro']))); ?></td>
                        <td><span class="badge" style="<?php echo $u['estado'] === 'ACTIVO' ? 'background:#dcfce7;color:#166534;' : 'background:#fee2e2;color:#991b1b;'; ?>padding:.3rem .7rem;border-radius:99px;font-size:.78rem;"><?php echo $u['estado']; ?></span></td>
                        <td class="text-end">
                            <?php if ((int) $u['id_usuario'] !== (int) $_SESSION['id_usuario']): ?>
                                <form action="php/admin_usuario_estado.php" method="POST" style="display:inline" onsubmit="return confirm('¿Cambiar el estado de este usuario?');">
                                    <input type="hidden" name="id_usuario" value="<?php echo (int) $u['id_usuario']; ?>">
                                    <button type="submit" class="btn btn-sm <?php echo $u['estado'] === 'ACTIVO' ? 'btn-danger-outline' : 'btn-success-outline'; ?>">
                                        <?php echo $u['estado'] === 'ACTIVO' ? 'Bloquear' : 'Activar'; ?>
                                    </button>
                                </form>
                            <?php else: ?>
                                <small class="text-muted">Tu cuenta</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</section>

<div class="modal" id="usuarioModal">
    <div class="modal-dialog">
        <form action="php/admin_usuario_guardar.php" method="POST">
            <div class="modal-header"><h2>Nuevo usuario</h2><button type="button" class="close-modal" data-close-modal>&times;</button></div>
            <div class="modal-body form-grid">
                <div><label class="form-label">Nombre</label><input class="form-control" name="nombre" required></div>
                <div><label class="form-label">Apellidos</label><input class="form-control" name="apellidos" required></div>
                <div><label class="form-label">Correo</label><input class="form-control" name="correo" type="email" required></div>
                <div><label class="form-label">Contraseña temporal</label><input class="form-control" name="contrasena" type="text" minlength="6" required></div>
                <div style="grid-column:1/-1"><label class="form-label">Rol</label>
                    <select class="form-select" name="rol" required>
                        <option value="ADOPTANTE">ADOPTANTE</option>
                        <option value="ADMIN_GENERAL">ADMIN_GENERAL</option>
                    </select>
                    <small class="text-muted">Las cuentas de refugio se crean desde el módulo "Refugios".</small>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-close-modal>Cancelar</button><button class="btn btn-primary">Guardar</button></div>
        </form>
    </div>
</div>
