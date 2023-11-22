<?php
include "../conexao.php";

// Verifique se o ID da publicação foi fornecido via parâmetro na URL
if (isset($_GET['id'])) {
    $idLivro = $_GET['id'];

    // Inicialize variáveis para evitar erros
    $nome = $livroSelecionado = "";
    $receitasSelecionadas = [];
    $receitasDisponiveis = [];

    // Verifique se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nome = $_POST["nome"];
        $livroSelecionado = $_POST["livroSelecionado"];
        $receitasSelecionadas = isset($_POST["receitasSelecionadas"]) ? $_POST["receitasSelecionadas"] : [];

        try {
            // Atualize a publicação com base no ID fornecido
            $stmt = $conexao->prepare("UPDATE Publicacao SET idLivro = ?, nome = ? WHERE idLivro = ?");
            $stmt->execute([$livroSelecionado, $nome, $idLivro]);

            // Obtenha as relações de receitas existentes para a publicação
            $stmt = $conexao->prepare("SELECT idReceita FROM Publicacao WHERE idLivro = ?");
            $stmt->execute([$idLivro]);
            $relacoesExistentes = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

            // Remova as relações de receitas que não estão nas seleções
            $relacoesParaRemover = array_diff($relacoesExistentes, $receitasSelecionadas);
            if (!empty($relacoesParaRemover)) {
                // Crie uma lista de placeholders para os valores dos placeholders
                $placeholders = implode(',', array_fill(0, count($relacoesParaRemover), '?'));

                // Construa a consulta SQL com os placeholders para os IDs das receitas a serem removidas
                $sql = "DELETE FROM Publicacao WHERE idLivro = ? AND idReceita IN ($placeholders)";
                $stmt = $conexao->prepare($sql);
                $stmt->execute([$idLivro, ...$relacoesParaRemover]);
            }

            // Adicione novas relações de receitas para as seleções
            $relacoesParaAdicionar = array_diff($receitasSelecionadas, $relacoesExistentes);
            foreach ($relacoesParaAdicionar as $idReceita) {
                $stmt = $conexao->prepare("INSERT INTO Publicacao (idLivro, idReceita, nome) VALUES (?, ?, ?)");
                $stmt->execute([$livroSelecionado, $idReceita, $nome]);
            }

            // Redirecione para a página de listagem após a edição
            header("Location: ../2-Read/listar-publicacao.php");
        } catch (PDOException $e) {
            die("Erro ao atualizar a publicação: " . $e->getMessage());
        }
        
    } else {
        // Recupere as informações da publicação com base no ID fornecido
        try {
            $stmt = $conexao->prepare("SELECT p.idLivro, p.nome, p.idReceita, l.titulo AS livro, r.id_receita, r.nome AS receita 
                                       FROM Publicacao p 
                                       INNER JOIN Livro l ON p.idLivro = l.idLivro 
                                       LEFT JOIN Receita r ON p.idReceita = r.id_receita 
                                       WHERE p.idLivro = ?");
            $stmt->execute([$idLivro]);
            $publicacao = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($publicacao) {
                $nome = $publicacao[0]['nome'];
                $livroSelecionado = $publicacao[0]['idLivro'];
                $receitasSelecionadas = array_column($publicacao, 'idReceita');
            } else {
                // Redirecione para a página de listagem se a publicação não for encontrada
                header("Location: ../2-Read/listar-publicacao.php");
            }
        } catch (PDOException $e) {
            die("Erro ao buscar a publicação: " . $e->getMessage());
        }
    }

    // Recupere as receitas disponíveis
    try {
        $stmt = $conexao->query("SELECT id_receita, nome FROM Receita");
        $receitasDisponiveis = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erro ao buscar as receitas disponíveis: " . $e->getMessage());
    }

    // Recupere o livro correspondente à publicação para exibir na lista suspensa
    try {
        $stmt = $conexao->prepare("SELECT idLivro, titulo FROM Livro WHERE idLivro = ?");
        $stmt->execute([$livroSelecionado]);
        $livroSelecionado = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erro ao buscar o livro correspondente à publicação: " . $e->getMessage());
    }
} else {
    // Redirecione para a página de listagem se o ID da publicação não for fornecido
    header("Location: ../2-Read/listar-publicacao.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Publicação de Livro</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            background-color: #337ab7;
            color: #fff;
            padding: 10px;
        }
        form {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label, select, input {
            display: block;
            margin-bottom: 10px;
        }
        select#livrosSelect {
            height: 50px;
        }
        select {
            height: 200px;
        }
        input[type="submit"] {
            background-color: #337ab7;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        .dual-list {
            display: flex;
            justify-content: space between;
        }
        .dual-list select {
            width: 48%;
        }
        .dual-list .control {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>
<body>
<a href="../index.php">Menu Principal</a>
    <h1>Editar Publicação de Livro</h1>
    <form method="POST" action="">
        <label for="idLivro">Livro:</label>
        <select id="livrosSelect" name="livroSelecionado" required>
            <?php
            // Exiba o livro correspondente à publicação como a opção selecionada na lista suspensa
            echo "<option value=\"{$livroSelecionado['idLivro']}\" selected>{$livroSelecionado['titulo']}</option>";
            ?>
        </select>

        <label for="nome">Nome da Publicação:</label>
        <input type="text" name="nome" value="<?= $nome ?>" required>

        <div class="dual-list">
    <select name="receitasDisponiveis[]" id="receitasDisponiveis" multiple>
        <?php
        // Exiba as receitas disponíveis (que não estão selecionadas) à direita
        foreach ($receitasDisponiveis as $receita) {
            echo "<option value=\"{$receita['id_receita']}\">{$receita['nome']}</option>";
        }
        ?>
    </select>

    <div class="control">
        <button type="button" onclick="adicionarReceitas()">Adicionar Receitas</button>
        <button type="button" onclick="removerReceitas()">Remover Receitas</button>
    </div>

    <select name="receitasSelecionadas[]" id="receitasSelecionadas" multiple>
        <?php
        // Exiba as receitas selecionadas (cadastradas na publicação) à esquerda
        foreach ($receitasSelecionadas as $idReceita) {
            $stmt = $conexao->prepare("SELECT id_receita, nome FROM Receita WHERE id_receita = ?");
            $stmt->execute([$idReceita]);
            $receita = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<option value=\"{$receita['id_receita']}\" selected>{$receita['nome']}</option>";
        }
        ?>
    
   
</select>
</div>
    
    <input type="submit" value="Atualizar Publicação">

</form>

<a href="../2-Read/listar-publicacao.php">Voltar para a Lista de Publicações</a>

<script>
    function adicionarReceitas() {
    var selectDisponiveis = document.querySelector("#receitasDisponiveis");
    var selectSelecionadas = document.querySelector("#receitasSelecionadas");
    var selectedOptions = selectDisponiveis.selectedOptions;

    for (var i = 0; i < selectedOptions.length; i++) {
        selectSelecionadas.appendChild(selectedOptions[i]);
    }
}

function removerReceitas() {
    var selectSelecionadas = document.querySelector("#receitasSelecionadas");
    var selectedOptions = selectSelecionadas.selectedOptions;
    var selectDisponiveis = document.querySelector("#receitasDisponiveis");

    for (var i = 0; i < selectedOptions.length; i++) {
        selectDisponiveis.appendChild(selectedOptions[i]);
    }
}
</script>
</body>
</html>
