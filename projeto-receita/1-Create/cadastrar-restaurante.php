<?php
include "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];
    
    try {
        $stmt = $conexao->prepare("INSERT INTO Restaurante (nome) VALUES (?)");
        $stmt->bindParam(1, $nome);
        $stmt->execute();
        header("Location: ../2-Read/listar-restaurante.php");
    } catch (PDOException $e) {
        die("Erro ao cadastrar restaurante: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Restaurante</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Cadastrar Restaurante</h1>
    <form method="POST" action="">
        <label for="nome">Nome do Restaurante:</label>
        <input type="text" name="nome" required>
        <input type="submit" value="Cadastrar">
    </form>
    <a href="../2-Read/listar-restaurante.php">Voltar para a Lista de Restaurantes</a>
</body>
</html>
