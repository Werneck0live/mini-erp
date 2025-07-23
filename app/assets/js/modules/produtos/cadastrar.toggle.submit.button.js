let indice = 0;

function adicionarVariacao() {
    const container = document.getElementById('variacoes-container');

    const bloco = document.createElement('div');
    bloco.classList.add('row', 'mb-3', 'variacao-item');
    bloco.innerHTML = `
        <div class="col-md-3">
            <label>Descriação</label>
            <input type="text" class="form-control" name="variacoes[${indice}][descricao]" required>
        </div>
        <div class="col-md-2">
            <label>Estoque</label>
            <input type="number" class="form-control" name="variacoes[${indice}][estoque]" min="0" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger" onclick="removerVariacao(this)">Remover</button>
        </div>
    `;
    container.appendChild(bloco);
    indice++;

    atualizarBotaoCadastrar();
}

function removerVariacao(btn) {
    btn.closest('.variacao-item').remove();
    atualizarBotaoCadastrar();
}

function atualizarBotaoCadastrar() {
    const btnCadastrar = document.getElementById('btnCadastrar');
    const totalVariacoes = document.querySelectorAll('.variacao-item').length;

    if (totalVariacoes > 0) {
        btnCadastrar.classList.remove('d-none');
    } else {
        btnCadastrar.classList.add('d-none');
    }
}