<?php
session_start();
require 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['empresa'] = $_POST['empresa'];
}
$empresaId = $_SESSION['empresa'] ?? null;

if (!$empresaId) {
    header("Location: index.php");
    exit;
}

$page = $_GET['page'] ?? 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Filtros e Ordenação
$filterDescricao = $_GET['filtro_descricao'] ?? '';
$filterApelido = $_GET['filtro_apelido'] ?? '';
$filterCodigo = $_GET['filtro_codigo'] ?? '';
$filterGrupoProduto = $_GET['filtro_grupo_produto'] ?? '';
$order = $_GET['order'] ?? 'descricao';

// Consulta para contar o total de produtos
$queryTotal = "SELECT COUNT(*) as total FROM PRODUTO WHERE EMPRESA = :empresa
               AND DESCRICAO_PRODUTO LIKE :filtro_descricao
               AND APELIDO_PRODUTO LIKE :filtro_apelido
               AND PRODUTO LIKE :filtro_codigo
               AND GRUPO_PRODUTO IN (SELECT GRUPO_PRODUTO FROM GRUPO_PRODUTO WHERE DESCRICAO_GRUPO_PRODUTO LIKE :filtro_grupo_produto)";
$stmtTotal = $pdo->prepare($queryTotal);
$stmtTotal->execute([
    ':empresa' => $empresaId,
    ':filtro_descricao' => '%' . $filterDescricao . '%',
    ':filtro_apelido' => '%' . $filterApelido . '%',
    ':filtro_codigo' => '%' . $filterCodigo . '%',
    ':filtro_grupo_produto' => '%' . $filterGrupoProduto . '%'
]);
$total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

// Consulta para buscar os produtos com paginação
$query = "SELECT p.PRODUTO, p.DESCRICAO_PRODUTO, p.APELIDO_PRODUTO, p.PESO_LIQUIDO, g.DESCRICAO_GRUPO_PRODUTO 
          FROM PRODUTO p 
          JOIN GRUPO_PRODUTO g ON p.GRUPO_PRODUTO = g.GRUPO_PRODUTO
          WHERE p.EMPRESA = :empresa
            AND p.DESCRICAO_PRODUTO LIKE :filtro_descricao
            AND p.APELIDO_PRODUTO LIKE :filtro_apelido
            AND p.PRODUTO LIKE :filtro_codigo
            AND g.DESCRICAO_GRUPO_PRODUTO LIKE :filtro_grupo_produto
          ORDER BY " . ($order === 'codigo' ? 'p.PRODUTO' : 'p.DESCRICAO_PRODUTO') . "
          LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':empresa', $empresaId, PDO::PARAM_INT);
$stmt->bindValue(':filtro_descricao', '%' . $filterDescricao . '%', PDO::PARAM_STR);
$stmt->bindValue(':filtro_apelido', '%' . $filterApelido . '%', PDO::PARAM_STR);
$stmt->bindValue(':filtro_codigo', '%' . $filterCodigo . '%', PDO::PARAM_STR);
$stmt->bindValue(':filtro_grupo_produto', '%' . $filterGrupoProduto . '%', PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
        <div class="container mt-5 text-center">
        <h1>Produtos</h1>
        <a href="index.php" class="btn btn-secondary mb-3">Voltar</a>
        <a href="cadastrar.php" class="btn btn-primary mb-3">Cadastrar Produto</a>
        
        <form method="GET" class="row mb-3">
            <div class="col-md-3">
                <input type="text" class="form-control" name="filtro_descricao" placeholder="Filtrar por Descrição" value="<?= htmlspecialchars($filterDescricao) ?>">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="filtro_apelido" placeholder="Filtrar por Apelido" value="<?= htmlspecialchars($filterApelido) ?>">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="filtro_codigo" placeholder="Filtrar por Código" value="<?= htmlspecialchars($filterCodigo) ?>">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="filtro_grupo_produto" placeholder="Filtrar por Grupo de Produto" value="<?= htmlspecialchars($filterGrupoProduto) ?>">
            </div>
            <div class="col-md-3 mt-3">
                <select class="form-select" name="order">
                    <option value="descricao" <?= $order === 'descricao' ? 'selected' : '' ?>>Ordenar por Descrição</option>
                    <option value="codigo" <?= $order === 'codigo' ? 'selected' : '' ?>>Ordenar por Código</option>
                </select>
            </div>
            <div class="col-md-3 mt-3">
                <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
            </div>
        </form>
        
        <div class="row">
            <?php foreach ($produtos as $produto): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= $produto['DESCRICAO_PRODUTO'] ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">Código: <?= $produto['PRODUTO'] ?></h6>
                            <p class="card-text">
                                Apelido: <?= $produto['APELIDO_PRODUTO'] ?><br>
                                Peso Líquido: <?= $produto['PESO_LIQUIDO'] ?><br>
                                Grupo: <?= $produto['DESCRICAO_GRUPO_PRODUTO'] ?>
                            </p>
                            <a href="editar.php?produto=<?= $produto['PRODUTO'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="excluir.php?produto=<?= $produto['PRODUTO'] ?>" class="btn btn-danger btn-sm">Excluir</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= ceil($total / $limit); $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="produtos.php?page=<?= $i ?>&order=<?= $order ?>&filtro_descricao=<?= urlencode($filterDescricao) ?>&filtro_apelido=<?= urlencode($filterApelido) ?>&filtro_codigo=<?= urlencode($filterCodigo) ?>&filtro_grupo_produto=<?= urlencode($filterGrupoProduto) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</body>
</html>