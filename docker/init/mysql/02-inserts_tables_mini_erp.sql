-- PRODUTOS
INSERT INTO produtos (id, nome, preco, descricao, status, id_produto_pai) VALUES
(1, 'Camiseta Algodão', 39.90, NULL, 'ativo', NULL),
(2, 'Calça Jeans Slim', 99.90, NULL, 'ativo', NULL),
(3, 'Tênis Esportivo', 149.90, NULL, 'ativo', NULL),
(4, 'Jaqueta Corta-Vento', 189.00, NULL, 'ativo', NULL),
(5, 'Boné Casual', 29.00, NULL, 'ativo', NULL),
(6, 'Relógio Digital', 199.90, NULL, 'ativo', NULL),
(7, 'Mochila Escolar', 89.90, NULL, 'ativo', NULL),
(8, 'Carteira Couro', 59.90, NULL, 'ativo', NULL);

INSERT INTO produtos (id, nome, preco, descricao, status, id_produto_pai) VALUES
(9, 'Camiseta Algodão', NULL, 'com estampa frontal', 'ativo', 1),
(10, 'Camiseta Algodão', NULL, 'com estampa nas costas', 'ativo', 1),
(11, 'Camiseta Algodão', NULL, 'cor preta', 'ativo', 1),
(12, 'Camiseta Algodão', NULL, 'cor branca', 'ativo', 1),
(13, 'Camiseta Algodão', NULL, 'tamanho G', 'ativo', 1);

INSERT INTO produtos (id, nome, preco, descricao, status, id_produto_pai) VALUES
(14, 'Calça Jeans Slim', NULL, 'lavagem escura', 'ativo', 2),
(15, 'Calça Jeans Slim', NULL, 'lavagem clara', 'ativo', 2),
(16, 'Calça Jeans Slim', NULL, 'tamanho 42', 'ativo', 2),
(17, 'Calça Jeans Slim', NULL, 'tamanho 44', 'ativo', 2),
(18, 'Calça Jeans Slim', NULL, 'modelagem skinny', 'ativo', 2),
(19, 'Calça Jeans Slim', NULL, 'cintura alta', 'ativo', 2);

INSERT INTO produtos (id, nome, preco, descricao, status, id_produto_pai) VALUES
(20, 'Tênis Esportivo', NULL, 'cor branca', 'ativo', 3),
(21, 'Tênis Esportivo', NULL, 'cor azul', 'ativo', 3),
(22, 'Tênis Esportivo', NULL, 'número 38', 'ativo', 3),
(23, 'Tênis Esportivo', NULL, 'número 40', 'ativo', 3),
(24, 'Tênis Esportivo', NULL, 'leve', 'ativo', 3),
(25, 'Tênis Esportivo', NULL, 'com amortecimento', 'ativo', 3),
(26, 'Tênis Esportivo', NULL, 'versão corrida', 'ativo', 3);

INSERT INTO produtos (id, nome, preco, descricao, status, id_produto_pai) VALUES
(27, 'Jaqueta Corta-Vento', NULL, 'impermeável', 'ativo', 4),
(28, 'Jaqueta Corta-Vento', NULL, 'com capuz', 'ativo', 4),
(29, 'Jaqueta Corta-Vento', NULL, 'cor cinza', 'ativo', 4),
(30, 'Jaqueta Corta-Vento', NULL, 'tamanho P', 'ativo', 4),
(31, 'Jaqueta Corta-Vento', NULL, 'corte slim', 'ativo', 4);

INSERT INTO produtos (id, nome, preco, descricao, status, id_produto_pai) VALUES
(32, 'Boné Casual', NULL, 'cor preta', 'ativo', 5),
(33, 'Boné Casual', NULL, 'cor azul', 'ativo', 5),
(34, 'Boné Casual', NULL, 'ajuste de velcro', 'ativo', 5),
(35, 'Boné Casual', NULL, 'ajuste de botão', 'ativo', 5),
(36, 'Boné Casual', NULL, 'com logo bordado', 'ativo', 5);

INSERT INTO produtos (id, nome, preco, descricao, status, id_produto_pai) VALUES
(37, 'Relógio Digital', NULL, 'com cronômetro', 'ativo', 6),
(38, 'Relógio Digital', NULL, 'resistente à água', 'ativo', 6),
(39, 'Relógio Digital', NULL, 'pulseira de silicone', 'ativo', 6),
(40, 'Relógio Digital', NULL, 'pulseira de aço', 'ativo', 6),
(41, 'Relógio Digital', NULL, 'cor preta', 'ativo', 6),
(42, 'Relógio Digital', NULL, 'modelo esportivo', 'ativo', 6);

INSERT INTO produtos (id, nome, preco, descricao, status, id_produto_pai) VALUES
(43, 'Mochila Escolar', NULL, 'com rodinhas', 'ativo', 7),
(44, 'Mochila Escolar', NULL, 'com bolso lateral', 'ativo', 7),
(45, 'Mochila Escolar', NULL, 'cor azul', 'ativo', 7),
(46, 'Mochila Escolar', NULL, 'alça acolchoada', 'ativo', 7),
(47, 'Mochila Escolar', NULL, 'capacidade 25L', 'ativo', 7);

