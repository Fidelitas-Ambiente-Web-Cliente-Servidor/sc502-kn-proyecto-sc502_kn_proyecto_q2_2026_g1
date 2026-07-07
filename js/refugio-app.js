// =====================================================
// PawsMatch — Refugio module page controllers
// Demo shelter: Refugio San Roque (id: 1)
// =====================================================

const REFUGIO_ID = 1; // swap to current session user's refugioId in Spring Boot

function kpi(id, valor) {
    const el = document.getElementById(id);
    if (el) el.textContent = valor;
}

// ── Dashboard ─────────────────────────────────────────────────
function initRefugioDashboard() {
    const e = PawsMatchService.estadisticasRefugio(REFUGIO_ID);
    kpi('kpiTotal',       e.totalMascotas);
    kpi('kpiDisponibles', e.mascotasDisponibles);
    kpi('kpiAdoptadas',   e.mascotasAdoptadas);
    kpi('kpiPendientes',  e.solicitudesPendientes);

    // Latest requests table
    const tbody = document.getElementById('ultimasSolicitudes');
    if (tbody) {
        const rows = PawsMatchService.solicitudesPorRefugio(REFUGIO_ID).slice(0, 5);
        tbody.innerHTML = rows.length
            ? rows.map(s => `
                <tr>
                    <td><strong>${s.mascota}</strong><br><small class="text-muted">${s.especieMascota}</small></td>
                    <td>${s.adoptante}</td>
                    <td>${s.fecha}</td>
                    <td><span class="badge ${PawsMatchUtils.badgeEstado(s.estado)}">${s.estado}</span></td>
                    <td class="text-end">
                        <a href="refugio-solicitudes.html" class="btn btn-sm btn-outline">Ver todas</a>
                    </td>
                </tr>`).join('')
            : `<tr><td colspan="5" class="text-center text-muted py-4">No hay solicitudes registradas.</td></tr>`;
    }
}

// ── Mascotas ──────────────────────────────────────────────────
function initRefugioMascotas() {
    const e = PawsMatchService.estadisticasRefugio(REFUGIO_ID);
    kpi('kpiTotal',      e.totalMascotas);
    kpi('kpiDisponibles', e.mascotasDisponibles);
    kpi('kpiPublicadas',  e.mascotasPublicadas);
    kpi('kpiAdoptadas',   e.mascotasAdoptadas);

    PawsMatchUtils.configurarTabla({
        datos: () => PawsMatchService.mascotasPorRefugio(REFUGIO_ID),
        tbody: document.getElementById('mascotasTbody'),
        buscarInput: document.getElementById('buscarMascota'),
        contador: document.getElementById('contadorMascotas'),
        paginacion: document.getElementById('paginacionMascotas'),
        filasPorPagina: 6,
        etiqueta: 'mascotas',
        filtros: [
            { id: 'filtroEspecie', campo: 'especie' },
            { id: 'filtroEstado',  campo: 'estado'  }
        ],
        renderFila: m => `
            <tr>
                <td>
                    <div class="d-flex align-items-center" style="gap:.8rem">
                        <img src="${m.foto || 'https://via.placeholder.com/48'}" width="48" height="48"
                             style="border-radius:12px;object-fit:cover;flex-shrink:0" alt="${m.nombre}">
                        <div>
                            <strong>${m.nombre}</strong><br>
                            <small class="text-muted">${m.raza || m.especie}</small>
                        </div>
                    </div>
                </td>
                <td>${m.especie}</td>
                <td>${m.edad}</td>
                <td>${m.sexo}</td>
                <td><span class="badge ${PawsMatchUtils.badgeEstado(m.estado)}">${m.estado}</span></td>
                <td>
                    <span class="badge ${m.publicada ? 'bg-success' : 'bg-secondary'}">
                        ${m.publicada ? 'Publicada' : 'Oculta'}
                    </span>
                </td>
                <td class="text-end" style="white-space:nowrap">
                    <button class="btn btn-sm btn-outline me-1"
                            data-action="editar-mascota" data-id="${m.id}" title="Editar">✏️</button>
                    <button class="btn btn-sm btn-outline me-1"
                            data-action="publicar-mascota" data-id="${m.id}" title="${m.publicada ? 'Despublicar' : 'Publicar'}">
                        ${m.publicada ? '🔒' : '📢'}
                    </button>
                    <button class="btn btn-sm btn-danger-outline"
                            data-action="eliminar-mascota" data-id="${m.id}" title="Eliminar">🗑️</button>
                </td>
            </tr>`
    });

    // Submit — add mascota
    document.getElementById('formMascota')?.addEventListener('submit', e => {
        e.preventDefault();
        const id = document.getElementById('mascotaId').value;
        const datos = {
            nombre:      document.getElementById('mNombre').value,
            especie:     document.getElementById('mEspecie').value,
            raza:        document.getElementById('mRaza').value,
            edad:        document.getElementById('mEdad').value,
            sexo:        document.getElementById('mSexo').value,
            descripcion: document.getElementById('mDescripcion').value,
            foto:        document.getElementById('mFoto').value,
            refugioId:   REFUGIO_ID
        };
        if (id) {
            PawsMatchService.actualizarMascota(Number(id), datos);
        } else {
            PawsMatchService.crearMascota(datos);
        }
        e.target.reset();
        document.getElementById('mascotaId').value = '';
        document.getElementById('mascotaModalTitle').textContent = 'Nueva mascota';
        PawsMatchUtils.cerrarModal('mascotaModal');
        initRefugioMascotas();
    }, { once: true });
}

