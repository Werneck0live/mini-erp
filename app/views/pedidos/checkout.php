<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../views/template.php';

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

<script src="../../assets/js/modules/pedidos/checkout.js"></script>
<script src="../../assets/js/modules/pedidos/cupom.js"></script>