<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Principal</title>
    <!-- Adicione o link para o Bootstrap CSS aqui -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .menu-container {
            margin-top: 30px;
        }

        .menu-title {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="menu-container">

            <!-- Apresentação -->
            <div class="card">
                <div class="card-header" id="headingApresentacao">
                    <h2 class="mb-0">
                        <button class="btn btn-link menu-title" type="button" data-toggle="collapse" data-target="#collapseApresentacao"
                            aria-expanded="true" aria-controls="collapseApresentacao">
                            Apresentação
                        </button>
                    </h2>
                </div>

                <div id="collapseApresentacao" class="collapse show" aria-labelledby="headingApresentacao"
                    data-parent=".menu-container">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="2-read/apresentar-livro.php">Livros</a></li>
                            <li class="list-group-item"><a href="2-read/apresentar-receita.php">Receitas</a></li>
                            <li class="list-group-item"><a href="2-read/apresentar-funcionario.php">Funcionários</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Publicação -->
            <div class="card">
                <div class="card-header" id="headingPublicacao">
                    <h2 class="mb-0">
                        <button class="btn btn-link menu-title collapsed" type="button" data-toggle="collapse" data-target="#collapsePublicacao"
                            aria-expanded="false" aria-controls="collapsePublicacao">
                            Publicação
                        </button>
                    </h2>
                </div>
                <div id="collapsePublicacao" class="collapse" aria-labelledby="headingPublicacao"
                    data-parent=".menu-container">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="1-Create/cadastrar-publicacao.php">Cadastrar</a></li>
                            <li class="list-group-item"><a href="2-Read/listar-publicacao.php">Listar</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Livros -->
            <div class="card">
                <div class="card-header" id="headingLivros">
                    <h2 class="mb-0">
                        <button class="btn btn-link menu-title collapsed" type="button" data-toggle="collapse" data-target="#collapseLivros"
                            aria-expanded="false" aria-controls="collapseLivros">
                            Livros
                        </button>
                    </h2>
                </div>
                <div id="collapseLivros" class="collapse" aria-labelledby="headingLivros"
                    data-parent=".menu-container">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="1-Create/cadastrar-livro.php">Cadastrar</a></li>
                            <li class="list-group-item"><a href="2-Read/listar-livro.php">Listar</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Receitas -->
            <div class="card">
                <div class="card-header" id="headingReceitas">
                    <h2 class="mb-0">
                        <button class="btn btn-link menu-title collapsed" type="button" data-toggle="collapse" data-target="#collapseReceitas"
                            aria-expanded="false" aria-controls="collapserReceitas">
                            Receitas
                        </button>
                    </h2>
                </div>
                <div id="collapseReceitas" class="collapse" aria-labelledby="headingReceitas"
                    data-parent=".menu-container">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="1-Create/cadastrar-receita.php">Cadastrar</a></li>
                            <li class="list-group-item"><a href="2-Read/listar-receita.php">Listar</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Ingredientes -->
            <div class="card">
                <div class="card-header" id="headingIngredientes">
                    <h2 class="mb-0">
                        <button class="btn btn-link menu-title collapsed" type="button" data-toggle="collapse" data-target="#collapseIngredientes"
                            aria-expanded="false" aria-controls="collapseIngredientes">
                            Ingredientes
                        </button>
                    </h2>
                </div>
                <div id="collapseIngredientes" class="collapse" aria-labelledby="headingIngredientes"
                    data-parent=".menu-container">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="1-Create/cadastrar-ingrediente.php">Cadastrar</a></li>
                            <li class="list-group-item"><a href="2-Read/listar-ingrediente.php">Listar</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Categorias -->
            <div class="card">
                <div class="card-header" id="headingCategorias">
                    <h2 class="mb-0">
                        <button class="btn btn-link menu-title collapsed" type="button" data-toggle="collapse" data-target="#collapseCategorias"
                            aria-expanded="false" aria-controls="collapseCategorias">
                            Categorias
                        </button>
                    </h2>
                </div>
                <div id="collapseCategorias" class="collapse" aria-labelledby="headingCategorias"
                    data-parent=".menu-container">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="1-Create/cadastrar-categoria.php">Cadastrar</a></li>
                            <li class="list-group-item"><a href="2-Read/listar-categoria.php">Listar</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Funcionários -->
            <div class="card">
                <div class="card-header" id="headingFuncionarios">
                    <h2 class="mb-0">
                        <button class="btn btn-link menu-title collapsed" type="button" data-toggle="collapse" data-target="#collapseFuncionarios"
                            aria-expanded="false" aria-controls="collapseFuncionarios">
                            Funcionários
                        </button>
                    </h2>
                </div>
                <div id="collapseFuncionarios" class="collapse" aria-labelledby="headingFuncionarios"
                    data-parent=".menu-container">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="1-Create/cadastrar-funcionario.php">Cadastrar</a></li>
                            <li class="list-group-item"><a href="2-Read/listar-funcionario.php">Listar</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Cargos -->
            <div class="card">
                <div class="card-header" id="headingCargos">
                    <h2 class="mb-0">
                        <button class="btn btn-link menu-title collapsed" type="button" data-toggle="collapse" data-target="#collapseCargos"
                            aria-expanded="false" aria-controls="collapseCargos">
                            Cargos
                        </button>
                    </h2>
                </div>
                <div id="collapseCargos" class="collapse" aria-labelledby="headingCargos"
                    data-parent=".menu-container">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="1-Create/cadastrar-cargo.php">Cadastrar</a></li>
                            <li class="list-group-item"><a href="2-Read/listar-cargo.php">Listar</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Restaurantes -->
            <div class="card">
                <div class="card-header" id="headingRestaurantes">
                    <h2 class="mb-0">
                        <button class="btn btn-link menu-title collapsed" type="button" data-toggle="collapse" data-target="#collapseRestaurantes"
                            aria-expanded="false" aria-controls="collapseRestaurantes">
                            Restaurantes
                        </button>
                    </h2>
                </div>
                <div id="collapseRestaurantes" class="collapse" aria-labelledby="headingRestaurantes"
                    data-parent=".menu-container">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="1-Create/cadastrar-restaurante.php">Cadastrar</a></li>
                            <li class="list-group-item"><a href="2-Read/listar-restaurante.php">Listar</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Referências -->
            <div class="card">
                <div class="card-header" id="headingReferencias">
                    <h2 class="mb-0">
                        <button class="btn btn-link menu-title collapsed" type="button" data-toggle="collapse" data-target="#collapseReferencias"
                            aria-expanded="false" aria-controls="collapseReferencias">
                            Referências
                        </button>
                    </h2>
                </div>
                <div id="collapseReferencias" class="collapse" aria-labelledby="headingReferencias"
                    data-parent=".menu-container">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="1-Create/cadastrar-referencia.php">Cadastrar</a></li>
                            <li class="list-group-item"><a href="2-Read/listar-referencia.php">Listar</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Degustação -->
            <div class="card">
                <div class="card-header" id="headingDegustacao">
                    <h2 class="mb-0">
                        <button class="btn btn-link menu-title collapsed" type="button" data-toggle="collapse" data-target="#collapseDegustacao"
                            aria-expanded="false" aria-controls="collapseDegustacao">
                            Degustação
                        </button>
                    </h2>
                </div>
                <div id="collapseDegustacao" class="collapse" aria-labelledby="headingDegustacao"
                    data-parent=".menu-container">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="1-Create/cadastrar-degustacao.php">Cadastrar</a></li>
                            <li class="list-group-item"><a href="2-Read/listar-degustacao.php">Listar</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Medidas -->
            <div class="card">
                <div class="card-header" id="headingMedidas">
                    <h2 class="mb-0">
                        <button class="btn btn-link menu-title collapsed" type="button" data-toggle="collapse" data-target="#collapseMedidas"
                            aria-expanded="false" aria-controls="collapseMedidas">
                            Medidas
                        </button>
                    </h2>
                </div>
                <div id="collapseMedidas" class="collapse" aria-labelledby="headingMedidas"
                    data-parent=".menu-container">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><a href="1-Create/cadastrar-medida.php">Cadastrar</a></li>
                            <li class="list-group-item"><a href="2-Read/listar-medida.php">Listar</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Repetir o padrão acima para outros títulos -->

        </div>
    </div>

    <!-- Adicione o link para o Bootstrap JS e o jQuery no final do corpo do HTML para um melhor desempenho -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
