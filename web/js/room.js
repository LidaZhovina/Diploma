function initCounter() {
    const buttonPlus  = document.getElementById('plus');
    const buttonMinus = document.getElementById('minus');
    const input       = document.querySelector('.field-number-guests');

    if (!buttonPlus || !buttonMinus || !input) return;

    const min = parseInt(input.getAttribute('min')) || 1;
    const max = parseInt(input.getAttribute('max')) || 5;

    function updateButtonStates() {
        const val = parseInt(input.value) || 0;
        buttonMinus.disabled = val <= min;
        buttonPlus.disabled  = val >= max;
    }

    updateButtonStates();

    buttonPlus.onclick = () => {
        let v = parseInt(input.value) || 0;
        if (v < max) { input.value = v + 1; updateButtonStates(); }
    };

    buttonMinus.onclick = () => {
        let v = parseInt(input.value) || 0;
        if (v > min) { input.value = v - 1; updateButtonStates(); }
    };
}

document.addEventListener('DOMContentLoaded', initCounter);

// Pjax пересоздаёт DOM — переинициализируем счётчик
$(document).on('pjax:success', initCounter);