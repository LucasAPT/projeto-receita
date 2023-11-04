<?php
include "../conexao.php";

if (isset($_GET["id"])) {
    $id_receita = $_GET["id"];

    try {
        $stmt = $conexao->prepare("SELECT * FROM Receita WHERE id_receita = ?");
        $stmt->bindParam(1, $id_receita);
        $stmt->execute();
        $receita = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$receita) {
            die("Receita não encontrada.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar receita: " . $e->getMessage());
    }
} else {
    die("ID de receita não fornecido.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Exclua todas as composições associadas a esta receita
        $stmtDeleteComposicoes = $conexao->prepare("DELETE FROM Composicao WHERE id_receita = ?");
        $stmtDeleteComposicoes->bindParam(1, $id_receita);
        $stmtDeleteComposicoes->execute();

        // Agora você pode excluir a receita
        $stmtExcluirReceita = $conexao->prepare("DELETE FROM Receita WHERE id_receita = ?");
        $stmtExcluirReceita->bindParam(1, $id_receita);
        $stmtExcluirReceita->execute();

        header("Location: ../2-Read/listar-receita.php");
    } catch (PDOException $e) {
        die("Erro ao excluir receita: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Excluir Receita</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <h1>Excluir Receita</h1>
    <p>Tem certeza de que deseja excluir a receita: <?= $receita['nome'] ?>?</p>
    <form method="POST" action="">
        <input type="submit" value="Sim">
    </form>
    <a href="../2-Read/listar-receita.php">Não</a>
</body>
</html>
