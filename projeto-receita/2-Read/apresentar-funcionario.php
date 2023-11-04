<!DOCTYPE html>
<html>
<head>
    <title>Apresentação de Funcionários</title>
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

        try {
            $stmt = $conexao->prepare("SELECT id_funcionario, nome, nome_fantasia, foto_funcionario FROM Funcionario");
            $stmt->execute();
            $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro ao buscar funcionários: " . $e->getMessage());
        }

        // Exibir os funcionários
        foreach ($funcionarios as $funcionario) {
            echo '<div class="employee-card">';
            echo '<img src="' . $funcionario['foto_funcionario'] . '" alt="Foto do Funcionário" class="cozinheiro-image">';
            echo '<h2>' . $funcionario['nome'] . '</h2>';
            echo '<p><strong>Nome Fantasia (Apelido):</strong> ' . $funcionario['nome_fantasia'] . '</p>';
        
            // Adicionar um link para as receitas do cozinheiro
            echo '<p><a href="../2-Read/listar-receitas-cozinheiro.php?id=' . $funcionario['id_funcionario'] . '">Conheça suas receitas</a></p>';
        
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
