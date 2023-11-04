<!DOCTYPE html>
<html>
<head>
    <title>Receitas por Categoria</title>
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

        // Verifique se o parâmetro categoria_id foi fornecido na URL
        if (isset($_GET['categoria_id'])) {
            $categoria_id = $_GET['categoria_id'];

            // Consulta para obter as receitas da categoria selecionada
            try {
                $stmt = $conexao->prepare("SELECT r.id_receita, r.nome, r.foto_receita, c.descricao AS categoria, r.modo_preparo, r.qtde_porcao, GROUP_CONCAT(i.nome ORDER BY i.nome ASC SEPARATOR ', ') AS ingredientes, f.id_funcionario AS id_cozinheiro, f.nome AS cozinheiro
                    FROM Receita r
                    LEFT JOIN Categoria c ON r.id_categoria = c.id_categoria
                    LEFT JOIN Composicao comp ON r.id_receita = comp.id_receita
                    LEFT JOIN Ingrediente i ON comp.id_ingrediente = i.id_ingrediente
                    LEFT JOIN Funcionario f ON r.id_cozinheiro = f.id_funcionario
                    WHERE c.id_categoria = ?
                    GROUP BY r.id_receita");
                $stmt->bindParam(1, $categoria_id);
                $stmt->execute();
                $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Erro ao buscar receitas: " . $e->getMessage());
            }

            // Exibir as receitas em cartões
            foreach ($receitas as $receita) {
                echo '<div class="recipe-card">';
                echo '<img src="' . $receita['foto_receita'] . '" alt="Imagem da Receita">';
                echo '<h2>' . $receita['nome'] . '</h2>';
                echo '<p><strong>Categoria:</strong> ' . $receita['categoria'] . '</p>';
                echo '<p><strong>Ingredientes:</strong> ' . $receita['ingredientes'] . '</p>';
                echo '<p><strong>Modo de Preparo:</strong> ' . $receita['modo_preparo'] . '</p>';
                echo '<p><strong>Qtde Porção:</strong> ' . $receita['qtde_porcao'] . '</p>';
                echo '<p><strong>Cozinheiro:</strong> <a href="../2-Read/apresentar-cozinheiro.php?id=' . $receita['id_cozinheiro'] . '">' . $receita['cozinheiro'] . '</a></p>';
                echo '</div>';
            }
        } else {
            echo "ID da categoria não fornecido na URL.";
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
