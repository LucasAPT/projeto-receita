<?php
include "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $descricao = $_POST["descricao"];
    
    try {
        $stmt = $conexao->prepare("INSERT INTO Cargo (descricao) VALUES (?)");
        $stmt->bindParam(1, $descricao);
        $stmt->execute();
        header("Location: ../2-Read/listar-cargo.php");
    } catch (PDOException $e) {
        die("Erro ao cadastrar cargo: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Cargo</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Cadastrar Cargo</h1>
    <form method="POST" action="">
        <label for="descricao">Descrição do Cargo:</label>
        <input type="text" name="descricao" required>
        <input type="submit" value="Cadastrar">
    </form>
    <a href="../2-Read/listar-cargo.php">Voltar para a Lista de Cargos</a>
</body>
</html>
