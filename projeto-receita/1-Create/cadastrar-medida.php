<?php
include "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = $_POST["descricao"];

    try {
        $stmt = $conexao->prepare("INSERT INTO Medida (descricao) VALUES (?)");
        $stmt->bindParam(1, $descricao);
        $stmt->execute();

        // Redirecione para a página de listagem de unidades de medida
        header("Location: ../2-Read/listar-medida.php");
    } catch (PDOException $e) {
        die("Erro ao cadastrar unidade de medida: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Unidade de Medida</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <h1>Cadastro de Unidade de Medida</h1>
    <form method="POST" action="">
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" required><br>
        <input type="submit" value="Cadastrar Unidade de Medida">
    </form>
    <a href="../2-Read/listar-medida.php">Voltar</a>
</body>
</html>
