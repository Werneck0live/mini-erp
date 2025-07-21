<?php 


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'views/template.php';

?>

<form action="/carrinho/atualizar" method="POST">
    <h4>Carrinho</h4>
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

        if (isset($_SESSION['carrinho']) && count($_SESSION['carrinho']) > 0) {
            foreach ($_SESSION['carrinho'] as $index => $item) {
                $itemSubtotal = $item['preco_unitario'] * $item['quantidade'];
                $subtotal += $itemSubtotal;
                echo "
                    <tr class='item-linha' data-id='". $item['id'] ."' data-preco='". $item['preco'] ."'>
                        <td>{$item['nome']}</td>
                        <td>R$ " . number_format($item['preco_unitario'], 2, ',', '.') . "</td>
                        <td>
                            <input type='number' name='quantidade[<?= $index ?>]' class='form-control qtd-input'
                                value='". $item['quantidade'] ."' min='1' max='". $_SESSION['estoque'][$item['id']] ?? 100 ."'
                                style='width: 80px;'>
                        </td>
                        <td class='subtotal-item'>R$ ". number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ."</td>
                        <td>
                            <a href='/carrinho/remover/" . $index ."' class='btn btn-danger btn-sm'>Remover</a>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Carrinho vazio.</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <!-- <button type="submit" class="btn btn-primary">Atualizar Carrinho</button> -->
</form>

<?php
// Cálculo do frete
if ($subtotal >= 52 && $subtotal <= 166.59) $frete = 15.00;
elseif ($subtotal > 200) $frete = 0;
else $frete = 20.00;

$total = $subtotal + $frete;
?>

<p>Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
<p>Frete: R$ <?= number_format($frete, 2, ',', '.') ?></p>
<p><strong>Total: R$ <?= number_format($total, 2, ',', '.') ?></strong></p>

<hr>

<!-- FORMULÁRIO FINAL DE ENVIO -->
<form action="/pedido/finalizar" method="POST">
    <input type="hidden" name="frete" value="<?= $frete ?>">
    <input type="hidden" name="total" value="<?= $total ?>">

    <div class="mb-3">
        <label for="cep">CEP:</label>
        <input type="text" name="cep" id="cep" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="endereco">Endereço:</label>
        <input type="text" name="endereco" id="endereco" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label for="email">E-mail:</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Finalizar Pedido</button>
</form>

<script>
const estoque = <?= json_encode($_SESSION['estoque']) ?>;

function atualizarTotais() {
    let subtotal = 0;
    document.querySelectorAll('.item-linha').forEach((row, index) => {
        const preco = parseFloat(row.dataset.preco);
        const qtdInput = row.querySelector('.qtd-input');
        let qtd = parseInt(qtdInput.value);

        const idProduto = row.dataset.id;

        // Respeitar estoque
        const maxEstoque = estoque[idProduto] ?? 1000; // fallback
        if (qtd > maxEstoque) {
            qtd = maxEstoque;
            qtdInput.value = maxEstoque;
            alert(`Quantidade máxima para este produto é ${maxEstoque}`);
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

    document.querySelector('input[name="total"]').value = total;
    document.querySelector('input[name="frete"]').value = frete;
}

document.querySelectorAll('.qtd-input').forEach(input => {
    input.addEventListener('input', atualizarTotais);
});

atualizarTotais();
</script>
