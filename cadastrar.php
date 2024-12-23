<?php
session_start();
require 'config/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $descricao = $_POST['descricao'];
    $apelido = $_POST['apelido'];
    $grupo_produto = $_POST['grupo_produto'];
    $peso_liquido = $_POST['peso_liquido'];
    $empresa = $_SESSION['empresa'];

    // Verificar se o código do produto já está em uso
    $queryCheck = "SELECT COUNT(*) as count FROM PRODUTO WHERE PRODUTO = :codigo AND EMPRESA = :empresa";
    $stmtCheck = $pdo->prepare($queryCheck);
    $stmtCheck->execute([':codigo' => $codigo, ':empresa' => $empresa]);
    $count = $stmtCheck->fetch(PDO::FETCH_ASSOC)['count'];

    if ($count > 0) {
        $error = 'O código do produto já está em uso. Por favor, escolha outro código.';
    } else {
        $query = "INSERT INTO PRODUTO (PRODUTO, DESCRICAO_PRODUTO, APELIDO_PRODUTO, GRUPO_PRODUTO, PESO_LIQUIDO, EMPRESA)
                  VALUES (:codigo, :descricao, :apelido, :grupo_produto, :peso_liquido, :empresa)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':codigo' => $codigo,
            ':descricao' => $descricao,
            ':apelido' => $apelido,
            ':grupo_produto' => $grupo_produto,
            ':peso_liquido' => $peso_liquido,
            ':empresa' => $empresa,
        ]);
        header("Location: produtos.php");
        exit;
    }
}

$queryGrupos = "SELECT GRUPO_PRODUTO, DESCRICAO_GRUPO_PRODUTO FROM GRUPO_PRODUTO";
$stmtGrupos = $pdo->prepare($queryGrupos);
$stmtGrupos->execute();
$grupos = $stmtGrupos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1>Cadastrar Produto</h1>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
                <form action="cadastrar.php" method="POST">
                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" required>
                    </div>
                    <div class="mb-3">
                        <label for="apelido" class="form-label">Apelido</label>
                        <input type="text" class="form-control" id="apelido" name="apelido" required>
                    </div>
                    <div class="mb-3">
                        <label for="grupo_produto" class="form-label">Grupo de Produto</label>
                        <select class="form-select" id="grupo_produto" name="grupo_produto" required>
                            <?php foreach ($grupos as $grupo): ?>
                                <option value="<?= $grupo['GRUPO_PRODUTO'] ?>">
                                    <?= $grupo['DESCRICAO_GRUPO_PRODUTO'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="peso_liquido" class="form-label">Peso Líquido</label>
                        <input type="number" step="0.001" class="form-control" id="peso_liquido" name="peso_liquido" required>
                    </div>
                    <button type="submit" class="btn btn-success">Cadastrar</button>
                    <a href="produtos.php" class="btn btn-secondary">Voltar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>