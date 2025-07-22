function atualizarTotais() {
    let subtotal = 0;

    document.querySelectorAll('.item-linha').forEach((row) => {
        const preco = parseFloat(row.dataset.preco);
        const estoque = parseInt(row.dataset.estoque);
        const qtdInput = row.querySelector('.qtd-input');

        let qtd = parseInt(qtdInput.value);
        if (isNaN(qtd) || qtd < 1) qtd = 1;

        // Verifica limite de estoque
        if (qtd > estoque) {
            qtd = estoque;
            qtdInput.value = estoque;
            alert(`A quantidade máxima disponível é ${estoque}`);
        }

        const subtotalItem = preco * qtd;
        subtotal += subtotalItem;

        row.querySelector('.subtotal-item').textContent = 'R$ ' + subtotalItem.toFixed(2).replace('.', ',');
    });

    // Calcula frete baseado no subtotal
    let frete = 20;
    if (subtotal >= 52 && subtotal <= 166.59) frete = 15;
    else if (subtotal > 200) frete = 0;

    let total = subtotal + frete;

    // Aplica desconto se houver
    const descontoElement = document.getElementById('desconto');
    const descontoLinha = document.getElementById('linha-desconto');

    if (descontoElement && descontoLinha.style.display !== 'none') {
        let descontoValor = parseFloat(descontoElement.innerText.replace(',', '.'));
        if (!isNaN(descontoValor) && descontoValor > 0) {
            total -= descontoValor;
        }
    }

    // Atualiza visual
    document.getElementById('subtotal').textContent = 'R$ ' + subtotal.toFixed(2).replace('.', ',');
    document.getElementById('frete').textContent = 'R$ ' + frete.toFixed(2).replace('.', ',');
    document.getElementById('total').textContent = 'R$ ' + total.toFixed(2).replace('.', ',');

    // Atualiza inputs ocultos
    document.querySelector('input[name="frete"]').value = frete;
    document.querySelector('input[name="total"]').value = total;
}


// Atualizar ao digitar
document.querySelectorAll('.qtd-input').forEach(input => {
    input.addEventListener('input', atualizarTotais);
});

atualizarTotais(); // inicializar ao carregar

// Busca de endereço via CEP
document.getElementById('cep').addEventListener('blur', function () {
    let cep = this.value.replace(/\D/g, '');
    if (cep.length === 8) {
        fetch('https://viacep.com.br/ws/' + cep + '/json/')
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('endereco').value =
                        `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                }
            });
    }
});