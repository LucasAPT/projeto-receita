<?php
include "../conexao.php";

if (isset($_GET['id'])) {
    $idCargo = $_GET['id'];

    try {
        $stmt = $conexao->prepare("SELECT * FROM Cargo WHERE id_cargo = ?");
        $stmt->bindParam(1, $idCargo, PDO::PARAM_INT);
        $stmt->execute();
        $cargo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cargo) {
            die("Cargo não encontrado.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar Cargo: " . $e->getMessage());
    }
} else {
    die("ID de Cargo não fornecido.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $descricao = $_POST["descricao"];

    try {
        $stmt = $conexao->prepare("UPDATE Cargo SET descricao = ? WHERE id_cargo = ?");
        $stmt->bindParam(1, $descricao);
        $stmt->bindParam(2, $idCargo, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: ../2-Read/listar-cargo.php");
    } catch (PDOException $e) {
        die("Erro ao editar Cargo: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Cargo</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Editar Cargo</h1>
    <form method="POST" action="">
        <label for="descricao">Descrição do Cargo:</label>
        <input type="text" name="descricao" value="<?= $cargo['descricao'] ?>" required>
        <input type="submit" value="Salvar Alterações">
    </form>
    <a href="../2-Read/listar-cargo.php">Voltar para a Lista de Cargos</a>
</body>
</html>
