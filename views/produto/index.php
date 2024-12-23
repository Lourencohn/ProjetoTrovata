<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Produtos</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Produtos</h1>
        <a href="/produto/create" class="btn btn-primary">Cadastrar Produto</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><a href="?order=codigo">Código</a></th>
                    <th><a href="?order=descricao">Descrição</a></th>
                    <th>Apelido</th>
                    <th>Grupo</th>
                    <th>Tipo Complemento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?php echo $produto['codigo']; ?></td>
                        <td><?php echo $produto['descricao']; ?></td>
                        <td><?php echo $produto['apelido']; ?></td>
                        <td><?php echo $produto['grupo_descricao']; ?></td>
                        <td><?php echo $produto['tipo_complemento']; ?></td>
                        <td>
                            <a href="/produto/edit/<?php echo $produto['id']; ?>" class="btn btn-warning">Editar</a>
                            <a href="/produto/delete/<?php echo $produto['id']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Implement pagination here -->
    </div>
</body>
</html>