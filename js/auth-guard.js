// =====================================================
// PawsMatch — Auth Guard
// =====================================================
// Include this script on every protected page BEFORE
// any other app script. It reads the session from
// sessionStorage and redirects away if the role doesn't
// match the required role for this page.
//
// MIGRATION NOTE (Spring Boot):
//   Replace this entire module with Spring Security's
//   SecurityFilterChain path rules and @PreAuthorize.
//   The sessionStorage key 'pawsMatchSession' maps to
//   the server-side Authentication object.
// =====================================================

(function () {
    const SESSION_KEY = 'pawsMatchSession';

    // Page → required rolId mapping
    // rolId: 1=ADMIN_GENERAL, 2=REFUGIO, 3=ADOPTANTE, 4=VISITANTE
    const PAGE_ROLES = {
        'dashboard':       1,
        'usuarios':        1,
        'refugios':        1,
        'roles':           1,
        'estadisticas':    1,
        'reportes':        1,
        'bitacora':        1,
        'notificaciones':  1,
        'configuracion':   1,
        'refugio-dashboard':   2,
        'refugio-mascotas':    2,
        'refugio-solicitudes': 2,
        'perfil':          3,
        'solicitudes':     3,
        'seguimiento':     3,
    };

    window.PawsMatchAuth = {
        /**
         * Get the current session object or null.
         * @returns {{ userId, nombre, correo, rolId, rol } | null}
         */
        getSession() {
            try {
                return JSON.parse(sessionStorage.getItem(SESSION_KEY));
            } catch {
                return null;
            }
        },

        /**
         * Save a session after a successful login.
         * @param {{ userId, nombre, correo, rolId, rol }} data
         */
        saveSession(data) {
            sessionStorage.setItem(SESSION_KEY, JSON.stringify(data));
        },

        /** Clear session (logout). */
        clearSession() {
            sessionStorage.removeItem(SESSION_KEY);
        },

        /**
         * Guard: call at the top of every protected page.
         * Reads data-page from <body> and redirects if the
         * current user doesn't have the required role.
         * @param {string} [redirectTo] - override login redirect URL
         */
        requireRole(redirectTo = 'login.html') {
            // GUARD DISABLED FOR DEMO — re-enable when real auth is wired
            // const page    = document.body.dataset.page;
            // const required = PAGE_ROLES[page];
            // if (!required) return;
            // const session = this.getSession();
            // if (!session || session.rolId !== required) {
            //     window.location.replace(redirectTo);
            // }
        },

        /**
         * Redirect after login based on rolId.
         * Call this from login.html after validating credentials.
         * @param {number} rolId
         */
        redirectByRole(rolId) {
            const destinations = {
                1: 'admin-dashboard.html',
                2: 'refugio-dashboard.html',
                3: 'perfil.html',
                4: 'index.html',
            };
            window.location.href = destinations[rolId] || 'index.html';
        },
    };

    // Auto-guard: runs on every page that has a matching data-page
    document.addEventListener('DOMContentLoaded', () => {
        window.PawsMatchAuth.requireRole();
    });
})();
