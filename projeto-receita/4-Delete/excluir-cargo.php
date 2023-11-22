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
    try {
        $stmt = $conexao->prepare("DELETE FROM Cargo WHERE id_cargo = ?");
        $stmt->bindParam(1, $idCargo, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: ../2-Read/listar-cargo.php");
    } catch (PDOException $e) {
        die("Erro ao excluir Cargo: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Excluir Cargo</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Excluir Cargo</h1>
    <p>Tem certeza de que deseja excluir o cargo: <?= $cargo['descricao']; ?>?</p>
    <form method="POST" action="">
        <input type="submit" value="Sim, Excluir">
    </form>
    <a href="../2-Read/listar-cargo.php">Cancelar</a>
</body>
</html>
