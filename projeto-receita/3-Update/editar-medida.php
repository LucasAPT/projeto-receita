<?php
include "../conexao.php";

if (isset($_GET['id'])) {
    $id_medida = $_GET['id'];

    try {
        $stmt = $conexao->prepare("SELECT * FROM Medida WHERE id_medida = ?");
        $stmt->bindParam(1, $id_medida);
        $stmt->execute();
        $medida = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$medida) {
            die("Unidade de Medida não encontrada.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar Unidade de Medida: " . $e->getMessage());
    }
} else {
    die("ID de Unidade de Medida não fornecido.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = $_POST["descricao"];

    try {
        $stmt = $conexao->prepare("UPDATE Medida SET descricao = ? WHERE id_medida = ?");
        $stmt->bindParam(1, $descricao);
        $stmt->bindParam(2, $id_medida);
        $stmt->execute();
        header("Location: ../2-Read/listar-medida.php");
    } catch (PDOException $e) {
        die("Erro ao editar Unidade de Medida: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Unidade de Medida</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <h1>Editar Unidade de Medida</h1>
    <form method="POST" action="">
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" value="<?= $medida['descricao'] ?>" required>
        <input type="submit" value="Salvar Alterações">
    </form>
    <a href="../2-Read/listar-medida.php">Voltar para a Lista de Unidades de Medida</a>
</body>
</html>
