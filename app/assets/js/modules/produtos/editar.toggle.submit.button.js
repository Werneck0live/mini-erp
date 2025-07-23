document.getElementById('add-variacao').addEventListener('click', function () {
    const container = document.getElementById('variacoes-container');

    const row = document.createElement('div');
    row.classList.add('row', 'mb-2', 'variacao-item');
    row.innerHTML = `
        <div class="col-md-4">
            <input type="text" class="form-control" name="variacoes[${variacaoIndex}][descricao]" placeholder="Descrição" required>
        </div>
        <div class="col-md-4">
            <input type="number" class="form-control" name="variacoes[${variacaoIndex}][estoque]" placeholder="Estoque" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-variacao">Remover</button>
        </div>
    `;
    container.appendChild(row);
    variacaoIndex++;

    toggleSubmit();
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-variacao')) {
        e.target.closest('.variacao-item').remove();
        toggleSubmit();
    }
});

function toggleSubmit() {
    const btnContainer = document.getElementById('submit-container');
    const count = document.querySelectorAll('.variacao-item').length;
    btnContainer.style.display = count > 0 ? 'block' : 'none';
}