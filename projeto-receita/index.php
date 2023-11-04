<!DOCTYPE html>
<html>
<head>
    <title>Menu Principal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .menu-container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .menu-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .menu-list {
            list-style-type: none;
            padding: 0;
        }

        .menu-item {
            margin: 10px 0;
        }

        .menu-link {
            display: block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .menu-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="menu-container">
        <h1 class="menu-title">Menu de Apresentação</h1>
        <ul class="menu-list">
            <li class="menu-item"><a class="menu-link" href="2-read/apresentar-livro.php">Apresentação de Livros</a></li>
            <li class="menu-item"><a class="menu-link" href="2-read/apresentar-receita.php">Apresentação de Receitas</a></li>
            <li class="menu-item"><a class="menu-link" href="2-read/apresentar-funcionario.php">Apresentação de Funcionários</a></li>
        </ul>
        <h1 class="menu-title">Menu de Publicação</h1>
        <ul class="menu-list">
            <li class="menu-item"><a class="menu-link" href="1-Create/cadastrar-publicacao.php">Cadastrar Publicação</a></li>
            <li class="menu-item"><a class="menu-link" href="2-Read/listar-publicacao.php">Listar Publicação</a></li>
        </ul>
        <h1 class="menu-title">Menu de Livros</h1>
        <ul class="menu-list">
            <li class="menu-item"><a class="menu-link" href="1-Create/cadastrar-livro.php">Cadastrar Livros</a></li>
            <li class="menu-item"><a class="menu-link" href="2-Read/listar-livro.php">Listar Livros</a></li>
        </ul>
        <h1 class="menu-title">Menu de Receitas</h1>
        <ul class="menu-list">
            <li class="menu-item"><a class="menu-link" href="1-Create/cadastrar-receita.php">Cadastrar Receita</a></li>
            <li class="menu-item"><a class="menu-link" href="2-Read/listar-receita.php">Listar Receitas</a></li>
        </ul>
        <h2 class="menu-title">Menu de Ingredientes</h2>
        <ul class="menu-list">
            <li class="menu-item"><a class="menu-link" href="1-Create/cadastrar-ingrediente.php">Cadastrar Ingrediente</a></li>
            <li class="menu-item"><a class="menu-link" href="2-Read/listar-ingrediente.php">Listar Ingredientes</a></li>
        </ul>
        <h2 class="menu-title">Menu de Categorias</h2>
        <ul class="menu-list">
            <li class="menu-item"><a class="menu-link" href="1-Create/cadastrar-categoria.php">Cadastrar Categorias</a></li>
            <li class="menu-item"><a class="menu-link" href="2-Read/listar-categoria.php">Listar Categorias</a></li>
        </ul>
        <h2 class="menu-title">Menu de Funcionários</h2>
        <ul class="menu-list">
            <li class="menu-item"><a class="menu-link" href="1-Create/cadastrar-funcionario.php">Cadastrar Funcionário</a></li>
            <li class="menu-item"><a class="menu-link" href="2-Read/listar-funcionario.php">Listar Funcionários</a></li>
        </ul>
        <h2 class="menu-title">Menu de Cargos</h2>
        <ul class="menu-list">
            <li class="menu-item"><a class="menu-link" href="1-Create/cadastrar-cargo.php">Cadastrar Cargos</a></li>
            <li class="menu-item"><a class="menu-link" href="2-Read/listar-cargo.php">Listar Cargos</a></li>
        </ul>
        <h2 class="menu-title">Menu de Restaurantes</h2>
        <ul class="menu-list">
            <li class="menu-item"><a class="menu-link" href="1-Create/cadastrar-restaurante.php">Cadastrar Restaurantes</a></li>
            <li class="menu-item"><a class="menu-link" href="2-Read/listar-restaurante.php">Listar Restaurantes</a></li>
        </ul>
        <h2 class="menu-title">Menu de Referências</h2>
        <ul class="menu-list">
            <li class="menu-item"><a class="menu-link" href="1-Create/cadastrar-referencia.php">Cadastrar Referências</a></li>
            <li class="menu-item"><a class="menu-link" href="2-Read/listar-referencia.php">Listar Referências</a></li>
        </ul>
        <h2 class="menu-title">Menu de Degustação</h2>
        <ul class="menu-list">
            <li class="menu-item"><a class="menu-link" href="1-Create/cadastrar-degustacao.php">Cadastrar Degustação</a></li>
            <li class="menu-item"><a class="menu-link" href="2-Read/listar-degustacao.php">Listar Degustação</a></li>
        </ul>
        <h2 class="menu-title">Menu de Medidas</h2>
        <ul class="menu-list">
            <li class="menu-item"><a class="menu-link" href="1-Create/cadastrar-medida.php">Cadastrar Medidas</a></li>
            <li class="menu-item"><a class="menu-link" href="2-Read/listar-medida.php">Listar Medidas</a></li>
        </ul>
    </div>
</body>
</html>
