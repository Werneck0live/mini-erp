<?php

require_once '../config/ErrorHandler.php';

$url = $_GET['url'] ?? '';

$params = explode('/', rtrim($url, '/'));

// Definir controlador e método padrão
$controllerName = !empty($params[0]) ? ucfirst($params[0]) . 'Controller' : 'HomeController';
$method = $params[1] ?? null;
$id = $params[2] ?? null;

// Switch para tratar o redirecionamento sem a necessidade de passar pelo Controller
switch ("$controllerName|$method") {
    case 'ProdutoController|cadastrar':
        require '../views/produtos/cadastrar.php';
        exit();
    case 'PedidoController|checkout':
        include '../config/helpers/frete.php';
        require '../views/pedidos/checkout.php';
        exit();
}

// Caminho do controller
$controllerPath = '../controllers/' . $controllerName . '.php';

if ((!empty($controllerName) && !empty($method)) || file_exists($controllerPath)) {
    require_once $controllerPath;
    $controller = new $controllerName();
    
    if (method_exists($controller, $method)) {
        $controller->$method($id);
    } else {
        // Rota padrão
        http_response_code(404);
        ErrorHandler::handleError("Caminho inválido", "../../produto/listarTodos");
    }
} else {
    http_response_code(404);
    ErrorHandler::handleError("Caminho inválido", "../../produto/listarTodos");
}
