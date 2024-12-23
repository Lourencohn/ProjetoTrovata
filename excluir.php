<?php
session_start();
require 'config/config.php';

if (!isset($_GET['produto'])) {
    header("Location: produtos.php");
    exit;
}

$produtoId = $_GET['produto'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = "DELETE FROM PRODUTO WHERE PRODUTO = :produto";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':produto' => $produtoId]);
    header("Location: produtos.php");
    exit;
}

$query = "SELECT DESCRICAO_PRODUTO FROM PRODUTO WHERE PRODUTO = :produto";
$stmt = $pdo->prepare($query);
$stmt->execute([':produto' => $produtoId]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Produto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Excluir Produto</h1>
        <p>Tem certeza de que deseja excluir o produto <strong><?= htmlspecialchars($produto['DESCRICAO_PRODUTO']) ?></strong>?</p>
        <form action="excluir.php?produto=<?= $produtoId ?>" method="POST">
            <button type="submit" class="btn btn-danger">Excluir</button>
            <a href="produtos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>