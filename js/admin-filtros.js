// =====================================================
// Huellitas de Amor - Filtros y paginación del Admin
// =====================================================
// Frontend demo: filtra y pagina las filas existentes en el HTML.
// Luego se puede reemplazar por consultas reales a Spring Boot.

function normalizarTexto(texto) {
    return (texto || "")
        .toString()
        .trim()
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "");
}

function obtenerValorFiltroSelect(id) {
    const select = document.getElementById(id);

    if (!select) {
        return "";
    }

    const valor = normalizarTexto(select.value);

    if (valor.includes("todos") || valor.includes("todas") || valor.includes("seleccione")) {
        return "";
    }

    return valor;
}

function mostrarMensajeSinResultados(tbody, columnas, idMensaje) {
    let filaMensaje = document.getElementById(idMensaje);

    if (!filaMensaje) {
        filaMensaje = document.createElement("tr");
        filaMensaje.id = idMensaje;
        filaMensaje.classList.add("d-none");
        filaMensaje.innerHTML = `
            <td colspan="${columnas}" class="text-center text-muted py-4">
                <i class="fas fa-search me-2"></i>No se encontraron resultados con los filtros seleccionados.
            </td>
        `;
        tbody.appendChild(filaMensaje);
    }

    return filaMensaje;
}

function configurarFiltrosTabla(configuracion) {
    const inputBusqueda = document.getElementById(configuracion.inputBusquedaId);
    const tbody = document.querySelector(configuracion.tbodySelector);

    if (!inputBusqueda || !tbody) {
        return;
    }

    const columnas = configuracion.columnas || 6;
    const filasOriginales = Array.from(tbody.querySelectorAll("tr"));
    const filaSinResultados = mostrarMensajeSinResultados(tbody, columnas, configuracion.idMensajeSinResultados);
    const filtrosSelect = configuracion.filtrosSelect || [];
    const filasPorPagina = configuracion.filasPorPagina || 5;
    const paginacion = configuracion.paginacionSelector
        ? document.querySelector(configuracion.paginacionSelector)
        : null;

    let paginaActual = 1;
    let filasFiltradas = [];

    const botonBuscar = configuracion.botonBuscarSelector
        ? document.querySelector(configuracion.botonBuscarSelector)
        : inputBusqueda.closest("form")?.querySelector("button");

    function filaCoincide(fila) {
        const busqueda = normalizarTexto(inputBusqueda.value);
        const textoFila = normalizarTexto(fila.innerText);

        const coincideBusqueda = !busqueda || textoFila.includes(busqueda);

        const coincideSelects = filtrosSelect.every((filtro) => {
            const valorFiltro = obtenerValorFiltroSelect(filtro.id);

            if (!valorFiltro) {
                return true;
            }

            const celda = fila.children[filtro.indiceColumna];
            const textoCelda = normalizarTexto(celda ? celda.innerText : "");

            return textoCelda.includes(valorFiltro);
        });

        return coincideBusqueda && coincideSelects;
    }

    function actualizarContador(totalFiltrado) {
        if (!configuracion.contadorSelector) {
            return;
        }

        const contador = document.querySelector(configuracion.contadorSelector);
        if (!contador) {
            return;
        }

        if (totalFiltrado === 0) {
            contador.textContent = `Mostrando 0 de ${filasOriginales.length} ${configuracion.etiquetaPlural}`;
            return;
        }

        const inicio = (paginaActual - 1) * filasPorPagina + 1;
        const fin = Math.min(paginaActual * filasPorPagina, totalFiltrado);
        contador.textContent = `Mostrando ${inicio}-${fin} de ${totalFiltrado} ${configuracion.etiquetaPlural}`;
    }

    function crearItemPagina(texto, pagina, deshabilitado = false, activo = false) {
        const li = document.createElement("li");
        li.className = `page-item${deshabilitado ? " disabled" : ""}${activo ? " active" : ""}`;

        const a = document.createElement("a");
        a.className = "page-link";
        a.href = "#";
        a.textContent = texto;
        a.addEventListener("click", function (event) {
            event.preventDefault();
            if (!deshabilitado) {
                paginaActual = pagina;
                renderizarTabla();
            }
        });

        li.appendChild(a);
        return li;
    }

    function renderizarPaginacion(totalFiltrado) {
        if (!paginacion) {
            return;
        }

        paginacion.innerHTML = "";
        const totalPaginas = Math.ceil(totalFiltrado / filasPorPagina);

        if (totalPaginas <= 1) {
            paginacion.classList.add("d-none");
            return;
        }

        paginacion.classList.remove("d-none");
        paginacion.appendChild(crearItemPagina("Anterior", paginaActual - 1, paginaActual === 1));

        for (let i = 1; i <= totalPaginas; i++) {
            paginacion.appendChild(crearItemPagina(i, i, false, i === paginaActual));
        }

        paginacion.appendChild(crearItemPagina("Siguiente", paginaActual + 1, paginaActual === totalPaginas));
    }

    function renderizarTabla() {
        const totalFiltrado = filasFiltradas.length;
        const totalPaginas = Math.max(1, Math.ceil(totalFiltrado / filasPorPagina));

        if (paginaActual > totalPaginas) {
            paginaActual = totalPaginas;
        }

        const inicio = (paginaActual - 1) * filasPorPagina;
        const fin = inicio + filasPorPagina;
        const filasPagina = filasFiltradas.slice(inicio, fin);

        filasOriginales.forEach((fila) => {
            fila.classList.toggle("d-none", !filasPagina.includes(fila));
        });

        filaSinResultados.classList.toggle("d-none", totalFiltrado > 0);
        actualizarContador(totalFiltrado);
        renderizarPaginacion(totalFiltrado);
    }

    function aplicarFiltros() {
        paginaActual = 1;
        filasFiltradas = filasOriginales.filter(filaCoincide);
        renderizarTabla();
    }

    inputBusqueda.addEventListener("input", aplicarFiltros);

    filtrosSelect.forEach((filtro) => {
        const select = document.getElementById(filtro.id);
        if (select) {
            select.addEventListener("change", aplicarFiltros);
        }
    });

    if (botonBuscar) {
        botonBuscar.addEventListener("click", aplicarFiltros);
    }

    const form = inputBusqueda.closest("form");
    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();
            aplicarFiltros();
        });
    }

    aplicarFiltros();
}

function inicializarFiltrosAdmin() {
    configurarFiltrosTabla({
        inputBusquedaId: "buscarUsuario",
        tbodySelector: "table tbody",
        contadorSelector: "#contadorUsuarios",
        paginacionSelector: "#paginacionUsuarios",
        etiquetaPlural: "usuarios",
        columnas: 6,
        filasPorPagina: 3,
        idMensajeSinResultados: "sinResultadosUsuarios",
        filtrosSelect: [
            { id: "filtroRol", indiceColumna: 1 },
            { id: "filtroEstado", indiceColumna: 4 }
        ]
    });

    configurarFiltrosTabla({
        inputBusquedaId: "buscarRefugio",
        tbodySelector: "table tbody",
        contadorSelector: "#contadorRefugios",
        paginacionSelector: "#paginacionRefugios",
        etiquetaPlural: "refugios",
        columnas: 6,
        filasPorPagina: 3,
        idMensajeSinResultados: "sinResultadosRefugios",
        filtrosSelect: [
            { id: "filtroProvincia", indiceColumna: 2 },
            { id: "filtroEstado", indiceColumna: 4 }
        ]
    });
}

document.addEventListener("DOMContentLoaded", inicializarFiltrosAdmin);
