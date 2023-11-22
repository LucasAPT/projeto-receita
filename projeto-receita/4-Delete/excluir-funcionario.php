<?php
include "../conexao.php";

if (isset($_GET["id"])) {
    $id_funcionario = $_GET["id"];
    
    try {
        $stmt = $conexao->prepare("SELECT * FROM Funcionario WHERE id_funcionario = ?");
        $stmt->bindParam(1, $id_funcionario);
        $stmt->execute();
        $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$funcionario) {
            die("Funcionário não encontrado.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar funcionário: " . $e->getMessage());
    }
} else {
    die("ID de funcionário não fornecido.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $stmt = $conexao->prepare("DELETE FROM Funcionario WHERE id_funcionario = ?");
        $stmt->bindParam(1, $id_funcionario);
        $stmt->execute();
        header("Location: ../2-Read/listar-funcionario.php");
    } catch (PDOException $e) {
        die("Erro ao excluir funcionário: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Excluir Funcionário</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <h1>Excluir Funcionário</h1>
    <p>Tem certeza de que deseja excluir o funcionário: <?= $funcionario['nome'] ?>?</p>
    <form method="POST" action="">
        <input type="submit" value="Sim">
    </form>
    <a href="../2-Read/listar-funcionario.php">Não</a>
</body>
</html>
