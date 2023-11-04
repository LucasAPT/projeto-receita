<?php
include "../conexao.php";

if (isset($_GET['idReferencia'])) {
    $idReferencia = $_GET['idReferencia'];

    try {
        $stmt = $conexao->prepare("SELECT R.*, Funcionario.nome AS nomeFuncionario, Restaurante.nome AS nomeRestaurante FROM Referencia R
                                INNER JOIN Funcionario ON R.id_funcionario = Funcionario.id_funcionario
                                INNER JOIN Restaurante ON R.idRestaurante = Restaurante.idRestaurante
                                WHERE R.id_referencia = ?");
        $stmt->bindParam(1, $idReferencia, PDO::PARAM_INT);
        $stmt->execute();
        $referencia = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$referencia) {
            die("Referência de Restaurante não encontrada.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar Referência de Restaurante: " . $e->getMessage());
    }
} else {
    die("ID de Referência de Restaurante não fornecido.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenha os dados do formulário
    $idFuncionario = $_POST["idFuncionario"];
    $novoRestaurante = $_POST["novo_restaurante"];
    $dataInicio = $_POST["data_inicio"];
    $dataFim = $_POST["data_fim"];

    try {
        $stmt = $conexao->prepare("UPDATE Referencia SET idRestaurante = ?, data_inicio = ?, data_fim = ? WHERE id_referencia = ?");
        $stmt->bindParam(1, $novoRestaurante, PDO::PARAM_INT);
        $stmt->bindParam(2, $dataInicio);
        $stmt->bindParam(3, $dataFim);
        $stmt->bindParam(4, $idReferencia, PDO::PARAM_INT);
        $stmt->execute();
        
        // Redirecione para a página de listagem após a edição
        header("Location: ../2-Read/listar-referencia.php");
    } catch (PDOException $e) {
        die("Erro ao editar Referência de Restaurante: " . $e->getMessage());
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Editar Referência de Restaurante</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Editar Referência de Restaurante</h1>
    <form method="POST" action="">
    <input type="hidden" name="idFuncionario" value="<?= $referencia['id_funcionario'] ?>">
    <label for="nomeFuncionario">Funcionário:</label>
    <input type="text" name="nomeFuncionario" value="<?= $referencia['nomeFuncionario'] ?>" readonly>
    <label for="novo_restaurante">Novo Restaurante:</label>
    <select name="novo_restaurante" required>
        <?php
        try {
            $stmt = $conexao->prepare("SELECT idRestaurante, nome FROM Restaurante");
            $stmt->execute();
            $restaurantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($restaurantes as $restaurante) {
                $selected = ($restaurante['idRestaurante'] == $referencia['idRestaurante']) ? 'selected' : '';
                echo "<option value='" . $restaurante['idRestaurante'] . "' $selected>" . $restaurante['nome'] . "</option>";
            }
        } catch (PDOException $e) {
            die("Erro ao buscar restaurantes: " . $e->getMessage());
        }
        ?>
    </select>
    <label for="data_inicio">Data de Início:</label>
    <input type="date" name="data_inicio" value="<?= $referencia['data_inicio'] ?>" required>
    <label for="data_fim">Data de Término:</label>
    <input type="date" name= "data_fim" value="<?= $referencia['data_fim'] ?>" required>
    <input type="submit" value="Salvar Alterações">
    </form>
    <a href="../2-Read/listar-referencia.php">Voltar para a Lista de Referências</a>
</body>
</html>
