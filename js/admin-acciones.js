// =========================================================
// PawsMatch - Acciones demo para módulos admin
// Funciones: crear registros en tabla, exportar CSV y acciones básicas.
// Nota: Esto es frontend temporal. Luego se reemplaza por Spring Boot.
// =========================================================

document.addEventListener('DOMContentLoaded', () => {
    configurarExportacion();
    configurarModalesCrear();
    configurarAccionesTabla();
});

function limpiarTexto(texto) {
    return (texto || '').toString().trim();
}

function crearAvatar(nombre, fondo = '6c5ce7') {
    const nombreUrl = encodeURIComponent(nombre || 'Usuario').replaceAll('%20', '+');
    return `https://ui-avatars.com/api/?name=${nombreUrl}&background=${fondo}&color=fff`;
}

function obtenerTablaPrincipal() {
    const tablas = Array.from(document.querySelectorAll('table'));
    return tablas[tablas.length - 1] || null;
}

function configurarExportacion() {
    const botones = Array.from(document.querySelectorAll('button'))
        .filter(btn => btn.textContent.toLowerCase().includes('exportar'));

    botones.forEach(btn => {
        btn.addEventListener('click', () => exportarTablaCSV());
    });
}

function exportarTablaCSV() {
    const tabla = obtenerTablaPrincipal();
    if (!tabla) {
        alert('No se encontró una tabla para exportar.');
        return;
    }

    const filas = Array.from(tabla.querySelectorAll('tr'));
    const csv = filas.map(fila => {
        const celdas = Array.from(fila.querySelectorAll('th, td'));
        return celdas
            .slice(0, -1) // omite columna Acciones
            .map(celda => `"${limpiarTexto(celda.innerText).replaceAll('"', '""')}"`)
            .join(',');
    }).join('\n');

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const enlace = document.createElement('a');
    const nombrePagina = document.title.toLowerCase().replaceAll(' ', '-').replaceAll('á', 'a').replaceAll('é', 'e').replaceAll('í', 'i').replaceAll('ó', 'o').replaceAll('ú', 'u');

    enlace.href = url;
    enlace.download = `${nombrePagina}.csv`;
    enlace.click();

    URL.revokeObjectURL(url);
}

function configurarModalesCrear() {
    const btnUsuario = document.querySelector('#usuarioModal .btn-primary');
    if (btnUsuario) btnUsuario.addEventListener('click', guardarUsuarioDemo);

    const btnRefugio = document.querySelector('#refugioModal .btn-primary');
    if (btnRefugio) btnRefugio.addEventListener('click', guardarRefugioDemo);

    const btnRol = document.querySelector('#rolModal .btn-primary');
    if (btnRol) btnRol.addEventListener('click', guardarRolDemo);
}

function cerrarModal(modalId) {
    const modalElement = document.getElementById(modalId);
    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
    modal.hide();
}

function resetearFormulario(modalId) {
    const form = document.querySelector(`#${modalId} form`);
    if (form) form.reset();
}

function guardarUsuarioDemo() {
    const modal = document.getElementById('usuarioModal');
    const inputs = modal.querySelectorAll('input, select, textarea');

    const nombre = limpiarTexto(inputs[0].value);
    const correo = limpiarTexto(inputs[1].value);
    const telefono = limpiarTexto(inputs[2].value);
    const rol = limpiarTexto(inputs[3].value);

    if (!nombre || !correo || !telefono || rol === 'Seleccione un rol') {
        alert('Complete nombre, correo, teléfono y rol.');
        return;
    }

    const tbody = obtenerTablaPrincipal().querySelector('tbody');
    const fila = document.createElement('tr');
    const badgeRol = obtenerBadgeRol(rol);

    fila.innerHTML = `
        <td>
            <div class="d-flex align-items-center">
                <img src="${crearAvatar(nombre)}" class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                <div>
                    <h6 class="mb-0">${nombre}</h6>
                    <small class="text-muted">${correo}</small>
                </div>
            </div>
        </td>
        <td><span class="badge ${badgeRol}">${rol}</span></td>
        <td>${telefono}</td>
        <td>${new Date().toLocaleDateString('es-CR')}</td>
        <td><span class="badge bg-success">Activo</span></td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-1" title="Ver"><i class="fas fa-eye"></i></button>
            <button class="btn btn-sm btn-outline-warning me-1" title="Editar"><i class="fas fa-pen"></i></button>
            <button class="btn btn-sm btn-outline-danger" title="Bloquear"><i class="fas fa-ban"></i></button>
        </td>
    `;

    tbody.prepend(fila);
    resetearFormulario('usuarioModal');
    cerrarModal('usuarioModal');
    alert('Usuario agregado en la tabla demo.');
}

