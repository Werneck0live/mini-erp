<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'views/template.php';

?>

<form action="/pedido/finalizar" method="POST">
    <br>    
    <h3>Carrinho</h3>
    <br>
    <table class="table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>

        <?php

        $subtotal = 0;

        if (isset($_SESSION['carrinho']) && count($_SESSION['carrinho']) > 0):
            foreach ($_SESSION['carrinho'] as $item):
                $itemSubtotal = $item['preco_unitario'] * $item['quantidade'];
                $subtotal += $itemSubtotal;
        ?>
            <tr class="item-linha"
                data-preco="<?= $item['preco_unitario'] ?>"
                data-estoque="<?= $item['estoque'] ?>">
                <td><?= $item['nome'] ?></td>
                <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                <td>
                    <input 
                           type="number"
                           name="quantidade[<?= $item['produto_id'] ?>]"
                           class="form-control qtd-input"
                           value="<?= $item['quantidade'] ?>"
                           min="1"
                           style="width: 80px;">
                </td>
                <td class="subtotal-item">
                    R$ <?= number_format($itemSubtotal, 2, ',', '.') ?>
                </td>
                <td>
                    <a href="/pedido/removerItem/<?= $item['produto_id'] ?>" class="btn btn-danger btn-sm">Remover</a>
                </td>
            </tr>
        <?php
            endforeach;
        ?>
        
    </table>
    
    <div id="cupom-container">
        <label for="codigo-cupom">Cupom:</label>
        <input name="codigo_cupom"type="text" id="codigo-cupom" placeholder="Digite o cupom">
        <button type="button" class="btn btn-link btn-sm ms-2" onclick="aplicarCupom()">Aplicar Cupom</button>
    </div>

    
    <p id="mensagem-cupom" style="color: green;"></p>
<?php
    // Cálculo do frete inicial (será sobrescrito pelo JS ao carregar)
    $frete = calcular_frete($subtotal);
    $total = $subtotal + $frete;
    ?>

    <p>Subtotal: <span id="subtotal">R$ <?= number_format($subtotal, 2, ',', '.') ?></span></p>
    <p>Frete: <span id="frete">R$ <?= number_format($frete, 2, ',', '.') ?></span></p>
    <p id="linha-desconto" style="display: none; font-weight: bold;">
        <span class="text-success me-2">Desconto: R$</span> 
        <span id="desconto" class="text-success">0,00</span>
    </p>
    <p><strong>Total: <span id="total">R$ <?= number_format($total, 2, ',', '.') ?></span></strong></p>

    <hr>
    <br>

    <input type="hidden" name="frete" value="<?= $frete ?>">
    <input type="hidden" name="total" value="<?= $total ?>">

    <div class="mb-3">
        <label for="cep">CEP:</label>
        <input type="text" name="cep" id="cep" class="form-control form-control-sm w-25" required>
    </div>

    <div class="mb-3">
        <label for="endereco">Endereço:</label>
        <input type="text" name="endereco" id="endereco" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label for="email">E-mail:</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <br>
    <br>
    <button type="submit" class="btn btn-success">Finalizar Pedido</button>
</form>
<?php

    else:
        echo "<tr><td colspan='5'>Carrinho vazio.</td></tr>";
    endif;

?>
</tbody>
<script>
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
</script>

<script>
    let subtotal = <?= $subtotal ?>;
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
            console.log(<?= __LINE__ ?>);
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
</script>