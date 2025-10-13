const CityLinkToast = {
    container: null,

    init() {
        this.container = document.querySelector('.toast-container');
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'toast-container position-fixed top-0 end-0 p-3';
            this.container.style.zIndex = '9999';
            document.body.appendChild(this.container);
        }
    },

    show(type, message, title = '', duration = 5000) {
        this.init();

        const icons = {
            success: 'ph-check-circle',
            error: 'ph-x-circle',
            warning: 'ph-warning-circle',
            info: 'ph-info'
        };

        const colors = {
            success: 'success',
            error: 'danger',
            warning: 'warning',
            info: 'info'
        };

        const bgColors = {
            success: 'bg-success',
            error: 'bg-danger',
            warning: 'bg-warning',
            info: 'bg-info'
        };

        const icon = icons[type] || icons.info;
        const color = colors[type] || colors.info;
        const bgColor = bgColors[type] || bgColors.info;

        const toastId = 'toast-' + Date.now();
        
        const toastHTML = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgColor} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="${icon} ph-lg me-3"></i>
                        <div class="flex-fill">
                            ${title ? `<div class="fw-semibold mb-1">${title}</div>` : ''}
                            <div>${message}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;

        this.container.insertAdjacentHTML('beforeend', toastHTML);

        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: duration
        });

        toast.show();

        // Remover o toast do DOM após esconder
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    },

    success(message, title = 'Sucesso!', duration = 5000) {
        this.show('success', message, title, duration);
    },

    error(message, title = 'Erro!', duration = 7000) {
        this.show('error', message, title, duration);
    },

    warning(message, title = 'Atenção!', duration = 6000) {
        this.show('warning', message, title, duration);
    },

    info(message, title = 'Informação', duration = 5000) {
        this.show('info', message, title, duration);
    }
};

// Tornar disponível globalmente
window.toast = CityLinkToast;

// Auto-inicializar
document.addEventListener('DOMContentLoaded', function() {
    CityLinkToast.init();
});
