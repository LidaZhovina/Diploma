document.addEventListener('DOMContentLoaded', function () {

    const previewNumber = document.getElementById('card-preview-number');
    const previewExpiry = document.getElementById('card-preview-expiry');
    const previewCvv    = document.getElementById('card-preview-cvv');
    const previewHolder = document.getElementById('card-preview-holder');

    const inputNumber = document.getElementById('cardpaymentform-card_number');
    const inputExpiry = document.getElementById('cardpaymentform-expiry');
    const inputCvv    = document.getElementById('cardpaymentform-cvv');
    const inputHolder = document.getElementById('card-holder');

    function on(el, handler) {
        if (!el) return;
        ['input', 'keyup', 'keydown', 'change', 'paste'].forEach(function (evt) {
            el.addEventListener(evt, handler);
        });
    }

    // ─── Номер карты ──────────────────────────────────────────────────────────
    on(inputNumber, function () {
        // Читаем значение через небольшую задержку
        const self = this;
        setTimeout(function () {
            const val = self.value.replace(/_/g, '•');
            previewNumber.textContent = val || '•••• •••• •••• ••••';
        }, 0);
    });

    // ─── Срок действия ────────────────────────────────────────────────────────
    on(inputExpiry, function () {
        const self = this;
        setTimeout(function () {
            const val = self.value.replace(/_/g, '•');
            previewExpiry.textContent = val || '••/••';
        }, 0);
    });

    // ─── CVV — всегда скрыт ───────────────────────────────────────────────────
    on(inputCvv, function () {
        const self = this;
        setTimeout(function () {
            const len = self.value.replace(/[_\s]/g, '').length;
            previewCvv.textContent = len ? '*'.repeat(len) : '•••';
        }, 0);
    });

    // ─── Имя держателя ────────────────────────────────────────────────────────
    on(inputHolder, function () {
        const val = this.value.toUpperCase().trim();
        previewHolder.textContent = val || 'IVAN IVANOV';
    });

});