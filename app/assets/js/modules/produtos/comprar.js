const inputNumero = document.getElementById('quantidade');

inputNumero.addEventListener('input', function () {
    const estoque = parseInt(document.getElementById('estoque').value);
    const qtd = parseInt(this.value);

    if (qtd > estoque) {
        alert('Quantidade solicitada excede o estoque disponível.');
        this.value = estoque;
    } else if (qtd < 1) {
        this.value = 1;
    }
});