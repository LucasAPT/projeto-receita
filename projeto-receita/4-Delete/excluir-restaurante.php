<?php
include "../conexao.php";

if (isset($_GET['id'])) {
    $idRestaurante = $_GET['id'];

    try {
        $stmt = $conexao->prepare("SELECT * FROM Restaurante WHERE idRestaurante = ?");
        $stmt->bindParam(1, $idRestaurante, PDO::PARAM_INT);
        $stmt->execute();
        $restaurante = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$restaurante) {
            die("Restaurante não encontrado.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar Restaurante: " . $e->getMessage());
    }
} else {
    die("ID de Restaurante não fornecido.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $stmt = $conexao->prepare("DELETE FROM Restaurante WHERE idRestaurante = ?");
        $stmt->bindParam(1, $idRestaurante, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: ../2-Read/listar-restaurante.php");
    } catch (PDOException $e) {
        die("Erro ao excluir Restaurante: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Excluir Restaurante</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Excluir Restaurante</h1>
    <p>Tem certeza de que deseja excluir o restaurante: <?= $restaurante['nome']; ?>?</p>
    <form method="POST" action="">
        <input type="submit" value="Sim, Excluir">
    </form>
    <a href="../2-Read/listar-restaurante.php">Cancelar</a>
</body>
</html>