function guardarRefugioDemo() {
    const modal = document.getElementById('refugioModal');
    const campos = modal.querySelectorAll('input, select, textarea');

    const nombre = limpiarTexto(campos[0].value);
    const encargado = limpiarTexto(campos[1].value);
    const correo = limpiarTexto(campos[2].value);
    const provincia = limpiarTexto(campos[4].value);
    const capacidad = limpiarTexto(campos[5].value) || '0';

    if (!nombre || !encargado || !correo || provincia === 'Seleccione una provincia') {
        alert('Complete nombre del refugio, encargado, correo y provincia.');
        return;
    }

    const tbody = obtenerTablaPrincipal().querySelector('tbody');
    const fila = document.createElement('tr');

    fila.innerHTML = `
        <td>
            <div class="d-flex align-items-center">
                <img src="${crearAvatar(nombre, '00b894')}" class="rounded-circle me-3" width="40" height="40" alt="Refugio">
                <div>
                    <h6 class="mb-0">${nombre}</h6>
                    <small class="text-muted">${correo}</small>
                </div>
            </div>
        </td>
        <td>${encargado}</td>
        <td>${provincia}</td>
        <td><span class="badge bg-info text-dark">${capacidad} mascotas</span></td>
        <td><span class="badge bg-warning text-dark">Pendiente</span></td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-1" title="Ver"><i class="fas fa-eye"></i></button>
            <button class="btn btn-sm btn-outline-success me-1" title="Aprobar"><i class="fas fa-check"></i></button>
            <button class="btn btn-sm btn-outline-danger" title="Rechazar"><i class="fas fa-times"></i></button>
        </td>
    `;

    tbody.prepend(fila);
    resetearFormulario('refugioModal');
    cerrarModal('refugioModal');
    alert('Refugio agregado en la tabla demo.');
}

function guardarRolDemo() {
    const modal = document.getElementById('rolModal');
    const campos = modal.querySelectorAll('input[type="text"], select, textarea');

    const nombre = limpiarTexto(campos[0].value).toUpperCase().replaceAll(' ', '_');
    const estado = limpiarTexto(campos[1].value);
    const descripcion = limpiarTexto(campos[2].value);

    if (!nombre || !descripcion) {
        alert('Complete el nombre del rol y la descripción.');
        return;
    }

    const tablaRoles = obtenerTablaPrincipal();
    const tbody = tablaRoles.querySelector('tbody');
    const fila = document.createElement('tr');

    fila.innerHTML = `
        <td><span class="badge bg-primary">${nombre}</span></td>
        <td>${descripcion}</td>
        <td>0</td>
        <td><span class="badge ${estado === 'Activo' ? 'bg-success' : 'bg-secondary'}">${estado}</span></td>
        <td>${new Date().toLocaleDateString('es-CR')}</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-1" title="Ver"><i class="fas fa-eye"></i></button>
            <button class="btn btn-sm btn-outline-warning me-1" title="Editar"><i class="fas fa-pen"></i></button>
            <button class="btn btn-sm btn-outline-secondary" title="Permisos"><i class="fas fa-key"></i></button>
        </td>
    `;

    tbody.prepend(fila);
    resetearFormulario('rolModal');
    cerrarModal('rolModal');
    alert('Rol agregado en la tabla demo.');
}

function obtenerBadgeRol(rol) {
    const rolNormalizado = rol.toLowerCase();
    if (rolNormalizado.includes('admin')) return 'bg-dark';
    if (rolNormalizado.includes('refugio')) return 'bg-success';
    if (rolNormalizado.includes('adoptante')) return 'bg-info text-dark';
    if (rolNormalizado.includes('visitante')) return 'bg-secondary';
    return 'bg-primary';
}

function configurarAccionesTabla() {
    document.addEventListener('click', (event) => {
        const boton = event.target.closest('button[title]');
        if (!boton) return;

        const accion = boton.getAttribute('title');
        const fila = boton.closest('tr');

        if (!fila) return;

        if (['Bloquear', 'Suspender', 'Rechazar'].includes(accion)) {
            if (confirm(`¿Desea ${accion.toLowerCase()} este registro?`)) {
                const badgeEstado = fila.querySelector('td:nth-last-child(2) .badge');
                if (badgeEstado) {
                    badgeEstado.className = 'badge bg-danger';
                    badgeEstado.textContent = accion === 'Bloquear' ? 'Bloqueado' : accion === 'Suspender' ? 'Suspendido' : 'Rechazado';
                }
            }
        }

        if (['Aprobar', 'Activar', 'Reactivar'].includes(accion)) {
            const badgeEstado = fila.querySelector('td:nth-last-child(2) .badge');
            if (badgeEstado) {
                badgeEstado.className = 'badge bg-success';
                badgeEstado.textContent = accion === 'Aprobar' ? 'Aprobado' : 'Activo';
            }
        }

        if (accion === 'Ver') {
            alert(limpiarTexto(fila.innerText));
        }
    });
}
