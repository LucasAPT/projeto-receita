<?php
include "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];

    try {
        $stmt = $conexao->prepare("INSERT INTO Ingrediente (nome, descricao) VALUES (?, ?)");
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $descricao);
        $stmt->execute();
        header("Location: ../2-Read/listar-ingrediente.php");
    } catch (PDOException $e) {
        die("Erro ao cadastrar ingrediente: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Ingrediente</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <h1>Cadastro de Ingrediente</h1>
    <form method="POST" action="">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required><br>
        <label for="descricao">Descrição:</label>
        <textarea name="descricao"></textarea><br>
        <input type="submit" value="Cadastrar">
    </form>
    <a href="../2-Read/listar-ingrediente.php">Voltar</a>
</body>
</html>
