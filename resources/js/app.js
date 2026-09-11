const onReady = (callback) => {
    if (document.readyState !== 'loading') {
        callback();
    } else {
        document.addEventListener('DOMContentLoaded', callback);
    }
};

onReady(() => {
    // Password visibility toggles.
    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.togglePassword);
            if (!input) {
                return;
            }

            const reveal = input.type === 'password';
            input.type = reveal ? 'text' : 'password';
            button.setAttribute('aria-pressed', String(reveal));
            button.querySelector('[data-eye]')?.classList.toggle('hidden', reveal);
            button.querySelector('[data-eye-off]')?.classList.toggle('hidden', !reveal);
        });
    });

    // Disable submit buttons and show a spinner while the request is in flight.
    document.querySelectorAll('form[data-submit]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');

            if (!button || button.disabled) {
                return;
            }

            button.disabled = true;
            button.classList.add('opacity-60', 'cursor-not-allowed');

            if (!button.querySelector('[data-spinner]')) {
                const spinner = document.createElement('span');
                spinner.dataset.spinner = '';
                spinner.className = 'w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin';
                button.prepend(spinner);
            }
        });
    });

    // Mobile sidebar drawer.
    const sidebar = document.getElementById('app-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');

    document.querySelectorAll('[data-sidebar-toggle]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            sidebar?.classList.toggle('-translate-x-full');
            backdrop?.classList.toggle('hidden');
        });
    });

    backdrop?.addEventListener('click', () => {
        sidebar?.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
    });

    // Generic dialogs used by the admin area.
    const openModal = (id) => {
        const modal = document.getElementById(id);

        if (!modal) {
            return;
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    const closeModal = (id) => {
        const modal = document.getElementById(id);

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    document.querySelectorAll('[data-modal-open]').forEach((trigger) => {
        trigger.addEventListener('click', () => openModal(trigger.dataset.modalOpen));
    });

    document.querySelectorAll('[data-modal-close]').forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            if (event.target !== trigger && !trigger.hasAttribute('data-modal-close-always')) {
                return;
            }

            closeModal(trigger.dataset.modalClose);
        });
    });

    document.querySelectorAll('[data-auto-open]').forEach((element) => {
        openModal(element.dataset.autoOpen);
    });
});
