<?php
require_once 'models/Produto.php';

class ProdutoController {
    public function index() {
        session_start();
        if (!isset($_SESSION['empresa_id'])) {
            header('Location: /empresa/index');
            exit();
        }

        $produtoModel = new Produto();
        $empresa_id = $_SESSION['empresa_id'];
        $order = $_GET['order'] ?? 'descricao';
        $limit = $_GET['limit'] ?? 15;
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * $limit;

        $produtos = $produtoModel->getAllByEmpresa($empresa_id, $order, $limit, $offset);
        require 'views/produto/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descricao = $_POST['descricao'];
            $apelido = $_POST['apelido'];
            $codigo = $_POST['codigo'];
            $empresa_id = $_SESSION['empresa_id'];
            $grupo_produto_id = $_POST['grupo_produto_id'];
            $tipo_complemento = $_POST['tipo_complemento'];

            $produtoModel = new Produto();
            $produtoModel->create($descricao, $apelido, $codigo, $empresa_id, $grupo_produto_id, $tipo_complemento);
            header('Location: /produto/index');
        } else {
            require 'views/produto/create.php';
        }
    }

    public function edit($id) {
        $produtoModel = new Produto();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descricao = $_POST['descricao'];
            $apelido = $_POST['apelido'];
            $codigo = $_POST['codigo'];
            $grupo_produto_id = $_POST['grupo_produto_id'];
            $tipo_complemento = $_POST['tipo_complemento'];

            $produtoModel->update($id, $descricao, $apelido, $codigo, $grupo_produto_id, $tipo_complemento);
            header('Location: /produto/index');
        } else {
            $produto = $produtoModel->getById($id);
            require 'views/produto/edit.php';
        }
    }

    public function delete($id) {
        $produtoModel = new Produto();
        $produtoModel->delete($id);
        header('Location: /produto/index');
    }
}
?>