<!DOCTYPE html>
<html>
<head>
    <title>Apresentação de Livros</title>
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
        include "../conexao.php";

        try {
            $stmt = $conexao->prepare("SELECT idLivro, titulo, capa_livro FROM Livro");
            $stmt->execute();
            $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro ao buscar livros: " . $e->getMessage());
        }

        // Exibir os livros
        foreach ($livros as $livro) {
            echo '<div class="recipe-card">'; // Use a mesma classe "recipe-card"
            echo '<img src="' . $livro['capa_livro'] . '" alt="Capa do Livro" class="recipe-image">'; // Use a mesma classe "recipe-image"
            echo '<h2>' . $livro['titulo'] . '</h2>';
            echo '<p><a href="../2-Read/apresentar-publicacao.php?id=' . $livro['idLivro'] . '">Ver receitas deste livro</a></p>';
            echo '</div>';
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
