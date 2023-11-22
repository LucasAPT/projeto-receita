<?php
include "../conexao.php";

// Inicialize variáveis para evitar erros
$idLivro = $nome = "";
$receitasSelecionadas = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idLivro = $_POST["idLivro"];
    $nome = $_POST["nome"];
    $receitasSelecionadas = isset($_POST["receitasSelecionadas"]) ? $_POST["receitasSelecionadas"] : [];

    // Verifique se foram selecionadas receitas
    if (!empty($receitasSelecionadas)) {
        try {
            // Inicie uma transação para evitar inserçõqes parciais
            $conexao->beginTransaction();

            // Inserir a publicação
            $stmt = $conexao->prepare("INSERT INTO Publicacao (idLivro, nome) VALUES (?, ?)");
            $stmt->bindParam(1, $idLivro, PDO::PARAM_INT);
            $stmt->bindParam(2, $nome);
            $stmt->execute();
            
            // Obtenha o ID da última inserção
            $idPublicacao = $conexao->lastInsertId();

        // Inserir as receitas selecionadas na tabela de relações (publicacao)
        foreach ($receitasSelecionadas as $idReceita) {
            $stmt = $conexao->prepare("INSERT INTO Publicacao (idLivro, idReceita, nome) VALUES (?, ?, ?)");
            $stmt->bindParam(1, $idLivro, PDO::PARAM_INT);
            $stmt->bindParam(2, $idReceita, PDO::PARAM_INT);
            $stmt->bindParam(3, $nome);
            $stmt->execute();
        }

            // Confirme a transação
            $conexao->commit();
            
            header("Location: ../2-Read/listar-publicacao.php");
        } catch (PDOException $e) {
            // Em caso de erro, reverta a transação
            $conexao->rollBack();
            die("Erro ao vincular receitas: " . $e->getMessage());
        }
    }
}

// Recuperar os livros disponíveis
$livros = $conexao->query("SELECT idLivro, titulo FROM Livro")->fetchAll(PDO::FETCH_ASSOC);

// Recuperar as receitas disponíveis
$receitas = $conexao->query("SELECT id_receita, nome FROM Receita")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Publicação de Livro</title>
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
            justify-content: space-between;
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
    <h1>Cadastrar Publicação de Livro</h1>
    <form method="POST" action="">
        <label for="idLivro">Livro:</label>
        <select id="livrosSelect" name="idLivro" required>
            <?php
            // Recupere a lista de livros disponíveis da tabela Livro e gere as opções do menu suspenso.
            foreach ($livros as $livro) {
                $selected = ($livro['idLivro'] == $idLivro) ? 'selected' : '';
                echo "<option value=\"{$livro['idLivro']}\" $selected>{$livro['titulo']}</option>";
            }
            ?>
        </select>

        <label for="nome">Nome da Publicação:</label>
        <input type="text" name="nome" value="<?= $nome ?>" required>

        <label for="">Receita disponíveis</label>
        <div class="dual-list">
            <select name="receitasDisponiveis[]" multiple>
                <?php
                // Recupere a lista de receitas disponíveis
                foreach ($receitas as $receita) {
                    $selected = in_array($receita['id_receita'], $receitasSelecionadas) ? 'selected' : '';
                    echo "<option value=\"{$receita['id_receita']}\" $selected>{$receita['nome']}</option>";
                }
                ?>
            </select>

            <div class="control">
                <button type="button" onclick="adicionarReceitas()">Adicionar Receitas</button>
                <button type="button" onclick="removerReceitas()">Remover Receitas</button>
            </div>

            <select name="receitasSelecionadas[]" multiple>
                <!-- As receitas selecionadas serão exibidas aqui -->
            </select>
        </div>

        <input type="submit" value="Cadastrar Publicação">
    </form>
    <a href="../2-Read/listar-publicacao.php">Voltar para a Lista de Publicações</a>

    <script>
        function adicionarReceitas() {
            var select = document.querySelector("select[name='receitasDisponiveis[]']");
            var selectedOptions = select.selectedOptions;
            var selectedList = document.querySelector("select[name='receitasSelecionadas[]']");

            for (var i = 0; i < selectedOptions.length; i++) {
                selectedList.appendChild(selectedOptions[i]);
            }
        }

        function removerReceitas() {
            var selectedList = document.querySelector("select[name='receitasSelecionadas[]']");
            var selectedOptions = selectedList.selectedOptions;
            var select = document.querySelector("select[name='receitasDisponiveis[]']");

            for (var i = 0; i < selectedOptions.length; i++) {
                select.appendChild(selectedOptions[i]);
            }
        }
    </script>
</body>
</html>