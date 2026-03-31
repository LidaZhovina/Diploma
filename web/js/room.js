document.addEventListener('DOMContentLoaded', () => {
    const buttonPlus = document.getElementById('plus');
    const buttonMinus = document.getElementById('minus');
    const input = document.querySelector('.field-number-guests');

    const min = parseInt(input.getAttribute('min')) || 1;
    const max = parseInt(input.getAttribute('max')) || 5;

    function updateButtonStates() {
        const val = parseInt(input.value) || 0;
        buttonMinus.disabled = (val <= min);
        buttonPlus.disabled = (val >= max);
    }

    updateButtonStates();

    buttonPlus.addEventListener('click', () => {
        let currentValue = parseInt(input.value) || 0;
        if (currentValue < max) {
            input.value = currentValue + 1;
            // Передаем актуальное значение из инпута
            updateButtonStates(parseInt(input.value)); 
        }
    });

    buttonMinus.addEventListener('click', () => {
        let currentValue = parseInt(input.value) || 0;
        if (currentValue > min) {
            input.value = currentValue - 1;
            // Передаем актуальное значение из инпута
            updateButtonStates(parseInt(input.value));
        }
    });
})