// =====================================================
// PawsMatch - Utilidades frontend
// =====================================================

const PawsMatchUtils = (() => {
    function normalizar(texto) {
        return (texto || '').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim();
    }

    function badgeEstado(estado) {
        const valor = normalizar(estado);
        if (valor.includes('activo') || valor.includes('aprobado') || valor.includes('aprobada')) return 'bg-success';
        if (valor.includes('pendiente') || valor.includes('revision') || valor.includes('proceso')) return 'bg-warning text-dark';
        if (valor.includes('bloqueado') || valor.includes('suspendido') || valor.includes('rechazado')) return 'bg-danger';
        if (valor.includes('leida')) return 'bg-secondary';
        return 'bg-info text-dark';
    }

    function badgeRol(rol) {
        const valor = normalizar(rol);
        if (valor.includes('admin')) return 'bg-dark';
        if (valor.includes('refugio')) return 'bg-success';
        if (valor.includes('adoptante')) return 'bg-info text-dark';
        if (valor.includes('visitante')) return 'bg-secondary';
        return 'bg-primary';
    }

    function exportarCSV(nombreArchivo, filas) {
        if (!filas.length) {
            alert('No hay datos para exportar.');
            return;
        }
        const columnas = Object.keys(filas[0]);
        const csv = [
            columnas.join(','),
            ...filas.map(fila => columnas.map(col => `"${String(fila[col] ?? '').replaceAll('"', '""')}"`).join(','))
        ].join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = nombreArchivo;
        link.click();
        URL.revokeObjectURL(url);
    }

    function configurarTabla({ datos, tbody, renderFila, buscarInput, filtros = [], contador, paginacion, filasPorPagina = 5, etiqueta = 'registros' }) {
        let pagina = 1;
        let filtrados = [];

        function coincide(item) {
            const busqueda = normalizar(buscarInput?.value || '');
            const textoCompleto = normalizar(Object.values(item).join(' '));
            if (busqueda && !textoCompleto.includes(busqueda)) return false;
            return filtros.every(f => {
                const valor = normalizar(document.getElementById(f.id)?.value || '');
                if (!valor || valor.includes('todos') || valor.includes('todas')) return true;
                return normalizar(item[f.campo]).includes(valor);
            });
        }

        function render() {
            filtrados = datos().filter(coincide);
            const totalPaginas = Math.max(1, Math.ceil(filtrados.length / filasPorPagina));
            if (pagina > totalPaginas) pagina = totalPaginas;
            const inicio = (pagina - 1) * filasPorPagina;
            const paginaDatos = filtrados.slice(inicio, inicio + filasPorPagina);
            tbody.innerHTML = paginaDatos.length
                ? paginaDatos.map(renderFila).join('')
                : `<tr><td colspan="20" class="text-center text-muted py-4"><i class="fas fa-search me-2"></i>No hay resultados.</td></tr>`;
            if (contador) {
                const desde = filtrados.length ? inicio + 1 : 0;
                const hasta = Math.min(inicio + filasPorPagina, filtrados.length);
                contador.textContent = `Mostrando ${desde}-${hasta} de ${filtrados.length} ${etiqueta}`;
            }
            if (paginacion) renderPaginacion(totalPaginas);
        }

        function renderPaginacion(totalPaginas) {
            paginacion.innerHTML = '';
            if (totalPaginas <= 1) return;
            const crear = (texto, nuevaPagina, disabled = false, active = false) => {
                const li = document.createElement('li');
                li.className = `page-item${disabled ? ' disabled' : ''}${active ? ' active' : ''}`;
                li.innerHTML = `<a class="page-link" href="#">${texto}</a>`;
                li.addEventListener('click', e => { e.preventDefault(); if (!disabled) { pagina = nuevaPagina; render(); } });
                paginacion.appendChild(li);
            };
            crear('Anterior', pagina - 1, pagina === 1);
            for (let i = 1; i <= totalPaginas; i++) crear(i, i, false, i === pagina);
            crear('Siguiente', pagina + 1, pagina === totalPaginas);
        }

        buscarInput?.addEventListener('input', () => { pagina = 1; render(); });
        filtros.forEach(f => document.getElementById(f.id)?.addEventListener('change', () => { pagina = 1; render(); }));
        render();
        return { render };
    }

    function cerrarModal(id) {
        const modalElement = document.getElementById(id);
        if (modalElement) modalElement.classList.remove('show');
    }

    return { normalizar, badgeEstado, badgeRol, exportarCSV, configurarTabla, cerrarModal };
})();
