<?php
include "../conexao.php";

if (isset($_GET['id'])) {
    $idLivro = $_GET['id'];

    try {
        $stmt = $conexao->prepare("SELECT * FROM Livro WHERE idLivro = ?");
        $stmt->bindParam(1, $idLivro, PDO::PARAM_INT);
        $stmt->execute();
        $livro = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$livro) {
            die("Livro não encontrado.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar Livro: " . $e->getMessage());
    }
} else {
    die("ID de Livro não fornecido.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $stmt = $conexao->prepare("DELETE FROM Livro WHERE idLivro = ?");
        $stmt->bindParam(1, $idLivro, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: ../2-Read/listar-livro.php");
    } catch (PDOException $e) {
        die("Erro ao excluir Livro: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Excluir Livro</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Excluir Livro</h1>
    <p>Tem certeza de que deseja excluir o livro: <?= $livro['titulo']; ?>?</p>
    <form method="POST" action="">
        <input type="submit" value="Sim, Excluir">
    </form>
    <a href="../2-Read/listar-livro.php">Cancelar</a>
</body>
</html>
