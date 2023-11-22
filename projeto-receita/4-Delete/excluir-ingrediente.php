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
    try {
        $stmt = $conexao->prepare("DELETE FROM Ingrediente WHERE id_ingrediente = ?");
        $stmt->bindParam(1, $id_ingrediente);
        $stmt->execute();
        header("Location: ../2-Read/listar-ingrediente.php");
    } catch (PDOException $e) {
        die("Erro ao excluir ingrediente: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Excluir Ingrediente</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <h1>Excluir Ingrediente</h1>
    <p>Tem certeza de que deseja excluir o ingrediente: <?= $ingrediente['nome'] ?>?</p>
    <form method="POST" action="">
        <input type="submit" value="Sim">
    </form>
    <a href="../2-Read/listar-ingrediente.php">Não</a>
</body>
</html>