// ── Solicitudes ───────────────────────────────────────────────
function initRefugioSolicitudes() {
    const e = PawsMatchService.estadisticasRefugio(REFUGIO_ID);
    kpi('kpiPendientes',  e.solicitudesPendientes);
    kpi('kpiAprobadas',   e.solicitudesAprobadas);
    kpi('kpiRechazadas',  e.solicitudesRechazadas);
    kpi('kpiTotal',       e.solicitudesPendientes + e.solicitudesAprobadas + e.solicitudesRechazadas);

    // Active requests
    PawsMatchUtils.configurarTabla({
        datos: () => PawsMatchService.solicitudesPorRefugio(REFUGIO_ID)
                        .filter(s => s.estado !== 'Aprobada'),
        tbody: document.getElementById('solicitudesTbody'),
        buscarInput: document.getElementById('buscarSolicitud'),
        contador: document.getElementById('contadorSolicitudes'),
        paginacion: document.getElementById('paginacionSolicitudes'),
        filasPorPagina: 6,
        etiqueta: 'solicitudes',
        filtros: [{ id: 'filtroEstadoSol', campo: 'estado' }],
        renderFila: s => `
            <tr>
                <td>
                    <div class="d-flex align-items-center" style="gap:.8rem">
                        <img src="${s.fotoMascota || 'https://via.placeholder.com/40'}" width="40" height="40"
                             style="border-radius:10px;object-fit:cover;flex-shrink:0" alt="${s.mascota}">
                        <div><strong>${s.mascota}</strong><br>
                             <small class="text-muted">${s.especieMascota}</small></div>
                    </div>
                </td>
                <td>${s.adoptante}</td>
                <td>${s.fecha}</td>
                <td><span class="badge ${PawsMatchUtils.badgeEstado(s.estado)}">${s.estado}</span></td>
                <td>${s.motivoRechazo ? `<small class="text-muted">${s.motivoRechazo}</small>` : '—'}</td>
                <td class="text-end" style="white-space:nowrap">
                    ${s.estado !== 'Rechazada' ? `
                    <button class="btn btn-sm btn-success-outline me-1"
                            data-action="aprobar-solicitud" data-id="${s.id}" title="Aprobar">✅ Aprobar</button>
                    <button class="btn btn-sm btn-danger-outline"
                            data-action="rechazar-solicitud" data-id="${s.id}" title="Rechazar">❌ Rechazar</button>` : '—'}
                </td>
            </tr>`
    });

    // Historial
    const histTbody = document.getElementById('historialTbody');
    if (histTbody) {
        const hist = PawsMatchService.historialAdopciones(REFUGIO_ID);
        histTbody.innerHTML = hist.length
            ? hist.map(s => `
                <tr>
                    <td><strong>${s.mascota}</strong></td>
                    <td>${s.adoptante}</td>
                    <td>${s.fecha}</td>
                    <td><span class="badge bg-success">Aprobada</span></td>
                </tr>`).join('')
            : `<tr><td colspan="4" class="text-center text-muted py-4">Sin adopciones registradas aún.</td></tr>`;
    }
}

// ── Global action handler ─────────────────────────────────────
document.addEventListener('click', e => {
    const btn = e.target.closest('[data-action]');
    if (!btn) return;
    const id = Number(btn.dataset.id);

    if (btn.dataset.action === 'publicar-mascota') {
        PawsMatchService.togglePublicarMascota(id);
        location.reload();
    }

    if (btn.dataset.action === 'eliminar-mascota') {
        if (confirm('¿Eliminar esta mascota? Esta acción no se puede deshacer.')) {
            PawsMatchService.eliminarMascota(id);
            location.reload();
        }
    }

    if (btn.dataset.action === 'editar-mascota') {
        const m = PawsMatchService.mascotasPorRefugio(REFUGIO_ID).find(x => x.id === id);
        if (!m) return;
        document.getElementById('mascotaId').value       = m.id;
        document.getElementById('mNombre').value         = m.nombre;
        document.getElementById('mEspecie').value        = m.especie;
        document.getElementById('mRaza').value           = m.raza;
        document.getElementById('mEdad').value           = m.edad;
        document.getElementById('mSexo').value           = m.sexo;
        document.getElementById('mDescripcion').value    = m.descripcion;
        document.getElementById('mFoto').value           = m.foto;
        document.getElementById('mascotaModalTitle').textContent = 'Editar mascota';
        window.abrirModal?.('mascotaModal');
    }

    if (btn.dataset.action === 'aprobar-solicitud') {
        if (confirm('¿Aprobar esta solicitud de adopción?')) {
            PawsMatchService.aprobarSolicitud(id);
            location.reload();
        }
    }

    if (btn.dataset.action === 'rechazar-solicitud') {
        const motivo = prompt('Motivo de rechazo (opcional):') || '';
        PawsMatchService.rechazarSolicitud(id, motivo);
        location.reload();
    }
});

// Modal open/close (mirrors admin-app.js pattern)
window.abrirModal = function(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('show');
};

document.addEventListener('click', function(e) {
    const openBtn = e.target.closest('[data-open-modal]');
    if (openBtn) {
        document.getElementById('mascotaId').value = '';
        document.getElementById('mascotaModalTitle').textContent = 'Nueva mascota';
        abrirModal(openBtn.dataset.openModal);
    }
    const closeBtn = e.target.closest('[data-close-modal]');
    if (closeBtn) closeBtn.closest('.modal')?.classList.remove('show');
    if (e.target.classList?.contains('modal')) e.target.classList.remove('show');
});

// ── Page dispatcher ───────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const page = document.body.dataset.page;
    if (page === 'refugio-dashboard')   initRefugioDashboard();
    if (page === 'refugio-mascotas')    initRefugioMascotas();
    if (page === 'refugio-solicitudes') initRefugioSolicitudes();
});
