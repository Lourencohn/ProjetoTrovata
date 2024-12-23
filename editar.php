<?php
session_start();
require 'config/config.php';

if (!isset($_GET['produto'])) {
    header("Location: produtos.php");
    exit;
}

$produtoId = $_GET['produto'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'];
    $apelido = $_POST['apelido'];
    $grupo_produto = $_POST['grupo_produto'];
    $peso_liquido = $_POST['peso_liquido'];

    $query = "UPDATE PRODUTO SET DESCRICAO_PRODUTO = :descricao, APELIDO_PRODUTO = :apelido,
              GRUPO_PRODUTO = :grupo_produto, PESO_LIQUIDO = :peso_liquido
              WHERE PRODUTO = :produto";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':descricao' => $descricao,
        ':apelido' => $apelido,
        ':grupo_produto' => $grupo_produto,
        ':peso_liquido' => $peso_liquido,
        ':produto' => $produtoId,
    ]);
    header("Location: produtos.php");
    exit;
}

$query = "SELECT * FROM PRODUTO WHERE PRODUTO = :produto";
$stmt = $pdo->prepare($query);
$stmt->execute([':produto' => $produtoId]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <title>Editar Produto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Produto</h1>
        <form action="editar.php?produto=<?= $produtoId ?>" method="POST">
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <input type="text" class="form-control" id="descricao" name="descricao" value="<?= htmlspecialchars($produto['DESCRICAO_PRODUTO']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="apelido" class="form-label">Apelido</label>
                <input type="text" class="form-control" id="apelido" name="apelido" value="<?= htmlspecialchars($produto['APELIDO_PRODUTO']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="grupo_produto" class="form-label">Grupo de Produto</label>
                <select class="form-select" id="grupo_produto" name="grupo_produto" required>
                    <?php foreach ($grupos as $grupo): ?>
                        <option value="<?= $grupo['GRUPO_PRODUTO'] ?>" <?= $produto['GRUPO_PRODUTO'] == $grupo['GRUPO_PRODUTO'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($grupo['DESCRICAO_GRUPO_PRODUTO']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="peso_liquido" class="form-label">Peso Líquido</label>
                <input type="number" step="0.001" class="form-control" id="peso_liquido" name="peso_liquido" value="<?= htmlspecialchars($produto['PESO_LIQUIDO']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="produtos.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</body>
</html>