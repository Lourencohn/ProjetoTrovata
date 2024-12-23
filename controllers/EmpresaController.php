<?php
require_once 'models/Empresa.php';

class EmpresaController {
    public function index() {
        $empresaModel = new Empresa();
        $empresas = $empresaModel->getAll();
        require 'views/empresa/index.php';
    }

    public function select($id) {
        session_start();
        $_SESSION['empresa_id'] = $id;
        header('Location: /produto/index');
    }
}
?>