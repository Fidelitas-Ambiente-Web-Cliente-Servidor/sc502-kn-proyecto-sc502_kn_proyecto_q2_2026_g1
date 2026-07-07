// =====================================================
// PawsMatch — Toast Notifications
// =====================================================
// Usage:
//   PawsMatchToast.show('Guardado correctamente', 'success')
//   PawsMatchToast.show('Error al guardar', 'error')
//   PawsMatchToast.show('Nuevo refugio pendiente', 'warning')
//   PawsMatchToast.show('3 solicitudes nuevas', 'info')
//
//   PawsMatchToast.confirm('¿Eliminar esta mascota?', onConfirm, onCancel)
//
// MIGRATION NOTE (Spring Boot + Thymeleaf):
//   Server-side flash messages (RedirectAttributes.addFlashAttribute)
//   can trigger toasts by rendering a hidden <div data-toast-msg>
//   element that this module picks up on DOMContentLoaded.
// =====================================================

(function () {
    // ── Inject container + styles once ──────────────────────
    function ensureContainer() {
        if (document.getElementById('toastContainer')) return;

        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.setAttribute('aria-live', 'polite');
        container.setAttribute('aria-atomic', 'false');
        document.body.appendChild(container);

        const style = document.createElement('style');
        style.textContent = `
#toastContainer {
    position: fixed;
    top: 1.2rem;
    right: 1.2rem;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    max-width: 340px;
    pointer-events: none;
}
.toast {
    display: flex;
    align-items: flex-start;
    gap: 0.7rem;
    padding: 0.85rem 1rem;
    border-radius: 12px;
    font-family: -apple-system, 'Poppins', sans-serif;
    font-size: 0.88rem;
    font-weight: 500;
    line-height: 1.4;
    box-shadow: 0 4px 20px rgba(0,0,0,0.13);
    pointer-events: auto;
    cursor: default;
    opacity: 0;
    transform: translateX(20px);
    transition: opacity 0.22s ease, transform 0.22s ease;
    border-left: 4px solid transparent;
    background: #fff;
    color: #1f2328;
    max-width: 100%;
    word-break: break-word;
}
.toast.show { opacity: 1; transform: translateX(0); }
.toast.hide  { opacity: 0; transform: translateX(20px); }
.toast-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }
.toast-body { flex: 1; }
.toast-title { font-weight: 700; margin-bottom: 0.1rem; }
.toast-msg   { color: #57606a; }
.toast-close {
    background: none; border: none; cursor: pointer;
    color: #57606a; font-size: 1rem; padding: 0; line-height: 1;
    flex-shrink: 0; margin-top: 1px;
}
.toast-close:hover { color: #1f2328; }
/* type variants */
.toast.success { border-left-color: #22c55e; }
.toast.success .toast-icon::before { content: '✅'; }
.toast.error   { border-left-color: #ef4444; }
.toast.error   .toast-icon::before { content: '❌'; }
.toast.warning { border-left-color: #f59e0b; }
.toast.warning .toast-icon::before { content: '⚠️'; }
.toast.info    { border-left-color: #3b82f6; }
.toast.info    .toast-icon::before { content: 'ℹ️'; }

/* Confirm dialog overlay */
.toast-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.35);
    z-index: 10000;
    display: flex; align-items: center; justify-content: center;
    animation: toastFadeIn 0.18s ease;
}
@keyframes toastFadeIn { from { opacity:0 } to { opacity:1 } }
.toast-dialog {
    background: #fff;
    border-radius: 16px;
    padding: 1.8rem 2rem;
    max-width: 380px;
    width: 90%;
    box-shadow: 0 12px 40px rgba(0,0,0,0.18);
    text-align: center;
    font-family: -apple-system,'Poppins',sans-serif;
}
.toast-dialog p {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2328;
    margin-bottom: 1.4rem;
}
.toast-dialog-btns {
    display: flex; gap: 0.8rem; justify-content: center;
}
.toast-dialog-btns button {
    padding: 0.65rem 1.4rem;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: 0.2s ease;
    font-family: inherit;
}
.toast-btn-cancel {
    background: #f3f4f6; color: #57606a; border: 1.5px solid #e5e7eb;
}
.toast-btn-cancel:hover { background: #e5e7eb; }
.toast-btn-confirm {
    background: #ef4444; color: #fff; border: none;
}
.toast-btn-confirm:hover { background: #dc2626; }
`;
        document.head.appendChild(style);
    }

    // ── Core show function ───────────────────────────────────
    function show(message, type = 'info', duration = 3500) {
        ensureContainer();
        const container = document.getElementById('toastContainer');

        const titles = { success: 'Listo', error: 'Error', warning: 'Atención', info: 'Info' };

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <span class="toast-icon" aria-hidden="true"></span>
            <div class="toast-body">
                <div class="toast-title">${titles[type] || 'Info'}</div>
                <div class="toast-msg">${message}</div>
            </div>
            <button class="toast-close" aria-label="Cerrar">&times;</button>
        `;

        toast.querySelector('.toast-close').addEventListener('click', () => dismiss(toast));
        container.appendChild(toast);

        // Trigger animation
        requestAnimationFrame(() => requestAnimationFrame(() => toast.classList.add('show')));

        if (duration > 0) {
            setTimeout(() => dismiss(toast), duration);
        }
        return toast;
    }

    function dismiss(toast) {
        toast.classList.remove('show');
        toast.classList.add('hide');
        toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    }

    // ── Confirm dialog ───────────────────────────────────────
    function confirm(message, onConfirm, onCancel) {
        ensureContainer();

        const overlay = document.createElement('div');
        overlay.className = 'toast-overlay';
        overlay.innerHTML = `
            <div class="toast-dialog" role="dialog" aria-modal="true">
                <p>${message}</p>
                <div class="toast-dialog-btns">
                    <button class="toast-btn-cancel">Cancelar</button>
                    <button class="toast-btn-confirm">Confirmar</button>
                </div>
            </div>
        `;

        const close = () => { overlay.remove(); };
        overlay.querySelector('.toast-btn-cancel').addEventListener('click', () => {
            close(); onCancel?.();
        });
        overlay.querySelector('.toast-btn-confirm').addEventListener('click', () => {
            close(); onConfirm?.();
        });
        // Close on backdrop click
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) { close(); onCancel?.(); }
        });

        document.body.appendChild(overlay);
    }

    // ── Flash message bridge (Spring Boot migration) ─────────
    // If Thymeleaf renders <div id="flashMsg" data-type="success">Texto</div>,
    // this picks it up automatically and shows a toast.
    document.addEventListener('DOMContentLoaded', () => {
        const flash = document.getElementById('flashMsg');
        if (flash) {
            show(flash.textContent.trim(), flash.dataset.type || 'info');
            flash.remove();
        }
    });

    window.PawsMatchToast = { show, confirm };
})();
