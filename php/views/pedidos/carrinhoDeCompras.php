<?php include 'views/template.php';?>
    <div class="container">
        <h1 class="my-4">Carrinho de Compras</h1>
        
        <?php
        
        if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }
        
        if (empty($_SESSION['carrinho'])) {
            echo "<p>Seu carrinho está vazio.</p>";
        } else {
            echo '<table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Produto</th>
                            <th scope="col">Quantidade</th>
                            <th scope="col">Preço</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            $subtotal = 0;
            
            foreach ($_SESSION['carrinho'] as $item) {
                $total_item = $item['quantidade'] * $item['preco_unitario'];
                $subtotal += $total_item;
                echo "<tr>
                        <td>{$item['nome']}</td>
                        <td>{$item['quantidade']}</td>
                        <td>R$ " . number_format($item['preco_unitario'], 2, ',', '.') . "</td>
                        <td>R$ " . number_format($total_item, 2, ',', '.') . "</td>
                    </tr>";
            }
            
            // Calcular o frete
            if ($subtotal >= 200.00) {
                $frete = 0;
            } elseif ($subtotal >= 52.00 && $subtotal <= 166.59) {
                $frete = 15.00;
            } else {
                $frete = 20.00;
            }
            
            $total = $subtotal + $frete;
            
            echo "</tbody>
                </table>";
            
            echo "<div class='row'>
                    <div class='col'>
                        <p><strong>Subtotal:</strong> R$ " . number_format($subtotal, 2, ',', '.') . "</p>
                        <p><strong>Frete:</strong> R$ " . number_format($frete, 2, ',', '.') . "</p>
                        <p><strong>Total:</strong> R$ " . number_format($total, 2, ',', '.') . "</p>
                    </div>
                </div>
                    <div class='row'>
                <div class='col'>
                    <a href='checkout.php' class='btn btn-success'>Finalizar Compra</a>
                </div>
            </div>";
        }
        ?>
        
        <div class="row">
            <div class="col">
                <a href="checkout.php" class="btn btn-success">Finalizar Compra</a>
            </div>
        </div>
    </div>
    
    <script src="../../assets/js/script.js"></script>
</body>
</html>