<!DOCTYPE html>
<html>
<head>
    <title>Detalhes do Cozinheiro</title>
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

        // Verificar se o parâmetro ID foi fornecido na URL
        if (isset($_GET['id'])) {
            $id_cozinheiro = $_GET['id'];

            try {
                $stmt = $conexao->prepare("SELECT * FROM Funcionario WHERE id_funcionario = ?");
                $stmt->bindParam(1, $id_cozinheiro);
                $stmt->execute();
                $cozinheiro = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($cozinheiro) {
                    echo '<div class="cozinheiro-card">';
                    echo '<img src="' . $cozinheiro['foto_funcionario'] . '" alt="Foto do Cozinheiro" class="cozinheiro-image">';
                    echo '<h2>' . $cozinheiro['nome'] . '</h2>';
                    echo '<p><strong>Nome Fantasia (Apelido): </strong>' . $cozinheiro['nome_fantasia'] . '</p>';

                    // Adicione aqui outros detalhes do cozinheiro que deseja exibir

                    echo '</div>';
                } else {
                    echo "Cozinheiro não encontrado.";
                }
            } catch (PDOException $e) {
                die("Erro ao buscar cozinheiro: " . $e->getMessage());
            }
        } else {
            echo "ID do cozinheiro não fornecido na URL.";
        }
        ?>
    </div>

    <!-- Rodapé -->
    <footer>
    <div class="footer-container">
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
