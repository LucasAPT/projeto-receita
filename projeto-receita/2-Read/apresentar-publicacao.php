<!DOCTYPE html>
<html>
<head>
    <title>Apresentação de Publicação</title>
    <link rel="stylesheet" type="text/css" href="../css/apresentacao.css">
</head>
<body>
    <!-- Cabeçalho -->
    <header>
        <h2><a href="../index.php">Menu de Gerenciamento</a></h2>
        <div class="menu-container">
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="../2-Read/listar-publicacao.php">Publicações</a>
                </li>
                <li class="menu-item">
                    <a href="../2-Read/listar-livro.php">Livros</a>
                </li>
                <li class="menu-item">
                    <a href="../2-Read/listar-receita.php">Receitas</a>
                </li>
                <li class="menu-item">
                    <a href="../2-Read/listar-funcionario.php">Funcionários</a>
                </li>
                <li class="menu-item">
                    <a href="../2-Read/listar-ingrediente.php">Ingredientes</a>
                </li>
            </ul>
        </div>
    </header>

    <!-- Conteúdo -->
    <div class="content">
        <?php
        include "../conexao.php"; // Certifique-se de incluir seu arquivo de conexão

        // Verifica se o ID da publicação foi fornecido via parâmetro na URL
        if (isset($_GET['id'])) {
            $idLivro = $_GET['id'];

            try {
                // Consulta para obter as informações do livro e da publicação
                $stmt = $conexao->prepare("SELECT P.nome AS nome_publicacao, L.titulo AS nome_livro
                    FROM Publicacao P
                    INNER JOIN Livro L ON P.idLivro = L.idLivro
                    WHERE P.idLivro = :idLivro");
                $stmt->bindParam(":idLivro", $idLivro, PDO::PARAM_INT);
                $stmt->execute();
                $dadosPublicacao = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Erro ao buscar informações da publicação: " . $e->getMessage());
            }

            if (!empty($dadosPublicacao)) {
                echo '<h2>Receitas do Livro: ' . $dadosPublicacao['nome_livro'] . '</h2>';
                echo '<h2>Nome da Publicação: ' . $dadosPublicacao['nome_publicacao'] . '</h2>';
                
    echo '</div>';
    echo '<div class="content">';
                // Consulta para obter as receitas da publicação
                $stmt = $conexao->prepare("SELECT R.id_receita, R.nome AS nome_receita, R.foto_receita, R.id_categoria, C.descricao AS categoria, R.modo_preparo, R.qtde_porcao, GROUP_CONCAT(I.nome ORDER BY I.nome ASC SEPARATOR ', ') AS ingredientes, R.id_cozinheiro, F.nome AS cozinheiro, F.nome_fantasia
                    FROM Publicacao P
                    INNER JOIN Receita R ON P.idReceita = R.id_receita
                    LEFT JOIN Categoria C ON R.id_categoria = C.id_categoria
                    LEFT JOIN Composicao COMP ON R.id_receita = COMP.id_receita
                    LEFT JOIN Ingrediente I ON COMP.id_ingrediente = I.id_ingrediente
                    LEFT JOIN Funcionario F ON R.id_cozinheiro = F.id_funcionario
                    WHERE P.idLivro = :idLivro
                    GROUP BY R.id_receita");
                $stmt->bindParam(":idLivro", $idLivro, PDO::PARAM_INT);
                $stmt->execute();
                $receitasDaPublicacao = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($receitasDaPublicacao) > 0) {
                    foreach ($receitasDaPublicacao as $receita) {
                        echo '<div class="recipe-card">';
                        echo '<img src="' . $receita['foto_receita'] . '" alt="Imagem da Receita">';
                        echo '<h2>' . $receita['nome_receita'] . '</h2>';
                        echo '<p><strong>Categoria:</strong> <a href="../2-Read/apresentar-categoria.php?categoria_id=' . $receita['id_categoria'] . '">' . $receita['categoria'] . '</a></p>';
                        echo '<p><strong>Ingredientes:</strong> ' . $receita['ingredientes'] . '</p>';
                        echo '<p><strong>Modo de Preparo:</strong> ' . $receita['modo_preparo'] . '</p>';
                        echo '<p><strong>Qtde Porção:</strong> ' . $receita['qtde_porcao'] . '</p>';
                        echo '<p><strong>Cozinheiro:</strong> <a href="../2-Read/apresentar-cozinheiro.php?id=' . $receita['id_cozinheiro'] . '">' . $receita['cozinheiro'] . '</a></p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Nenhuma receita encontrada para esta publicação.</p>';
                }
            } else {
                echo '<p>Nenhuma informação encontrada para este livro.</p>';
            }
        } else {
            echo '<p>ID do livro não fornecido.</p>';
        }
        ?>
    </div>

    <!-- Rodapé -->
    <footer>
    <div class="footer-container">
    <h2>Menu de Navegação</h2>
        <a href="../2-Read/apresentar-livro.php">Livros</a>
        <a href="../2-Read/apresentar-receita.php">Receitas</a>
        <a href="../2-Read/apresentar-funcionario.php">Funcionários</a>
    </div>
</footer>
</body>
</html>
