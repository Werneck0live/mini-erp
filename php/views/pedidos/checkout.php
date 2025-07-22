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
    <div class="mb-3">
        <label for="cupom">Cupom de Desconto:</label>
        <input type="text" name="cupom" id="cupom" class="form-control form-control-sm w-25" placeholder="">
    </div>
<?php
    // Cálculo do frete inicial (será sobrescrito pelo JS ao carregar)
    if ($subtotal >= 52 && $subtotal <= 166.59) $frete = 15;
    elseif ($subtotal > 200) $frete = 0;
    else $frete = 20;
    $total = $subtotal + $frete;
    ?>

    <p>Subtotal: <span id="subtotal">R$ <?= number_format($subtotal, 2, ',', '.') ?></span></p>
    <p>Frete: <span id="frete">R$ <?= number_format($frete, 2, ',', '.') ?></span></p>
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

        let frete = 20;
        if (subtotal >= 52 && subtotal <= 166.59) frete = 15;
        else if (subtotal > 200) frete = 0;

        const total = subtotal + frete;

        document.getElementById('subtotal').textContent = 'R$ ' + subtotal.toFixed(2).replace('.', ',');
        document.getElementById('frete').textContent = 'R$ ' + frete.toFixed(2).replace('.', ',');
        document.getElementById('total').textContent = 'R$ ' + total.toFixed(2).replace('.', ',');

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