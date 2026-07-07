// =====================================================
// PawsMatch - Base de datos simulada frontend
// =====================================================
// Este archivo centraliza los datos demo de todos los módulos.
// En Spring Boot, esta fuente se reemplaza por endpoints REST o Thymeleaf.

(function () {
    const STORAGE_KEY = 'pawsmatchDataV1';

    const datosIniciales = {
        roles: [
            { id: 1, nombre: 'ADMIN_GENERAL', descripcion: 'Administrador general del sistema', estado: 'Activo', permisos: ['Usuarios', 'Refugios', 'Roles', 'Estadísticas', 'Reportes', 'Configuración', 'Bitácora', 'Notificaciones'] },
            { id: 2, nombre: 'REFUGIO', descripcion: 'Gestión de refugio, mascotas y solicitudes recibidas', estado: 'Activo', permisos: ['Mascotas', 'Solicitudes recibidas', 'Historial de adopciones'] },
            { id: 3, nombre: 'ADOPTANTE', descripcion: 'Usuario adoptante con acceso a perfil y solicitudes', estado: 'Activo', permisos: ['Perfil', 'Favoritos', 'Solicitudes', 'Seguimiento'] },
            { id: 4, nombre: 'VISITANTE', descripcion: 'Usuario público sin autenticación', estado: 'Activo', permisos: ['Home', 'Catálogo', 'Detalle de mascota'] }
        ],
        usuarios: [
            { id: 1, nombre: 'Henry Diking', correo: 'admin@pawsmatch.com', rolId: 1, estado: 'Activo', fechaRegistro: '2026-06-01' },
            { id: 2, nombre: 'María Fernanda López', correo: 'maria.admin@pawsmatch.com', rolId: 1, estado: 'Activo', fechaRegistro: '2026-06-05' },
            { id: 3, nombre: 'Carlos Rojas', correo: 'carlos.soporte@pawsmatch.com', rolId: 1, estado: 'Pendiente', fechaRegistro: '2026-06-18' },
            { id: 4, nombre: 'Refugio San Roque', correo: 'contacto@sanroque.org', rolId: 2, estado: 'Activo', fechaRegistro: '2026-05-10' },
            { id: 5, nombre: 'Refugio Patitas Felices', correo: 'info@patitasfelices.org', rolId: 2, estado: 'Activo', fechaRegistro: '2026-05-20' },
            { id: 6, nombre: 'Refugio Esperanza Animal', correo: 'esperanza@animal.org', rolId: 2, estado: 'Pendiente', fechaRegistro: '2026-06-23' },
            { id: 7, nombre: 'Juan Pérez', correo: 'juan@correo.com', rolId: 3, estado: 'Activo', fechaRegistro: '2026-06-03' },
            { id: 8, nombre: 'Ana Torres', correo: 'ana@correo.com', rolId: 3, estado: 'Activo', fechaRegistro: '2026-06-12' },
            { id: 9, nombre: 'Luis Morales', correo: 'luis@correo.com', rolId: 3, estado: 'Bloqueado', fechaRegistro: '2026-06-14' },
            { id: 10, nombre: 'Visitante Demo', correo: 'visitante@huellitas.com', rolId: 4, estado: 'Activo', fechaRegistro: '2026-06-25' }
        ],
        refugios: [
            { id: 1, nombre: 'Refugio San Roque', administradorUsuarioId: 4, provincia: 'Heredia', telefono: '8888-1001', correo: 'contacto@sanroque.org', estado: 'Aprobado', fechaRegistro: '2026-05-10' },
            { id: 2, nombre: 'Patitas Felices', administradorUsuarioId: 5, provincia: 'San José', telefono: '8888-1002', correo: 'info@patitasfelices.org', estado: 'Aprobado', fechaRegistro: '2026-05-20' },
            { id: 3, nombre: 'Esperanza Animal', administradorUsuarioId: 6, provincia: 'Alajuela', telefono: '8888-1003', correo: 'esperanza@animal.org', estado: 'Pendiente', fechaRegistro: '2026-06-23' },
            { id: 4, nombre: 'Hogar Peluditos', administradorUsuarioId: null, provincia: 'Cartago', telefono: '8888-1004', correo: 'hogar@peluditos.org', estado: 'Suspendido', fechaRegistro: '2026-06-28' }
        ],
        mascotas: [
            { id: 1, nombre: 'Max',   especie: 'Perro', raza: 'Labrador Retriever', edad: '2 años',   sexo: 'Macho',  descripcion: 'Amigable, juguetón y muy cariñoso. Ideal para familias con niños.', foto: 'https://images.unsplash.com/photo-1552053831-71594a27632d?auto=format&fit=crop&w=500&q=80', refugioId: 1, estado: 'Disponible', publicada: true,  fechaIngreso: '2026-05-15' },
            { id: 2, nombre: 'Luna',  especie: 'Gato',  raza: 'Siamés',            edad: '4 meses',   sexo: 'Hembra', descripcion: 'Tímida al principio pero muy cariñosa. Esterilizada y vacunada.',        foto: 'https://images.unsplash.com/photo-1574158622682-e40e69881006?auto=format&fit=crop&w=500&q=80', refugioId: 2, estado: 'En proceso', publicada: true,  fechaIngreso: '2026-06-01' },
            { id: 3, nombre: 'Rocky', especie: 'Perro', raza: 'Mestizo',           edad: '1 año',     sexo: 'Macho',  descripcion: 'Energético y leal. Vacunado y desparasitado al día.',                    foto: 'https://images.unsplash.com/photo-1537151625747-768eb6cf92b2?auto=format&fit=crop&w=500&q=80', refugioId: 1, estado: 'Adoptado',  publicada: false, fechaIngreso: '2026-04-10' },
            { id: 4, nombre: 'Mía',   especie: 'Gato',  raza: 'Persa',             edad: '3 años',    sexo: 'Hembra', descripcion: 'Tranquila y elegante. Perfecta para hogares sin otros animales.',        foto: 'https://images.unsplash.com/photo-1574144611937-0df059b5ef3e?auto=format&fit=crop&w=500&q=80', refugioId: 3, estado: 'Disponible', publicada: true,  fechaIngreso: '2026-06-20' },
            { id: 5, nombre: 'Toby',  especie: 'Perro', raza: 'Beagle',            edad: '3 años',    sexo: 'Macho',  descripcion: 'Curioso y muy sociable. Se lleva bien con otros perros.',                foto: 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?auto=format&fit=crop&w=500&q=80', refugioId: 1, estado: 'Disponible', publicada: true,  fechaIngreso: '2026-06-05' },
            { id: 6, nombre: 'Nala',  especie: 'Gato',  raza: 'Mestizo',           edad: '8 meses',   sexo: 'Hembra', descripcion: 'Juguetona y activa. Le encanta explorar y trepar.',                      foto: 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=500&q=80', refugioId: 1, estado: 'Disponible', publicada: true,  fechaIngreso: '2026-06-18' },
            { id: 7, nombre: 'Bruno', especie: 'Perro', raza: 'Pastor Alemán',     edad: '5 años',    sexo: 'Macho',  descripcion: 'Inteligente y protector. Necesita espacio y ejercicio diario.',          foto: 'https://images.unsplash.com/photo-1583511655826-05700d52f4d9?auto=format&fit=crop&w=500&q=80', refugioId: 1, estado: 'Disponible', publicada: false, fechaIngreso: '2026-07-01' }
        ],
        solicitudes: [
            { id: 1, mascotaId: 1, adoptanteUsuarioId: 7, refugioId: 1, estado: 'Pendiente',   fecha: '2026-07-01', motivoRechazo: '' },
            { id: 2, mascotaId: 2, adoptanteUsuarioId: 8, refugioId: 2, estado: 'En revisión', fecha: '2026-07-03', motivoRechazo: '' },
            { id: 3, mascotaId: 3, adoptanteUsuarioId: 7, refugioId: 1, estado: 'Aprobada',    fecha: '2026-06-26', motivoRechazo: '' },
            { id: 4, mascotaId: 5, adoptanteUsuarioId: 8, refugioId: 1, estado: 'Pendiente',   fecha: '2026-07-05', motivoRechazo: '' },
            { id: 5, mascotaId: 6, adoptanteUsuarioId: 9, refugioId: 1, estado: 'Rechazada',   fecha: '2026-07-02', motivoRechazo: 'El adoptante no cumple con los requisitos de espacio.' }
        ],
        bitacora: [
            { id: 1, usuarioId: 1, accion: 'Creó el rol ADMIN_GENERAL', modulo: 'Roles', fecha: '2026-07-01 09:15', ip: '192.168.1.12' },
            { id: 2, usuarioId: 1, accion: 'Aprobó refugio Patitas Felices', modulo: 'Refugios', fecha: '2026-07-02 14:21', ip: '192.168.1.12' },
            { id: 3, usuarioId: 2, accion: 'Exportó reporte de usuarios', modulo: 'Reportes', fecha: '2026-07-04 10:08', ip: '192.168.1.15' }
        ],
        notificaciones: [
            { id: 1, titulo: 'Nuevo refugio pendiente', mensaje: 'Esperanza Animal está pendiente de aprobación.', tipo: 'warning', leida: false, fecha: '2026-07-04' },
            { id: 2, titulo: 'Solicitud nueva', mensaje: 'Juan Pérez solicitó adoptar a Max.', tipo: 'info', leida: false, fecha: '2026-07-03' },
            { id: 3, titulo: 'Reporte generado', mensaje: 'El reporte mensual fue generado correctamente.', tipo: 'success', leida: true, fecha: '2026-07-01' }
        ],
        configuracion: {
            nombreSistema: 'PawsMatch',
            correoSoporte: 'soporte@pawsmatch.com',
            tiempoSesionMinutos: 30,
            aprobacionRefugiosManual: true,
            notificacionesCorreo: true
        }
    };

    function cargarDatos() {
        const guardado = localStorage.getItem(STORAGE_KEY);
        if (!guardado) return structuredClone(datosIniciales);
        try {
            return JSON.parse(guardado);
        } catch (error) {
            console.warn('No se pudieron cargar los datos guardados. Se usarán datos iniciales.', error);
            return structuredClone(datosIniciales);
        }
    }

    window.PawsMatchDB = cargarDatos();
    window.PawsMatchStorage = {
        key: STORAGE_KEY,
        save() { localStorage.setItem(STORAGE_KEY, JSON.stringify(window.PawsMatchDB)); },
        reset() { localStorage.removeItem(STORAGE_KEY); window.PawsMatchDB = structuredClone(datosIniciales); this.save(); }
    };
})();
