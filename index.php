<?php
session_start();
require 'config/config.php';

// Ajuste na consulta para evitar duplicatas
$query = "SELECT DISTINCT E.EMPRESA, E.RAZAO_SOCIAL, C.DESCRICAO_CIDADE 
          FROM EMPRESA E 
          JOIN CIDADE C ON E.CIDADE = C.CIDADE";
$stmt = $pdo->prepare($query);
$stmt->execute();
$empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolha de Empresa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5 text-center">
        <h1>Escolha uma Empresa</h1>
        <div class="row justify-content-center">
            <?php foreach ($empresas as $empresa): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= $empresa['RAZAO_SOCIAL'] ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?= $empresa['DESCRICAO_CIDADE'] ?></h6>
                            <form action="produtos.php" method="POST">
                                <input type="hidden" name="empresa" value="<?= $empresa['EMPRESA'] ?>">
                                <button type="submit" class="btn btn-primary">Selecionar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>