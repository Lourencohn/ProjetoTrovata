<?php
require_once 'config/database.php';
require_once 'controllers/EmpresaController.php';
require_once 'controllers/ProdutoController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri == '/empresa/index' || $uri == '/') {
    $controller = new EmpresaController();
    $controller->index();
} elseif ($uri == '/produto/index') {
    $controller = new ProdutoController();
    $controller->index();
} elseif (preg_match('/^\/empresa\/select\/(\d+)$/', $uri, $matches)) {
    $controller = new EmpresaController();
    $controller->select($matches[1]);
} elseif ($uri == '/produto/create') {
    $controller = new ProdutoController();
    $controller->create();
} elseif (preg_match('/^\/produto\/edit\/(\d+)$/', $uri, $matches)) {
    $controller = new ProdutoController();
    $controller->edit($matches[1]);
} elseif (preg_match('/^\/produto\/delete\/(\d+)$/', $uri, $matches)) {
    $controller = new ProdutoController();
    $controller->delete($matches[1]);
} else {
    echo "Rota não encontrada!";
}
?>