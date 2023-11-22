<!DOCTYPE html>
<html>
<head>
    <title>Receitas do Cozinheiro</title>
    <link rel="stylesheet" type="text/css" href="../css/apresentacao.css">
</head>
<body>
    <!-- Cabeçalho -->
    <header>
        <h2><a href="../index.php">Menu de Gerenciamento</a></h2>
        <div class="menu-container">
            <ul class="menu-list">
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

        // Verifique se o parâmetro ID foi fornecido na URL
        if (isset($_GET['id'])) {
            $id_cozinheiro = $_GET['id'];

            try {
                // Consulta para obter informações do cozinheiro
                $stmt = $conexao->prepare("SELECT * FROM Funcionario WHERE id_funcionario = ?");
                $stmt->bindParam(1, $id_cozinheiro);
                $stmt->execute();
                $cozinheiro = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($cozinheiro) {
                    echo '<div class="cozinheiro-card">';
                    echo '<img src="' . $cozinheiro['foto_funcionario'] . '" alt="Foto do Cozinheiro" class="cozinheiro-image">';
                    echo '<h2>' . $cozinheiro['nome'] . '</h2>';
                    echo '<h3>Receitas criadas por ' . $cozinheiro['nome'] . '</h3>';
                    echo '</div>';

                    // Consulta para obter as receitas criadas por este cozinheiro, incluindo ingredientes
                    $stmt = $conexao->prepare("SELECT r.id_receita, r.nome, r.foto_receita, c.descricao AS categoria, r.modo_preparo, r.qtde_porcao, GROUP_CONCAT(CONCAT(i.nome, ' (', comp.qtd_ingrediente, ' ', m.descricao, ')') ORDER BY i.nome ASC SEPARATOR ', ') AS ingredientes
                        FROM Receita r
                        LEFT JOIN Categoria c ON r.id_categoria = c.id_categoria
                        LEFT JOIN Composicao comp ON r.id_receita = comp.id_receita
                        LEFT JOIN Ingrediente i ON comp.id_ingrediente = i.id_ingrediente
                        LEFT JOIN Medida m ON comp.id_medida = m.id_medida
                        WHERE r.id_cozinheiro = ?
                        GROUP BY r.id_receita");
                    $stmt->bindParam(1, $id_cozinheiro);
                    $stmt->execute();
                    $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    echo '<div class="content">';
                    // Exibir as receitas em cartões
                    foreach ($receitas as $receita) {
                        echo '<div class="recipe-card">';
                        echo '<img src="' . $receita['foto_receita'] . '" alt="Imagem da Receita">';
                        echo '<h2>' . $receita['nome'] . '</h2>';
                        echo '<p><strong>Categoria:</strong> ' . $receita['categoria'] . '</p>';
                        echo '<p><strong>Ingredientes:</strong> ' . $receita['ingredientes'] . '</p>';
                        echo '<p><strong>Modo de Preparo:</strong> ' . $receita['modo_preparo'] . '</p>';
                        echo '<p><strong>Qtde Porção:</strong> ' . $receita['qtde_porcao'] . '</p>';   
                        echo '</div>';
                    }
                    echo '</div>';
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
            <p>Conheça nossos cozinheiros</p>
            <a href="../2-Read/apresentar-funcionario.php">Clique aqui</a>
        </div>
    </footer>
</body>
</html>
