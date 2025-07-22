
let descontoAplicado = 0;
let cupomAtual = null;

function aplicarCupom() {
    const input = document.getElementById('codigo-cupom');
    const codigo = input.value.trim();

    if (!codigo) {
        document.getElementById('mensagem-cupom').innerText = 'Digite um código.';
        return;
    }

    fetch('../cupom/validar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            codigo: codigo,
            subtotal: subtotal
        })
    })
    .then(response => response.json())
    .then(data => {
        
        if (!data.sucesso) {
            document.getElementById('mensagem-cupom').innerText = data.mensagem;
            return;
        }

        descontoAplicado = data.desconto;
        const novoTotal = subtotal - descontoAplicado;
        // Atualiza valores
        
        document.getElementById('desconto').innerText = descontoAplicado.toFixed(2).replace('.', ',');
        document.getElementById('linha-desconto').style.display = 'block';
        document.getElementById('total').innerText = novoTotal.toFixed(2).replace('.', ',');
        document.getElementById('mensagem-cupom').innerText = 'Cupom aplicado com sucesso!';
        
        // Bloqueia input e adiciona botão X
        input.readOnly = true;
        if (!document.getElementById('remover-cupom')) {
            // input.insertAdjacentHTML('afterend', `<button id="remover-cupom" onclick="removerCupom()" style="margin-left: 5px;">X</button>`);
            input.insertAdjacentHTML('afterend', `<button id="remover-cupom" onclick="removerCupom()" class="btn btn-danger btn-sm ms-2" style="margin-left: 4px;">X</button>`);
        }

        cupomAtual = codigo;
    })
    .catch(() => {
        document.getElementById('mensagem-cupom').innerText = 'Erro ao validar cupom.';
    });
}

function removerCupom() {
    const input = document.getElementById('codigo-cupom');
    const removerBtn = document.getElementById('remover-cupom');

    descontoAplicado = 0;
    cupomAtual = null;

    document.getElementById('desconto').innerText = '0,00';
    document.getElementById('total').innerText = subtotal.toFixed(2).replace('.', ',');
    document.getElementById('mensagem-cupom').innerText = '';
    document.getElementById('linha-desconto').style.display = 'none';

    input.readOnly = false;
    input.value = '';

    if (removerBtn) removerBtn.remove();
}