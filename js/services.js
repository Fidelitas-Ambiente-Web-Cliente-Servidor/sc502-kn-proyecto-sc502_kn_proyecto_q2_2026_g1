// =====================================================
// PawsMatch - Servicios frontend
// =====================================================

const PawsMatchService = (() => {
    const db = () => window.PawsMatchDB;
    const save = () => window.PawsMatchStorage.save();
    const nextId = (coleccion) => Math.max(0, ...coleccion.map(item => item.id || 0)) + 1;

    function getRol(id) { return db().roles.find(r => Number(r.id) === Number(id)); }
    function getUsuario(id) { return db().usuarios.find(u => Number(u.id) === Number(id)); }
    function getRefugio(id) { return db().refugios.find(r => Number(r.id) === Number(id)); }
    function getMascota(id) { return db().mascotas.find(m => Number(m.id) === Number(id)); }

    function usuariosDetallados() {
        return db().usuarios.map(usuario => ({
            ...usuario,
            rol: getRol(usuario.rolId)?.nombre || 'SIN_ROL'
        }));
    }

    function refugiosDetallados() {
        return db().refugios.map(refugio => ({
            ...refugio,
            administrador: getUsuario(refugio.administradorUsuarioId)?.nombre || 'Sin asignar'
        }));
    }

    function solicitudesDetalladas() {
        return db().solicitudes.map(solicitud => ({
            ...solicitud,
            mascota: getMascota(solicitud.mascotaId)?.nombre || 'Sin mascota',
            adoptante: getUsuario(solicitud.adoptanteUsuarioId)?.nombre || 'Sin adoptante'
        }));
    }

    function bitacoraDetallada() {
        return db().bitacora.map(item => ({
            ...item,
            usuario: getUsuario(item.usuarioId)?.nombre || 'Sistema'
        }));
    }

    function conteoUsuariosPorRol() {
        return db().roles.map(rol => ({
            ...rol,
            totalUsuarios: db().usuarios.filter(usuario => Number(usuario.rolId) === Number(rol.id)).length
        }));
    }

    function crearUsuario(datos) {
        const usuario = {
            id: nextId(db().usuarios),
            nombre: datos.nombre,
            correo: datos.correo,
            rolId: Number(datos.rolId),
            estado: datos.estado || 'Activo',
            fechaRegistro: new Date().toISOString().slice(0, 10)
        };
        db().usuarios.push(usuario);
        agregarBitacora(1, `Creó usuario ${usuario.nombre}`, 'Usuarios');
        save();
        return usuario;
    }

    function crearRefugio(datos) {
        const refugio = {
            id: nextId(db().refugios),
            nombre: datos.nombre,
            administradorUsuarioId: datos.administradorUsuarioId ? Number(datos.administradorUsuarioId) : null,
            provincia: datos.provincia,
            telefono: datos.telefono,
            correo: datos.correo,
            estado: datos.estado || 'Pendiente',
            fechaRegistro: new Date().toISOString().slice(0, 10)
        };
        db().refugios.push(refugio);
        agregarBitacora(1, `Registró refugio ${refugio.nombre}`, 'Refugios');
        save();
        return refugio;
    }

    function crearRol(datos) {
        const rol = {
            id: nextId(db().roles),
            nombre: datos.nombre.toUpperCase().replaceAll(' ', '_'),
            descripcion: datos.descripcion,
            estado: datos.estado || 'Activo',
            permisos: datos.permisos || []
        };
        db().roles.push(rol);
        agregarBitacora(1, `Creó rol ${rol.nombre}`, 'Roles');
        save();
        return rol;
    }

    function actualizarEstadoUsuario(id, estado) {
        const usuario = getUsuario(id);
        if (!usuario) return;
        usuario.estado = estado;
        agregarBitacora(1, `Cambió estado de usuario ${usuario.nombre} a ${estado}`, 'Usuarios');
        save();
    }

    function actualizarEstadoRefugio(id, estado) {
        const refugio = getRefugio(id);
        if (!refugio) return;
        refugio.estado = estado;
        agregarBitacora(1, `Cambió estado de refugio ${refugio.nombre} a ${estado}`, 'Refugios');
        save();
    }

    function marcarNotificacionLeida(id) {
        const notificacion = db().notificaciones.find(n => Number(n.id) === Number(id));
        if (!notificacion) return;
        notificacion.leida = true;
        save();
    }

    function crearNotificacion(datos) {
        const notificacion = {
            id: nextId(db().notificaciones),
            titulo: datos.titulo,
            mensaje: datos.mensaje,
            tipo: datos.tipo || 'info',
            leida: false,
            fecha: new Date().toISOString().slice(0, 10)
        };
        db().notificaciones.unshift(notificacion);
        agregarBitacora(1, `Creó notificación ${notificacion.titulo}`, 'Notificaciones');
        save();
        return notificacion;
    }

    function guardarConfiguracion(datos) {
        db().configuracion = { ...db().configuracion, ...datos };
        agregarBitacora(1, 'Actualizó la configuración general', 'Configuración');
        save();
    }

    function agregarBitacora(usuarioId, accion, modulo) {
        db().bitacora.unshift({
            id: nextId(db().bitacora),
            usuarioId,
            accion,
            modulo,
            fecha: new Date().toLocaleString('es-CR'),
            ip: '127.0.0.1'
        });
    }

    function estadisticas() {
        const usuarios = db().usuarios;
        const refugios = db().refugios;
        const mascotas = db().mascotas;
        const solicitudes = db().solicitudes;
        return {
            totalUsuarios: usuarios.length,
            usuariosActivos: usuarios.filter(u => u.estado === 'Activo').length,
            usuariosPendientes: usuarios.filter(u => u.estado === 'Pendiente').length,
            usuariosBloqueados: usuarios.filter(u => u.estado === 'Bloqueado').length,
            totalRefugios: refugios.length,
            refugiosAprobados: refugios.filter(r => r.estado === 'Aprobado').length,
            refugiosPendientes: refugios.filter(r => r.estado === 'Pendiente').length,
            totalMascotas: mascotas.length,
            mascotasDisponibles: mascotas.filter(m => m.estado === 'Disponible').length,
            solicitudesPendientes: solicitudes.filter(s => ['Pendiente', 'En revisión'].includes(s.estado)).length,
            adopcionesAprobadas: solicitudes.filter(s => s.estado === 'Aprobada').length,
            notificacionesNoLeidas: db().notificaciones.filter(n => !n.leida).length
        };
    }

    // ── Refugio module ──────────────────────────────────────────

    function mascotasPorRefugio(refugioId) {
        return db().mascotas.filter(m => Number(m.refugioId) === Number(refugioId));
    }

    function crearMascota(datos) {
        const mascota = {
            id: nextId(db().mascotas),
            nombre: datos.nombre,
            especie: datos.especie,
            raza: datos.raza || '',
            edad: datos.edad || '',
            sexo: datos.sexo || '',
            descripcion: datos.descripcion || '',
            foto: datos.foto || '',
            refugioId: Number(datos.refugioId),
            estado: 'Disponible',
            publicada: false,
            fechaIngreso: new Date().toISOString().slice(0, 10)
        };
        db().mascotas.push(mascota);
        agregarBitacora(Number(datos.refugioId), `Registró mascota ${mascota.nombre}`, 'Mascotas');
        save();
        return mascota;
    }

    function actualizarMascota(id, datos) {
        const mascota = getMascota(id);
        if (!mascota) return;
        Object.assign(mascota, datos);
        agregarBitacora(mascota.refugioId, `Editó mascota ${mascota.nombre}`, 'Mascotas');
        save();
    }

    function eliminarMascota(id) {
        const idx = db().mascotas.findIndex(m => Number(m.id) === Number(id));
        if (idx === -1) return;
        const nombre = db().mascotas[idx].nombre;
        db().mascotas.splice(idx, 1);
        agregarBitacora(1, `Eliminó mascota ${nombre}`, 'Mascotas');
        save();
    }

    function togglePublicarMascota(id) {
        const mascota = getMascota(id);
        if (!mascota) return;
        mascota.publicada = !mascota.publicada;
        agregarBitacora(mascota.refugioId, `${mascota.publicada ? 'Publicó' : 'Despublicó'} mascota ${mascota.nombre}`, 'Mascotas');
        save();
    }

    function solicitudesPorRefugio(refugioId) {
        const rid = Number(refugioId);
        return db().solicitudes
            .filter(s => Number(s.refugioId) === rid)
            .map(s => ({
                ...s,
                mascota:   getMascota(s.mascotaId)?.nombre   || 'Sin mascota',
                adoptante: getUsuario(s.adoptanteUsuarioId)?.nombre || 'Sin adoptante',
                fotoMascota: getMascota(s.mascotaId)?.foto   || '',
                especieMascota: getMascota(s.mascotaId)?.especie || ''
            }));
    }

    function aprobarSolicitud(id) {
        const sol = db().solicitudes.find(s => Number(s.id) === Number(id));
        if (!sol) return;
        sol.estado = 'Aprobada';
        const mascota = getMascota(sol.mascotaId);
        if (mascota) { mascota.estado = 'En proceso'; mascota.publicada = false; }
        agregarBitacora(sol.refugioId, `Aprobó solicitud #${id} para ${mascota?.nombre || ''}`, 'Solicitudes');
        save();
    }

    function rechazarSolicitud(id, motivo) {
        const sol = db().solicitudes.find(s => Number(s.id) === Number(id));
        if (!sol) return;
        sol.estado = 'Rechazada';
        sol.motivoRechazo = motivo || '';
        agregarBitacora(sol.refugioId, `Rechazó solicitud #${id}`, 'Solicitudes');
        save();
    }

    function historialAdopciones(refugioId) {
        return solicitudesPorRefugio(refugioId).filter(s => s.estado === 'Aprobada');
    }

    function estadisticasRefugio(refugioId) {
        const mascotas   = mascotasPorRefugio(refugioId);
        const solicitudes = solicitudesPorRefugio(refugioId);
        return {
            totalMascotas:        mascotas.length,
            mascotasDisponibles:  mascotas.filter(m => m.estado === 'Disponible').length,
            mascotasAdoptadas:    mascotas.filter(m => m.estado === 'Adoptado').length,
            mascotasPublicadas:   mascotas.filter(m => m.publicada).length,
            solicitudesPendientes: solicitudes.filter(s => ['Pendiente', 'En revisión'].includes(s.estado)).length,
            solicitudesAprobadas: solicitudes.filter(s => s.estado === 'Aprobada').length,
            solicitudesRechazadas: solicitudes.filter(s => s.estado === 'Rechazada').length
        };
    }

    return {
        roles: () => db().roles,
        usuarios: usuariosDetallados,
        refugios: refugiosDetallados,
        mascotas: () => db().mascotas,
        solicitudes: solicitudesDetalladas,
        bitacora: bitacoraDetallada,
        notificaciones: () => db().notificaciones,
        configuracion: () => db().configuracion,
        conteoUsuariosPorRol,
        crearUsuario,
        crearRefugio,
        crearRol,
        actualizarEstadoUsuario,
        actualizarEstadoRefugio,
        marcarNotificacionLeida,
        crearNotificacion,
        guardarConfiguracion,
        estadisticas,
        // Refugio module
        mascotasPorRefugio,
        crearMascota,
        actualizarMascota,
        eliminarMascota,
        togglePublicarMascota,
        solicitudesPorRefugio,
        aprobarSolicitud,
        rechazarSolicitud,
        historialAdopciones,
        estadisticasRefugio
    };
})();
