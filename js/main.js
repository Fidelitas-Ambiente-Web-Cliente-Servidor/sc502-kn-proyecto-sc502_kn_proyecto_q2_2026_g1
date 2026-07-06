

document.addEventListener('DOMContentLoaded', function() {
    
    // --- CONFIGURACIÓN INICIAL Y ROLES ---
    
    // Simulación de rol de usuario para la visibilidad del módulo Admin
    const userRole = 'admin'; // Cambiar a 'adoptante' para ocultar el panel admin
    const adminLink = document.getElementById('adminNavLink');
    
    if (adminLink && userRole === 'admin') {
        adminLink.classList.remove('d-none');
    }

    // --- MOTOR DE MATCHING (Lógica de compatibilidad) ---

    /**
     * Datos simulados de mascotas para el motor de matching.
     * En una app real, esto vendría de una API.
     */
    const mascotasDisponibles = [
        { id: 1, nombre: 'Max', raza: 'Golden Retriever', puntaje: 95, imagen: 'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?auto=format&fit=crop&w=150&h=150', tags: ['Activo', 'Espacio Grande'] },
        { id: 2, nombre: 'Bella', raza: 'Siamés', puntaje: 88, imagen: 'https://images.unsplash.com/photo-1573865668131-974f71230a7e?auto=format&fit=crop&w=150&h=150', tags: ['Tranquila', 'Departamento'] },
        { id: 3, nombre: 'Rocky', raza: 'Bulldog Francés', puntaje: 82, imagen: 'https://images.unsplash.com/photo-1537151608828-ea2b11777ee8?auto=format&fit=crop&w=150&h=150', tags: ['Moderado', 'Compañero'] }
    ];

    /**
     * Renderiza los resultados del matching en el dashboard.
     */
    function renderMatching() {
        const container = document.getElementById('matchingResults');
        if (!container) return;

        container.innerHTML = mascotasDisponibles.map(pet => `
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="position-relative mb-3">
                            <img src="${pet.imagen}" class="rounded-circle border border-3 border-primary" width="80" height="80" alt="${pet.nombre}">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                ${pet.puntaje}%
                            </span>
                        </div>
                        <h6 class="mb-1">${pet.nombre}</h6>
                        <p class="small text-muted mb-2">${pet.raza}</p>
                        <div class="d-flex justify-content-center gap-1 mb-3">
                            ${pet.tags.map(tag => `<span class="badge bg-light text-dark border small">${tag}</span>`).join('')}
                        </div>
                        <button class="btn btn-sm btn-primary w-100">Ver Perfil</button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // --- GESTIÓN DE FAVORITOS ---

    /**
     * Renderiza la lista de favoritos en la página de perfil.
     */
    function renderFavorites() {
        const container = document.getElementById('favoritesList');
        if (!container) return;

        const favoritos = [
            { nombre: 'Max', raza: 'Golden Retriever', img: 'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?auto=format&fit=crop&w=60&h=60' },
            { nombre: 'Bella', raza: 'Siamés', img: 'https://images.unsplash.com/photo-1573865668131-974f71230a7e?auto=format&fit=crop&w=60&h=60' }
        ];

        container.innerHTML = favoritos.map(fav => `
            <div class="list-group-item d-flex align-items-center border-0 px-0">
                <img src="${fav.img}" class="rounded me-3" alt="${fav.nombre}">
                <div class="flex-grow-1">
                    <h6 class="mb-0 small font-weight-bold">${fav.nombre}</h6>
                    <small class="text-muted">${fav.raza}</small>
                </div>
                <button class="btn btn-link text-danger p-0"><i class="fas fa-heart"></i></button>
            </div>
        `).join('');
    }

    // --- MANEJO DE FORMULARIOS Y ALERTAS ---

    const profileForm = document.getElementById('profileFormDetailed');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('¡Perfil actualizado! El motor de matching está recalculando tus mejores opciones...');
            setTimeout(() => {
                window.location.href = 'index.html';
            }, 1000);
        });
    }

    // Simulación de envío de reporte técnico
    const techReportBtn = document.querySelector('#techReportModal .btn-success');
    if (techReportBtn) {
        techReportBtn.addEventListener('click', function() {
            alert('Reporte técnico enviado. El refugio ha sido notificado y la alerta de seguimiento se ha actualizado.');
            const modal = bootstrap.Modal.getInstance(document.getElementById('techReportModal'));
            if (modal) modal.hide();
        });
    }

    // --- UTILIDADES ---

    // Resaltar el enlace activo en la barra lateral
    const currentPath = window.location.pathname.split('/').pop() || 'index.html';
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });

    // Inicializar componentes
    renderMatching();
    renderFavorites();
});
