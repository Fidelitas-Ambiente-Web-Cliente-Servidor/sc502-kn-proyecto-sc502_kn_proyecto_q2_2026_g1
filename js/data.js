// =====================================================
// PawMatch - Base de datos simulada frontend
// =====================================================
// Este archivo centraliza los datos demo de todos los módulos.
// En Spring Boot, esta fuente se reemplaza por endpoints REST o Thymeleaf.

(function () {
    const STORAGE_KEY = 'pawmatchDataV1';

    const datosIniciales = {
        roles: [
            { id: 1, nombre: 'ADMIN_GENERAL', descripcion: 'Administrador general del sistema', estado: 'Activo', permisos: ['Usuarios', 'Refugios', 'Roles', 'Estadísticas', 'Reportes', 'Configuración', 'Bitácora', 'Notificaciones'] },
            { id: 2, nombre: 'REFUGIO', descripcion: 'Gestión de refugio, mascotas y solicitudes recibidas', estado: 'Activo', permisos: ['Mascotas', 'Solicitudes recibidas', 'Historial de adopciones'] },
            { id: 3, nombre: 'ADOPTANTE', descripcion: 'Usuario adoptante con acceso a perfil y solicitudes', estado: 'Activo', permisos: ['Perfil', 'Favoritos', 'Solicitudes', 'Seguimiento'] },
            { id: 4, nombre: 'VISITANTE', descripcion: 'Usuario público sin autenticación', estado: 'Activo', permisos: ['Home', 'Catálogo', 'Detalle de mascota'] }
        ],
        usuarios: [
            { id: 1, nombre: 'Henry Diking', correo: 'admin@huellitas.com', rolId: 1, estado: 'Activo', fechaRegistro: '2026-06-01' },
            { id: 2, nombre: 'María Fernanda López', correo: 'maria.admin@huellitas.com', rolId: 1, estado: 'Activo', fechaRegistro: '2026-06-05' },
            { id: 3, nombre: 'Carlos Rojas', correo: 'carlos.soporte@pawmatch.com', rolId: 1, estado: 'Pendiente', fechaRegistro: '2026-06-18' },
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
            { id: 1, nombre: 'Max', especie: 'Perro', refugioId: 1, estado: 'Disponible' },
            { id: 2, nombre: 'Luna', especie: 'Gato', refugioId: 2, estado: 'En proceso' },
            { id: 3, nombre: 'Rocky', especie: 'Perro', refugioId: 1, estado: 'Adoptado' },
            { id: 4, nombre: 'Mía', especie: 'Gato', refugioId: 3, estado: 'Disponible' }
        ],
        solicitudes: [
            { id: 1, mascotaId: 1, adoptanteUsuarioId: 7, estado: 'Pendiente', fecha: '2026-07-01' },
            { id: 2, mascotaId: 2, adoptanteUsuarioId: 8, estado: 'En revisión', fecha: '2026-07-03' },
            { id: 3, mascotaId: 3, adoptanteUsuarioId: 7, estado: 'Aprobada', fecha: '2026-06-26' }
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
            nombreSistema: 'PawMatch',
            correoSoporte: 'soporte@pawmatch.com',
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

    window.HuellitasDB = cargarDatos();
    window.HuellitasStorage = {
        key: STORAGE_KEY,
        save() { localStorage.setItem(STORAGE_KEY, JSON.stringify(window.HuellitasDB)); },
        reset() { localStorage.removeItem(STORAGE_KEY); window.HuellitasDB = structuredClone(datosIniciales); this.save(); }
    };
})();
