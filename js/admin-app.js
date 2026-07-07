// =====================================================
// PawMatch - Renderizado de módulos admin
// =====================================================

function kpi(id, valor) {
    const el = document.getElementById(id);
    if (el) el.textContent = valor;
}

function opcionesRoles() {
    return HuellitasService.roles().map(r => `<option value="${r.id}">${r.nombre}</option>`).join('');
}

function opcionesUsuariosRefugio() {
    return HuellitasService.usuarios()
        .filter(u => u.rol === 'REFUGIO')
        .map(u => `<option value="${u.id}">${u.nombre}</option>`).join('');
}

function initDashboard() {
    const e = HuellitasService.estadisticas();
    kpi('totalUsers', e.totalUsuarios);
    kpi('totalShelters', e.totalRefugios);
    kpi('monthlyAdoptions', e.adopcionesAprobadas);
    kpi('pendingAlerts', e.notificacionesNoLeidas);

    const recent = document.getElementById('recentRequests');
    if (recent) {
        recent.innerHTML = HuellitasService.solicitudes().slice(0, 4).map(s => `
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div><h6 class="mb-1">${s.adoptante} solicita a ${s.mascota}</h6><small class="text-muted">${s.fecha}</small></div>
                    <span class="badge ${HuellitasUtils.badgeEstado(s.estado)}">${s.estado}</span>
                </div>
            </div>`).join('');
    }

    const alerts = document.getElementById('systemAlerts');
    if (alerts) {
        alerts.innerHTML = HuellitasService.notificaciones().slice(0, 4).map(n => `
            <div class="list-group-item">
                <div class="d-flex align-items-center">
                    <i class="fas fa-bell text-primary me-3 fa-lg"></i>
                    <div><h6 class="mb-0">${n.titulo}</h6><small class="text-muted">${n.mensaje}</small></div>
                </div>
            </div>`).join('');
    }
}

function initUsuarios() {
    const e = HuellitasService.estadisticas();
    kpi('usuariosTotal', e.totalUsuarios);
    kpi('usuariosActivos', e.usuariosActivos);
    kpi('usuariosPendientes', e.usuariosPendientes);
    kpi('usuariosBloqueados', e.usuariosBloqueados);

    const rolSelect = document.getElementById('usuarioRol');
    if (rolSelect) rolSelect.innerHTML = opcionesRoles();

    const tabla = HuellitasUtils.configurarTabla({
        datos: HuellitasService.usuarios,
        tbody: document.getElementById('usuariosTbody'),
        buscarInput: document.getElementById('buscarUsuario'),
        contador: document.getElementById('contadorUsuarios'),
        paginacion: document.getElementById('paginacionUsuarios'),
        filasPorPagina: 5,
        etiqueta: 'usuarios',
        filtros: [{ id: 'filtroRol', campo: 'rol' }, { id: 'filtroEstado', campo: 'estado' }],
        renderFila: u => `
            <tr>
                <td><strong>${u.nombre}</strong><br><small class="text-muted">${u.correo}</small></td>
                <td><span class="badge ${HuellitasUtils.badgeRol(u.rol)}">${u.rol}</span></td>
                <td>${u.fechaRegistro}</td>
                <td><span class="badge ${HuellitasUtils.badgeEstado(u.estado)}">${u.estado}</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary me-1" title="Ver"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-sm btn-outline-danger" data-action="bloquear-usuario" data-id="${u.id}" title="Bloquear"><i class="fas fa-ban"></i></button>
                </td>
            </tr>`
    });

    document.getElementById('formUsuario')?.addEventListener('submit', e => {
        e.preventDefault();
        HuellitasService.crearUsuario({
            nombre: document.getElementById('usuarioNombre').value,
            correo: document.getElementById('usuarioCorreo').value,
            rolId: document.getElementById('usuarioRol').value,
            estado: document.getElementById('usuarioEstado').value
        });
        e.target.reset();
        HuellitasUtils.cerrarModal('usuarioModal');
        initUsuarios();
    }, { once: true });

    document.getElementById('btnExportarUsuarios')?.addEventListener('click', () => HuellitasUtils.exportarCSV('usuarios.csv', HuellitasService.usuarios()));
}

