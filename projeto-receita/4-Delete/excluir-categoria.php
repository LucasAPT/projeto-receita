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
    try {
        $stmt = $conexao->prepare("DELETE FROM Categoria WHERE id_categoria = ?");
        $stmt->bindParam(1, $idCategoria, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: ../2-Read/listar-categoria.php");
    } catch (PDOException $e) {
        die("Erro ao excluir Categoria: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Excluir Categoria</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Excluir Categoria</h1>
    <p>Tem certeza de que deseja excluir a categoria: <?= $categoria['descricao']; ?>?</p>
    <form method="POST" action="">
        <input type="submit" value="Sim, Excluir">
    </form>
    <a href="../2-Read/listar-categoria.php">Cancelar</a>
</body>
</html>
