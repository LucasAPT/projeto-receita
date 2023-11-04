<?php
include "../conexao.php";

// Verifique se o ID da degustação a ser excluída foi passado via GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idDegustacao = $_GET['id'];
    
    // Consulta para obter os dados da degustação a ser excluída
    try {
        $stmt = $conexao->prepare("SELECT * FROM Degustacao WHERE id_degustacao = ?");
        $stmt->bindParam(1, $idDegustacao, PDO::PARAM_INT);
        $stmt->execute();
        $degustacao = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verifique se a degustação existe
        if (!$degustacao) {
            die("Degustação não encontrada.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar degustação: " . $e->getMessage());
    }
} else {
    die("ID da degustação não fornecido.");
}

// Lógica para excluir a degustação do banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmar'])) {
    try {
        $stmt = $conexao->prepare("DELETE FROM Degustacao WHERE id_degustacao = ?");
        $stmt->bindParam(1, $idDegustacao, PDO::PARAM_INT);
        $stmt->execute();
        
        // Redirecione para a página de listagem após a exclusão
        header("Location: ../2-Read/listar-degustacao.php");
    } catch (PDOException $e) {
        die("Erro ao excluir degustação: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Excluir Degustação</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Excluir Degustação</h1>
    <p>Tem certeza de que deseja excluir a degustação: <?= $degustacao['nome'] ?>?</p>
    <form method="POST" action="">
        <input type="submit" name="confirmar" value="Confirmar Exclusão">
    </form>
    <a href="../2-Read/listar-degustacao.php">Cancelar e Voltar para a Lista de Degustações</a>
</body>
</html>
