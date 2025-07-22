INSERT INTO produtos (nome, preco, descricao, `status`) VALUES
('Produto A', 10.00, 'Descrição do Produto A', 'ativo'),
('Produto B', 20.00, 'Descrição do Produto B', 'ativo'),
('Produto C', 15.50, 'Descrição do Produto C', 'ativo'),
('Produto D', 8.75, 'Descrição do Produto D', 'ativo'),
('Produto E', 18.30, 'Descrição do Produto E', 'ativo'),
('Produto F', 30.00, 'Descrição do Produto F', 'ativo'),
('Produto G', 12.45, 'Descrição do Produto G', 'ativo'),
('Produto H', 25.50, 'Descrição do Produto H', 'ativo'),
('Produto I', 13.99, 'Descrição do Produto I', 'ativo'),
('Produto J', 9.99, 'Descrição do Produto J', 'ativo');

INSERT INTO estoque (produto_id, quantidade) VALUES
(1, 100),
(2, 200),
(3, 150),
(4, 80),
(5, 120),
(6, 60),
(7, 90),
(8, 110),
(9, 130),
(10, 140);

INSERT INTO cupons (codigo, `status`, valor_minimo, percentual, validade) VALUES
('CUPOM10', 'ativo', 50.00, 10, '2025-12-31'),
('CUPOM20', 'ativo', 100.00, 20, '2025-12-31'),
('CUPOM30', 'ativo', 150.00, 30, '2025-12-31');

INSERT INTO pedidos (subtotal, frete, valor_desconto, total, cep, status, endereco, email_cliente) VALUES
(150.00, 15.00, 10.00, 155.00, '12345-678', 'pendente', 'Rua A, 123', 'cliente1@example.com'),
(200.00, 20.00, 20.00, 200.00, '98765-432', 'inativo', 'Rua B, 456', 'cliente2@example.com'),
(250.00, 25.00, 25.00, 250.00, '54321-987', 'em rota', 'Rua C, 789', 'cliente3@example.com'),
(300.00, 30.00, 30.00, 300.00, '11111-222', 'finalizado', 'Rua D, 321', 'cliente4@example.com'),
(400.00, 40.00, 40.00, 400.00, '33333-444', 'pendente', 'Rua E, 654', 'cliente5@example.com'),
(500.00, 50.00, 50.00, 500.00, '55555-666', 'pendente', 'Rua F, 987', 'cliente6@example.com'),
(600.00, 60.00, 60.00, 600.00, '77777-888', 'finalizado', 'Rua G, 111', 'cliente7@example.com'),
(700.00, 70.00, 70.00, 700.00, '99999-000', 'pendente', 'Rua H, 222', 'cliente8@example.com'),
(800.00, 80.00, 80.00, 800.00, '11111-000', 'inativo', 'Rua I, 333', 'cliente9@example.com'),
(900.00, 90.00, 90.00, 900.00, '22222-111', 'pendente', 'Rua J, 444', 'cliente10@example.com');

INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES
(1, 1, 2, 10.00),
(2, 2, 3, 20.00),
(3, 3, 4, 15.50),
(4, 4, 5, 8.75),
(5, 5, 6, 18.30),
(6, 6, 7, 30.00),
(7, 7, 8, 12.45),
(8, 8, 9, 25.50),
(9, 9, 10, 13.99),
(10, 10, 11, 9.99);