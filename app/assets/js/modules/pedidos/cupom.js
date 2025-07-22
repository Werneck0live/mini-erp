
let descontoAplicado = 0;
let cupomAtual = null;

function aplicarCupom() {
    const input = document.getElementById('codigo-cupom');
    const codigo = input.value.trim();

    // Converte para número float logo após limpar a string
    let subtotal = parseFloat(
        document.getElementById('subtotal').textContent.replace('R$', '').replace(',', '.').trim()
    );

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

        const descontoAplicado = parseFloat(data.desconto);;

        // Recalcula frete baseado no subtotal
        let frete = 20;
        if (subtotal >= 52 && subtotal <= 166.59) frete = 15;
        else if (subtotal > 200) frete = 0;

        // Garante que tudo é número
        const novoTotal = parseFloat((subtotal + frete - descontoAplicado).toFixed(2));

        // Atualiza valores na tela
        document.getElementById('desconto').innerText = descontoAplicado.toFixed(2).replace('.', ',');
        document.getElementById('linha-desconto').style.display = 'block';
        document.getElementById('total').innerText = novoTotal.toFixed(2).replace('.', ',');
        document.getElementById('mensagem-cupom').innerText = 'Cupom aplicado com sucesso!';

        // Bloqueia o campo e adiciona botão de remoção
        input.readOnly = true;
        if (!document.getElementById('remover-cupom')) {
            input.insertAdjacentHTML('afterend', `<button id="remover-cupom" onclick="removerCupom()" class="btn btn-danger btn-sm ms-2" style="margin-left: 4px;">X</button>`);
        }

        document.querySelectorAll('.qtd-input').forEach(input => {
            input.setAttribute('readonly', true);
        });
        
        document.querySelectorAll('a.btn-danger').forEach(btn => btn.classList.add('disabled'));
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
    // document.getElementById('total').innerText = subtotal.toFixed(2).replace('.', ',');
    let subtotalText = document.getElementById('subtotal').textContent.replace('R$', '').replace(',', '.').trim();
    let subtotal = parseFloat(subtotalText);
    document.getElementById('total').innerText = subtotal.toFixed(2).replace('.', ',');
    document.getElementById('mensagem-cupom').innerText = '';
    document.getElementById('linha-desconto').style.display = 'none';

    input.readOnly = false;
    input.value = '';

    if (removerBtn) removerBtn.remove();

    document.querySelectorAll('.qtd-input').forEach(input => {
        input.removeAttribute('readonly');
    });
    document.querySelectorAll('a.btn-danger').forEach(btn => btn.classList.remove('disabled'));
}