<?php
include "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $degustador = $_POST["degustador"];
    $idCozinheiro = $_POST["idcozinheiro"];
    $nome = $_POST["nome"];
    $dataDegustacao = $_POST["data_degustacao"];
    $notaDegustacao = $_POST["nota_degustacao"];

    try {
        $stmt = $conexao->prepare("INSERT INTO Degustacao (degustador, idcozinheiro, nome, data_degustacao, nota_degustacao) VALUES (?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $degustador, PDO::PARAM_INT);
        $stmt->bindParam(2, $idCozinheiro, PDO::PARAM_INT);
        $stmt->bindParam(3, $nome);
        $stmt->bindParam(4, $dataDegustacao);
        $stmt->bindParam(5, $notaDegustacao, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: ../2-Read/listar-degustacao.php");
    } catch (PDOException $e) {
        die("Erro ao cadastrar degustação: " . $e->getMessage());
    }
}

// Recupere a lista de funcionários cozinheiros
try {
    $stmt = $conexao->prepare("SELECT id_funcionario, nome FROM Funcionario WHERE id_cargo = (SELECT id_cargo FROM Cargo WHERE descricao = 'cozinheiro') ORDER BY nome");
    $stmt->execute();
    $cozinheiros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar funcionários cozinheiros: " . $e->getMessage());
}

// Recupere a lista de funcionários degustadores
try {
    $stmt = $conexao->prepare("SELECT id_funcionario, nome FROM Funcionario WHERE id_cargo = (SELECT id_cargo FROM Cargo WHERE descricao = 'degustador')");
    $stmt->execute();
    $degustadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar funcionários degustadores: " . $e->getMessage());
}

// Recupere a lista de receitas relacionadas ao cozinheiro
if (isset($_POST['idcozinheiro'])) {
    $idCozinheiroSelecionado = $_POST['idcozinheiro'];

    try {
        $stmt = $conexao->prepare("SELECT nome_receita FROM Receita WHERE idcozinheiro = ?");
        $stmt->bindParam(1, $idCozinheiroSelecionado, PDO::PARAM_INT);
        $stmt->execute();
        $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erro ao buscar receitas do cozinheiro: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Degustação</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Cadastrar Degustação</h1>
    <form method="POST" action="">
        <label for="degustador">Degustador:</label>
        <select name="degustador" required>
            <option value="">Selecione o Degustador</option>
            <?php foreach ($degustadores as $degustador): ?>
                <option value="<?= $degustador['id_funcionario'] ?>"><?= $degustador['nome'] ?></option>
            <?php endforeach; ?>
        </select>
        <label for="idcozinheiro">Cozinheiro:</label>
        <select name="idcozinheiro" required>
            <option value="">Selecione o Cozinheiro</option>
            <?php foreach ($cozinheiros as $cozinheiro): ?>
                <option value="<?= $cozinheiro['id_funcionario'] ?>"><?= $cozinheiro['nome'] ?></option>
            <?php endforeach; ?>
        </select>
        <label for="nome">Nome da Receita:</label>
        <select name="nome" required>
            <option value="">Selecione a Receita</option>
            <?php foreach ($receitas as $receita): ?>
                <option value="<?= $receita['nome_receita'] ?>"><?= $receita['nome_receita'] ?></option>
            <?php endforeach; ?>
        </select>
        <label for="data_degustacao">Data de Degustação:</label>
        <input type="date" name="data_degustacao" required>
        <label for="nota_degustacao">Nota da Degustação:</label>
        <input type="number" step="0.01" name="nota_degustacao" min="0" max="10" required>
        <input type="submit" value="Cadastrar Degustação">
    </form>
    <a href="../2-Read/listar-degustacao.php">Voltar para a Lista de Degustações</a>

    <script>
$(document).ready(function() {
    // Quando o cozinheiro é selecionado
    $('select[name="idcozinheiro"]').change(function() {
        // Obtenha o ID do cozinheiro selecionado
        var idCozinheiroSelecionado = $(this).val();

        // Faça uma solicitação AJAX para buscar as receitas relacionadas ao cozinheiro
        $.ajax({
            url: 'buscar_receitas.php', // Substitua pelo nome do seu script PHP que busca as receitas
            method: 'POST',
            data: { id_cozinheiro: idCozinheiroSelecionado },
            success: function(response) {
                // Atualize o campo de seleção de receitas com as opções retornadas
                $('select[name="nome"]').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Erro ao buscar as receitas: " + error);
            }
        });
    });
});
</script>

</body>
</html>
