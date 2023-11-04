<?php
include "../conexao.php";

if (isset($_GET["id"])) {
    $id_ingrediente = $_GET["id"];

    try {
        $stmt = $conexao->prepare("SELECT * FROM Ingrediente WHERE id_ingrediente = ?");
        $stmt->bindParam(1, $id_ingrediente);
        $stmt->execute();
        $ingrediente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ingrediente) {
            die("Ingrediente não encontrado.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar ingrediente: " . $e->getMessage());
    }
} else {
    die("ID de ingrediente não fornecido.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];

    try {
        $stmt = $conexao->prepare("UPDATE Ingrediente SET nome = ?, descricao = ? WHERE id_ingrediente = ?");
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $descricao);
        $stmt->bindParam(3, $id_ingrediente);
        $stmt->execute();
        header("Location: ../2-Read/listar-ingrediente.php");
    } catch (PDOException $e) {
        die("Erro ao editar ingrediente: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Ingrediente</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <h1>Editar Ingrediente</h1>
    <form method="POST" action="">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?= $ingrediente['nome'] ?>" required><br>
        <label for="descricao">Descrição:</label>
        <textarea name="descricao"><?= isset($ingrediente['descricao']) ? $ingrediente['descricao'] : '' ?></textarea><br>
        <input type="submit" value="Salvar">
    </form>
    <a href="../2-Read/listar-ingrediente.php">Cancelar</a>
</body>
</html>
