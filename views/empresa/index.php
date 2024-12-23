<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Empresas</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Escolha a Empresa</h1>
        <ul class="list-group">
            <?php foreach ($empresas as $empresa): ?>
                <li class="list-group-item">
                    <a href="/empresa/select/<?php echo $empresa['id']; ?>">
                        <?php echo $empresa['razao_social']; ?> - <?php echo $empresa['cidade']; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>