function initRefugios() {
    const e = HuellitasService.estadisticas();
    kpi('refugiosTotal', e.totalRefugios);
    kpi('refugiosAprobados', e.refugiosAprobados);
    kpi('refugiosPendientes', e.refugiosPendientes);
    kpi('refugiosSuspendidos', HuellitasService.refugios().filter(r => r.estado === 'Suspendido').length);

    const adminSelect = document.getElementById('refugioAdmin');
    if (adminSelect) adminSelect.innerHTML = '<option value="">Sin asignar</option>' + opcionesUsuariosRefugio();

    const tabla = HuellitasUtils.configurarTabla({
        datos: HuellitasService.refugios,
        tbody: document.getElementById('refugiosTbody'),
        buscarInput: document.getElementById('buscarRefugio'),
        contador: document.getElementById('contadorRefugios'),
        paginacion: document.getElementById('paginacionRefugios'),
        filasPorPagina: 5,
        etiqueta: 'refugios',
        filtros: [{ id: 'filtroProvincia', campo: 'provincia' }, { id: 'filtroEstado', campo: 'estado' }],
        renderFila: r => `
            <tr>
                <td><strong>${r.nombre}</strong><br><small class="text-muted">${r.correo}</small></td>
                <td>${r.administrador}</td>
                <td>${r.provincia}</td>
                <td>${r.telefono}</td>
                <td><span class="badge ${HuellitasUtils.badgeEstado(r.estado)}">${r.estado}</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-success me-1" data-action="aprobar-refugio" data-id="${r.id}" title="Aprobar"><i class="fas fa-check"></i></button>
                    <button class="btn btn-sm btn-outline-danger" data-action="suspender-refugio" data-id="${r.id}" title="Suspender"><i class="fas fa-ban"></i></button>
                </td>
            </tr>`
    });

    document.getElementById('formRefugio')?.addEventListener('submit', e => {
        e.preventDefault();
        HuellitasService.crearRefugio({
            nombre: document.getElementById('refugioNombre').value,
            correo: document.getElementById('refugioCorreo').value,
            telefono: document.getElementById('refugioTelefono').value,
            provincia: document.getElementById('refugioProvincia').value,
            administradorUsuarioId: document.getElementById('refugioAdmin').value,
            estado: document.getElementById('refugioEstado').value
        });
        e.target.reset();
        HuellitasUtils.cerrarModal('refugioModal');
        initRefugios();
    }, { once: true });

    document.getElementById('btnExportarRefugios')?.addEventListener('click', () => HuellitasUtils.exportarCSV('refugios.csv', HuellitasService.refugios()));
}

function initRoles() {
    const roles = HuellitasService.conteoUsuariosPorRol();
    kpi('rolesTotal', roles.length);
    kpi('rolesActivos', roles.filter(r => r.estado === 'Activo').length);
    kpi('rolesUsuarios', HuellitasService.usuarios().length);
    kpi('rolesAdmin', roles.find(r => r.nombre === 'ADMIN_GENERAL')?.totalUsuarios || 0);

    const tbody = document.getElementById('rolesTbody');
    if (tbody) tbody.innerHTML = roles.map(r => `
        <tr>
            <td><span class="badge ${HuellitasUtils.badgeRol(r.nombre)}">${r.nombre}</span></td>
            <td>${r.descripcion}</td>
            <td>${r.permisos.join(', ')}</td>
            <td>${r.totalUsuarios}</td>
            <td><span class="badge ${HuellitasUtils.badgeEstado(r.estado)}">${r.estado}</span></td>
            <td class="text-end"><button class="btn btn-sm btn-outline-warning" title="Editar"><i class="fas fa-pen"></i></button></td>
        </tr>`).join('');

    document.getElementById('formRol')?.addEventListener('submit', e => {
        e.preventDefault();
        HuellitasService.crearRol({
            nombre: document.getElementById('rolNombre').value,
            descripcion: document.getElementById('rolDescripcion').value,
            estado: document.getElementById('rolEstado').value,
            permisos: document.getElementById('rolPermisos').value.split(',').map(p => p.trim()).filter(Boolean)
        });
        e.target.reset();
        HuellitasUtils.cerrarModal('rolModal');
        initRoles();
    }, { once: true });

    document.getElementById('btnExportarRoles')?.addEventListener('click', () => HuellitasUtils.exportarCSV('roles.csv', HuellitasService.conteoUsuariosPorRol()));
}

function initEstadisticas() {
    const e = HuellitasService.estadisticas();
    kpi('statUsuarios', e.totalUsuarios);
    kpi('statRefugios', e.totalRefugios);
    kpi('statMascotas', e.totalMascotas);
    kpi('statSolicitudes', e.solicitudesPendientes);

    const resumen = document.getElementById('estadisticasResumen');
    if (resumen) resumen.innerHTML = `
        <tr><td>Usuarios activos</td><td>${e.usuariosActivos}</td></tr>
        <tr><td>Refugios aprobados</td><td>${e.refugiosAprobados}</td></tr>
        <tr><td>Mascotas disponibles</td><td>${e.mascotasDisponibles}</td></tr>
        <tr><td>Adopciones aprobadas</td><td>${e.adopcionesAprobadas}</td></tr>`;
}

function initReportes() {
    document.querySelectorAll('[data-reporte]').forEach(btn => {
        btn.addEventListener('click', () => {
            const tipo = btn.dataset.reporte;
            const mapa = {
                usuarios: HuellitasService.usuarios(),
                refugios: HuellitasService.refugios(),
                roles: HuellitasService.conteoUsuariosPorRol(),
                bitacora: HuellitasService.bitacora(),
                notificaciones: HuellitasService.notificaciones()
            };
            HuellitasUtils.exportarCSV(`${tipo}.csv`, mapa[tipo] || []);
        });
    });
}

