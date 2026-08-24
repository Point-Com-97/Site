function show_message(message, type = 'danger') {
    const container = document.getElementById('toast-container');

    const toast = document.createElement('div');
    toast.classList.add('toast', 'align-items-center', `text-bg-${type}`, 'border-0','position-fixed', 'bottom-0', 'end-0', 'p-1');
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');

    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body fs-6"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;

    // le message est injecté via textContent, pas innerHTML — pourquoi, à ton avis,
    // vu ce qu'on avait déjà appris avec sort.js ?
    toast.querySelector('.toast-body').textContent = message;

    container.appendChild(toast);

    const bsToast = new bootstrap.Toast(toast, { delay: 5000 });
    bsToast.show();

    // nettoyage : retire le toast du DOM une fois complètement caché
    toast.addEventListener('hidden.bs.toast', () => toast.remove());
}