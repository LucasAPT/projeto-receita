<?php
include "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idFuncionario = $_POST["id_funcionario"];
    $idRestaurante = $_POST["idRestaurante"];
    $dataInicio = $_POST["data_inicio"];
    $dataFim = $_POST["data_fim"];

    try {
        $stmt = $conexao->prepare("INSERT INTO Referencia (id_funcionario, idRestaurante, data_inicio, data_fim) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $idFuncionario, PDO::PARAM_INT);
        $stmt->bindParam(2, $idRestaurante, PDO::PARAM_INT);
        $stmt->bindParam(3, $dataInicio);
        $stmt->bindParam(4, $dataFim);
        $stmt->execute();
        header("Location: ../2-Read/listar-referencia.php");
    } catch (PDOException $e) {
        die("Erro ao cadastrar referência: " . $e->getMessage());
    }
}

// Recupere a lista de funcionários
try {
    $stmt = $conexao->prepare("SELECT id_funcionario, nome FROM Funcionario");
    $stmt->execute();
    $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar funcionários: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Referência de Restaurante</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Cadastrar Referência de Restaurante</h1>
    <form method="POST" action="">
        <label for="id_funcionario">Selecione o funcionário:</label>
        <select name="id_funcionario" required>
            <option value="">Selecione o Funcionário</option>
            <?php foreach ($funcionarios as $funcionario): ?>
                <option value="<?= $funcionario['id_funcionario'] ?>"><?= $funcionario['nome'] ?></option>
            <?php endforeach; ?>
        </select>
        <label for="idRestaurante">Restaurante:</label>
        <select name="idRestaurante" required>
            <?php
            try {
                $stmt = $conexao->prepare("SELECT * FROM Restaurante");
                $stmt->execute();
                $restaurantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($restaurantes as $restaurante) {
                    echo "<option value='" . $restaurante['idRestaurante'] . "'>" . $restaurante['nome'] . "</option>";
                }
            } catch (PDOException $e) {
                die("Erro ao buscar restaurantes: " . $e->getMessage());
            }
            ?>
        </select>
        <label for="data_inicio">Data de Início:</label>
        <input type="date" name="data_inicio" required>
        <label for="data_fim">Data de Término:</label>
        <input type="date" name="data_fim" required>
        <input type="submit" value="Cadastrar Referência">
    </form>
    <a href="../2-Read/listar-referencia.php">Voltar para a Lista de Referências</a>
</body>
</html>