function initBitacora() {
    const tbody = document.getElementById('bitacoraTbody');
    HuellitasUtils.configurarTabla({
        datos: HuellitasService.bitacora,
        tbody,
        buscarInput: document.getElementById('buscarBitacora'),
        contador: document.getElementById('contadorBitacora'),
        paginacion: document.getElementById('paginacionBitacora'),
        filasPorPagina: 5,
        etiqueta: 'registros',
        filtros: [{ id: 'filtroModulo', campo: 'modulo' }],
        renderFila: b => `<tr><td>${b.fecha}</td><td>${b.usuario}</td><td>${b.modulo}</td><td>${b.accion}</td><td>${b.ip}</td></tr>`
    });
}

function initNotificaciones() {
    const tbody = document.getElementById('notificacionesTbody');
    if (tbody) tbody.innerHTML = HuellitasService.notificaciones().map(n => `
        <tr>
            <td><strong>${n.titulo}</strong><br><small class="text-muted">${n.mensaje}</small></td>
            <td><span class="badge bg-${n.tipo === 'warning' ? 'warning text-dark' : n.tipo}">${n.tipo}</span></td>
            <td>${n.fecha}</td>
            <td><span class="badge ${n.leida ? 'bg-secondary' : 'bg-primary'}">${n.leida ? 'Leída' : 'Nueva'}</span></td>
            <td class="text-end"><button class="btn btn-sm btn-outline-primary" data-action="leer-notificacion" data-id="${n.id}">Marcar leída</button></td>
        </tr>`).join('');

    document.getElementById('formNotificacion')?.addEventListener('submit', e => {
        e.preventDefault();
        HuellitasService.crearNotificacion({
            titulo: document.getElementById('notificacionTitulo').value,
            mensaje: document.getElementById('notificacionMensaje').value,
            tipo: document.getElementById('notificacionTipo').value
        });
        e.target.reset();
        HuellitasUtils.cerrarModal('notificacionModal');
        initNotificaciones();
    }, { once: true });
}

function initConfiguracion() {
    const c = HuellitasService.configuracion();
    Object.entries(c).forEach(([key, value]) => {
        const input = document.getElementById(key);
        if (!input) return;
        if (input.type === 'checkbox') input.checked = Boolean(value);
        else input.value = value;
    });
    document.getElementById('formConfiguracion')?.addEventListener('submit', e => {
        e.preventDefault();
        HuellitasService.guardarConfiguracion({
            nombreSistema: document.getElementById('nombreSistema').value,
            correoSoporte: document.getElementById('correoSoporte').value,
            tiempoSesionMinutos: Number(document.getElementById('tiempoSesionMinutos').value),
            aprobacionRefugiosManual: document.getElementById('aprobacionRefugiosManual').checked,
            notificacionesCorreo: document.getElementById('notificacionesCorreo').checked
        });
        alert('Configuración guardada correctamente.');
    });
}

document.addEventListener('click', e => {
    const btn = e.target.closest('[data-action]');
    if (!btn) return;
    const id = btn.dataset.id;
    if (btn.dataset.action === 'bloquear-usuario') HuellitasService.actualizarEstadoUsuario(id, 'Bloqueado');
    if (btn.dataset.action === 'aprobar-refugio') HuellitasService.actualizarEstadoRefugio(id, 'Aprobado');
    if (btn.dataset.action === 'suspender-refugio') HuellitasService.actualizarEstadoRefugio(id, 'Suspendido');
    if (btn.dataset.action === 'leer-notificacion') HuellitasService.marcarNotificacionLeida(id);
    location.reload();
});

document.addEventListener('DOMContentLoaded', () => {
    const page = document.body.dataset.page;
    if (page === 'dashboard') initDashboard();
    if (page === 'usuarios') initUsuarios();
    if (page === 'refugios') initRefugios();
    if (page === 'roles') initRoles();
    if (page === 'estadisticas') initEstadisticas();
    if (page === 'reportes') initReportes();
    if (page === 'bitacora') initBitacora();
    if (page === 'notificaciones') initNotificaciones();
    if (page === 'configuracion') initConfiguracion();
});

// Modales simples sin Bootstrap
window.abrirModal = function(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('show');
};

document.addEventListener('click', function(e) {
    const openBtn = e.target.closest('[data-open-modal]');
    if (openBtn) abrirModal(openBtn.dataset.openModal);

    const closeBtn = e.target.closest('[data-close-modal]');
    if (closeBtn) closeBtn.closest('.modal')?.classList.remove('show');

    if (e.target.classList?.contains('modal')) e.target.classList.remove('show');
});
