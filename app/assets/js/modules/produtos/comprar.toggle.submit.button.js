function toggleSubmitButton() {
    const anyChecked = document.querySelectorAll('.toggle-variacao:checked').length > 0;
    document.getElementById('submit-container').style.display = anyChecked ? 'block' : 'none';
}

document.querySelectorAll('.toggle-variacao').forEach(function(checkbox) {
    checkbox.addEventListener('change', function () {
        const index = this.dataset.index;
        const input = document.getElementById('quantidade-' + index);
        input.disabled = !this.checked;

        if (this.checked) {
            input.focus();
            input.select(); // opcional: já seleciona o número atual
        }

        toggleSubmitButton();
    });
});

document.querySelectorAll('.quantidade-input').forEach(function (input) {
    input.addEventListener('input', function () {
        const max = parseInt(this.getAttribute('max'));
        let val = parseInt(this.value);

        if (!isNaN(val) && val > max) {
            this.value = max;
        }
    });
});

toggleSubmitButton();