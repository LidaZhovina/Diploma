const TOAST_ICONS = {
    info:    'ti-info-circle',
    success: 'ti-check',
    error:   'ti-x',
    warning: 'ti-alert-triangle',
};

function showToast(data) {
    if (!data) return;

    const type = data.type || 'info';
    const toast = document.createElement('div');
    toast.className = `app-toast ${type}`;
    toast.innerHTML = `
        <i class="ti ${TOAST_ICONS[type]} toast-icon"></i>
        <p class="toast-msg">${data.message}</p>
        <button class="toast-close" onclick="this.closest('.app-toast').remove()">✕</button>
    `;

    document.getElementById('toast-container').appendChild(toast);
    setTimeout(() => toast.remove(), 4500);
}

function checkFlash() {
    fetch('/site/get-flash')
        .then(r => r.json())
        .then(data => showToast(data))
        .catch(() => {});
}

document.addEventListener('DOMContentLoaded', checkFlash);
document.addEventListener('pjax:end', checkFlash);