INSERT INTO produtos (id, nome, preco, descricao, status, id_produto_pai) VALUES
(48, 'Carteira Couro', NULL, 'com porta-cartões', 'ativo', 8),
(49, 'Carteira Couro', NULL, 'dobrável', 'ativo', 8),
(50, 'Carteira Couro', NULL, 'cor marrom', 'ativo', 8),
(51, 'Carteira Couro', NULL, 'com zíper', 'ativo', 8),
(52, 'Carteira Couro', NULL, 'com clipe de dinheiro', 'ativo', 8),
(53, 'Carteira Couro', NULL, 'modelo slim', 'ativo', 8),
(54, 'Carteira Couro', NULL, 'couro ecológico', 'ativo', 8);

-- ESTOQUE
INSERT INTO estoque (produto_id, quantidade) VALUES
(9, 10), (10, 8), (11, 12), (12, 15), (13, 6),
(14, 14), (15, 9), (16, 11), (17, 13), (18, 7), (19, 5),
(20, 8), (21, 6), (22, 10), (23, 4), (24, 12), (25, 3), (26, 9),
(27, 6), (28, 7), (29, 5), (30, 4), (31, 10),
(32, 20), (33, 15), (34, 18), (35, 13), (36, 9),
(37, 7), (38, 6), (39, 10), (40, 8), (41, 12), (42, 5),
(43, 10), (44, 11), (45, 9), (46, 8), (47, 6),
(48, 12), (49, 10), (50, 9), (51, 7), (52, 5), (53, 4), (54, 3);

-- CUPONS
INSERT INTO cupons (codigo, status, valor_minimo, percentual, validade) VALUES
('DESCONTO10', 'ativo', 100.00, 10, '2025-12-31'),
('VIP20', 'ativo', 200.00, 20, '2025-11-30'),
('FRETEGRATIS', 'inativo', 150.00, 15, '2024-12-31');

INSERT INTO pedidos (subtotal, frete, valor_desconto, total, cep, status, endereco, email_cliente) VALUES
(100.00, 15.00, 10.00, 105.00, '12345-000', 'pendente', 'Rua A, 123', 'cliente1@email.com'),
(200.00, 20.00, 0.00, 220.00, '23456-000', 'em rota', 'Rua B, 456', 'cliente2@email.com'),
(150.00, 10.00, 15.00, 145.00, '34567-000', 'inativo', 'Rua C, 789', 'cliente3@email.com'),
(300.00, 25.00, 30.00, 295.00, '45678-000', 'finalizado', 'Rua D, 321', 'cliente4@email.com'),
(110.00, 10.00, 0.00, 120.00, '56789-000', 'em separação', 'Rua E, 654', 'cliente5@email.com'),
(80.00, 12.00, 5.00, 87.00, '67890-000', 'pendente', 'Rua F, 987', 'cliente6@email.com'),
(190.00, 20.00, 10.00, 200.00, '78901-000', 'finalizado', 'Rua G, 111', 'cliente7@email.com'),
(250.00, 30.00, 25.00, 255.00, '89012-000', 'inativo', 'Rua H, 222', 'cliente8@email.com'),
(170.00, 15.00, 0.00, 185.00, '90123-000', 'em rota', 'Rua I, 333', 'cliente9@email.com'),
(140.00, 10.00, 10.00, 140.00, '01234-000', 'pendente', 'Rua J, 444', 'cliente10@email.com'),
(210.00, 25.00, 15.00, 220.00, '11223-000', 'finalizado', 'Rua K, 555', 'cliente11@email.com'),
(95.00, 10.00, 5.00, 100.00, '22334-000', 'em separação', 'Rua L, 666', 'cliente12@email.com'),
(175.00, 20.00, 10.00, 185.00, '33445-000', 'em rota', 'Rua M, 777', 'cliente13@email.com'),
(160.00, 12.00, 0.00, 172.00, '44556-000', 'inativo', 'Rua N, 888', 'cliente14@email.com'),
(120.00, 15.00, 10.00, 125.00, '55667-000', 'pendente', 'Rua O, 999', 'cliente15@email.com'),
(90.00, 10.00, 0.00, 100.00, '66778-000', 'finalizado', 'Rua P, 000', 'cliente16@email.com'),
(130.00, 20.00, 15.00, 135.00, '77889-000', 'em rota', 'Rua Q, 123', 'cliente17@email.com'),
(260.00, 25.00, 0.00, 285.00, '88990-000', 'pendente', 'Rua R, 234', 'cliente18@email.com'),
(175.00, 15.00, 10.00, 180.00, '99001-000', 'em separação', 'Rua S, 345', 'cliente19@email.com'),
(140.00, 18.00, 5.00, 153.00, '10112-000', 'finalizado', 'Rua T, 456', 'cliente20@email.com');