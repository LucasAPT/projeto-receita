<?php
include "../conexao.php";

if (isset($_GET['id'])) {
    $idCategoria = $_GET['id'];

    try {
        $stmt = $conexao->prepare("SELECT * FROM Categoria WHERE id_categoria = ?");
        $stmt->bindParam(1, $idCategoria, PDO::PARAM_INT);
        $stmt->execute();
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$categoria) {
            die("Categoria não encontrada.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar Categoria: " . $e->getMessage());
    }
} else {
    die("ID de Categoria não fornecido.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $descricao = $_POST["descricao"];

    try {
        $stmt = $conexao->prepare("UPDATE Categoria SET descricao = ? WHERE id_categoria = ?");
        $stmt->bindParam(1, $descricao);
        $stmt->bindParam(2, $idCategoria, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: ../2-Read/listar-categoria.php");
    } catch (PDOException $e) {
        die("Erro ao editar Categoria: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Categoria</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Editar Categoria</h1>
    <form method="POST" action="">
        <label for="descricao">Descrição da Categoria:</label>
        <input type="text" name="descricao" value="<?= $categoria['descricao'] ?>" required>
        <input type="submit" value="Salvar Alterações">
    </form>
    <a href="../2-Read/listar-categoria.php">Voltar para a Lista de Categorias</a>
</body>
</html>
