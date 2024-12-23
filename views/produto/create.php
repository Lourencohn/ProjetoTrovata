<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Cadastrar Produto</h1>
        <form action="/produto/create" method="post">
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <input type="text" class="form-control" id="descricao" name="descricao" required>
            </div>
            <div class="form-group">
                <label for="apelido">Apelido</label>
                <input type="text" class="form-control" id="apelido" name="apelido">
            </div>
            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" class="form-control" id="codigo" name="codigo" required>
            </div>
            <div class="form-group">
                <label for="grupo_produto_id">Grupo de Produto</label>
                <select class="form-control" id="grupo_produto_id" name="grupo_produto_id" required>
                    <!-- Populate with grupos_produtos -->
                </select>
            </div>
            <div class="form-group">
                <label for="tipo_complemento">Tipo de Complemento</label>
                <input type="text" class="form-control" id="tipo_complemento" name="tipo_complemento">
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="/produto/index" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</body>
</html>