<?php

// Ex: /produtos/editar/3
$url = $_GET['url'] ?? '';

// Remove barras à direita e divide por "/"
$params = explode('/', rtrim($url, '/'));

// echo "<pre>";die(var_dump($params));

// Definir controlador e método padrão
$controllerName = !empty($params[0]) ? ucfirst($params[0]) . 'Controller' : 'HomeController';
$method = $params[1] ?? null;
$id = $params[2] ?? null;


// Caminho do controller
$controllerPath = 'controllers/' . $controllerName . '.php';

// echo "<pre>";die(var_dump(['controllerName' => $controllerName,'method' => $method,'id' => $id,'controllerPath' => $controllerPath]));

if ((!empty($controllerName) && !empty($method)) || file_exists($controllerPath)) {
    require_once $controllerPath;
    $controller = new $controllerName();

    if (method_exists($controller, $method)) {
        $controller->$method($id);
    } else {
        http_response_code(404);
        // echo "Método '$method' não encontrado no controlador '$controllerName'.";
        header("Location: ../views/produtos/cadastro.php");
    }
} else {
    http_response_code(404);
    // echo "Controlador '$controllerName' não encontrado.";
    header("Location: ../views/produtos/cadastro.php");
}
