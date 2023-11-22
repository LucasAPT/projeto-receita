<?php
include "../conexao.php";

// Verifique se o ID da degustação a ser editada foi passado via GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idDegustacao = $_GET['id'];
    
    // Consulta para obter os dados da degustação a ser editada
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

// Lógica para atualizar a degustação no banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $notaDegustacao = $_POST["nota_degustacao"];
    
    // Atualize a degustação no banco de dados
    try {
        $stmt = $conexao->prepare("UPDATE Degustacao SET nota_degustacao = ? WHERE id_degustacao = ?");
        $stmt->bindParam(1, $notaDegustacao, PDO::PARAM_STR);
        $stmt->bindParam(2, $idDegustacao, PDO::PARAM_INT);
        $stmt->execute();
        
        // Redirecione para a página de listagem após a atualização
        header("Location: ../2-Read/listar-degustacao.php");
    } catch (PDOException $e) {
        die("Erro ao atualizar degustação: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Degustação</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Editar Degustação</h1>
    <form method="POST" action="">
        <label for="nota_degustacao">Nota da Degustação:</label>
        <input type="number" step="0.01" name="nota_degustacao" min="0" max="10" value="<?= $degustacao['nota_degustacao'] ?>" required>
        <input type="submit" value="Atualizar Degustação">
    </form>
    <a href="../2-Read/listar-degustacao.php">Voltar para a Lista de Degustações</a>
</body>
</html>
