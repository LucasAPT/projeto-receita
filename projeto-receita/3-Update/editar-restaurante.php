<?php
include "../conexao.php";

if (isset($_GET['id'])) {
    $idRestaurante = $_GET['id'];

    try {
        $stmt = $conexao->prepare("SELECT * FROM Restaurante WHERE idRestaurante = ?");
        $stmt->bindParam(1, $idRestaurante, PDO::PARAM_INT);
        $stmt->execute();
        $restaurante = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$restaurante) {
            die("Restaurante não encontrado.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar Restaurante: " . $e->getMessage());
    }
} else {
    die("ID de Restaurante não fornecido.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];

    try {
        $stmt = $conexao->prepare("UPDATE Restaurante SET nome = ? WHERE idRestaurante = ?");
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $idRestaurante, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: ../2-Read/listar-restaurante.php");
    } catch (PDOException $e) {
        die("Erro ao editar Restaurante: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Restaurante</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Editar Restaurante</h1>
    <form method="POST" action="">
        <label for="nome">Nome do Restaurante:</label>
        <input type="text" name="nome" value="<?= $restaurante['nome'] ?>" required>
        <input type="submit" value="Salvar Alterações">
    </form>
    <a href="../2-Read/listar-restaurante.php">Voltar para a Lista de Restaurantes</a>
</body>
</html>