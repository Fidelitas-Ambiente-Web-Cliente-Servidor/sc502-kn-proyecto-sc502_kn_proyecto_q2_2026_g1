/* ==================== VALIDACIONES DE FORMULARIOS ==================== */

/**
 * Validar que el email tenga un formato correcto
 * @param {string} email - Email a validar
 * @returns {boolean} True si es válido, False si no
 */
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Validar que la contraseña cumpla los requisitos
 * Mínimo 8 caracteres, al menos una mayúscula, un número y un carácter especial
 * @param {string} password - Contraseña a validar
 * @returns {object} Objeto con validación y mensajes
 */
function validarPassword(password) {
    const validacion = {
        valida: true,
        errores: []
    };

    if (password.length < 8) {
        validacion.valida = false;
        validacion.errores.push("La contraseña debe tener al menos 8 caracteres");
    }

    if (!/[A-Z]/.test(password)) {
        validacion.valida = false;
        validacion.errores.push("La contraseña debe contener al menos una mayúscula");
    }

    if (!/[0-9]/.test(password)) {
        validacion.valida = false;
        validacion.errores.push("La contraseña debe contener al menos un número");
    }

    if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
        validacion.valida = false;
        validacion.errores.push("La contraseña debe contener al menos un carácter especial (!@#$%...)");
    }

    return validacion;
}

/**
 * Validar que dos contraseñas coincidan
 * @param {string} password1 - Primera contraseña
 * @param {string} password2 - Segunda contraseña (confirmación)
 * @returns {boolean} True si coinciden, False si no
 */
function coincidirPasswords(password1, password2) {
    return password1 === password2;
}

/**
 * Validar que un campo no esté vacío
 * @param {string} valor - Valor a validar
 * @returns {boolean} True si no está vacío, False si está vacío
 */
function validarCampoRequerido(valor) {
    return valor.trim().length > 0;
}

/**
 * Validar nombre (solo letras y espacios)
 * @param {string} nombre - Nombre a validar
 * @returns {boolean} True si es válido, False si no
 */
function validarNombre(nombre) {
    const regex = /^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/;
    return regex.test(nombre);
}

/**
 * Validar teléfono (formato: +34 600 00 00 00 o 600000000)
 * @param {string} telefono - Teléfono a validar
 * @returns {boolean} True si es válido, False si no
 */
function validarTelefono(telefono) {
    const regex = /^(\+?34)?[6-9]\d{8}$/;
    return regex.test(telefono.replace(/\s/g, ''));
}

/**
 * Mostrar mensaje de error en un elemento
 * @param {string} elementId - ID del elemento donde mostrar el error
 * @param {string} mensaje - Mensaje de error
 */
function mostrarError(elementId, mensaje) {
    const elemento = document.getElementById(elementId);
    if (elemento) {
        elemento.innerHTML = `<div class="error-message">${mensaje}</div>`;
        elemento.style.display = 'block';
    }
}

/**
 * Limpiar mensaje de error de un elemento
 * @param {string} elementId - ID del elemento
 */
function limpiarError(elementId) {
    const elemento = document.getElementById(elementId);
    if (elemento) {
        elemento.innerHTML = '';
        elemento.style.display = 'none';
    }
}

/**
 * Mostrar mensaje de éxito
 * @param {string} elementId - ID del elemento donde mostrar el éxito
 * @param {string} mensaje - Mensaje de éxito
 */
function mostrarExito(elementId, mensaje) {
    const elemento = document.getElementById(elementId);
    if (elemento) {
        elemento.innerHTML = `<div class="success-message">${mensaje}</div>`;
        elemento.style.display = 'block';
    }
}

/**
 * Validar formulario de login
 * @param {object} datos - Objeto con email y password
 * @returns {object} Objeto con validación y errores
 */
function validarLogin(datos) {
    const validacion = {
        valida: true,
        errores: {}
    };

    // Validar email
    if (!validarCampoRequerido(datos.email)) {
        validacion.valida = false;
        validacion.errores.email = "El email es requerido";
    } else if (!validarEmail(datos.email)) {
        validacion.valida = false;
        validacion.errores.email = "El formato del email no es válido";
    }

    // Validar contraseña
    if (!validarCampoRequerido(datos.password)) {
        validacion.valida = false;
        validacion.errores.password = "La contraseña es requerida";
    }

    return validacion;
}

/**
 * Validar formulario de registro
 * @param {object} datos - Objeto con nombre, email, password, passwordConfirm
 * @returns {object} Objeto con validación y errores
 */
function validarRegistro(datos) {
    const validacion = {
        valida: true,
        errores: {}
    };

    // Validar nombre
    if (!validarCampoRequerido(datos.nombre)) {
        validacion.valida = false;
        validacion.errores.nombre = "El nombre es requerido";
    } else if (!validarNombre(datos.nombre)) {
        validacion.valida = false;
        validacion.errores.nombre = "El nombre solo puede contener letras y espacios";
    }

    // Validar email
    if (!validarCampoRequerido(datos.email)) {
        validacion.valida = false;
        validacion.errores.email = "El email es requerido";
    } else if (!validarEmail(datos.email)) {
        validacion.valida = false;
        validacion.errores.email = "El formato del email no es válido";
    }

    // Validar contraseña
    if (!validarCampoRequerido(datos.password)) {
        validacion.valida = false;
        validacion.errores.password = "La contraseña es requerida";
    } else {
        const validacionPassword = validarPassword(datos.password);
        if (!validacionPassword.valida) {
            validacion.valida = false;
            validacion.errores.password = validacionPassword.errores.join(" / ");
        }
    }

    // Validar confirmación de contraseña
    if (!validarCampoRequerido(datos.passwordConfirm)) {
        validacion.valida = false;
        validacion.errores.passwordConfirm = "Debes confirmar la contraseña";
    } else if (!coincidirPasswords(datos.password, datos.passwordConfirm)) {
        validacion.valida = false;
        validacion.errores.passwordConfirm = "Las contraseñas no coinciden";
    }

    return validacion;
}

/**
 * Validar formulario de recuperación de contraseña
 * @param {object} datos - Objeto con email
 * @returns {object} Objeto con validación y errores
 */
function validarRecuperarPassword(datos) {
    const validacion = {
        valida: true,
        errores: {}
    };

    if (!validarCampoRequerido(datos.email)) {
        validacion.valida = false;
        validacion.errores.email = "El email es requerido";
    } else if (!validarEmail(datos.email)) {
        validacion.valida = false;
        validacion.errores.email = "El formato del email no es válido";
    }

    return validacion;
}

/* ==================== STYLES PARA MENSAJES ==================== */
const estilosMensajes = `
<style>
    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 12px 20px;
        border-radius: 4px;
        border: 1px solid #f5c6cb;
        margin-bottom: 15px;
        font-size: 0.9rem;
    }

    .success-message {
        background-color: #d4edda;
        color: #155724;
        padding: 12px 20px;
        border-radius: 4px;
        border: 1px solid #c3e6cb;
        margin-bottom: 15px;
        font-size: 0.9rem;
    }

    .warning-message {
        background-color: #fff3cd;
        color: #856404;
        padding: 12px 20px;
        border-radius: 4px;
        border: 1px solid #ffeeba;
        margin-bottom: 15px;
        font-size: 0.9rem;
    }
</style>
`;

// Inyectar estilos de mensajes
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        document.head.insertAdjacentHTML('beforeend', estilosMensajes);
    });
} else {
    document.head.insertAdjacentHTML('beforeend', estilosMensajes);
}
