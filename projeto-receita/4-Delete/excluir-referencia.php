<?php
include "../conexao.php";

if (isset($_GET['idReferencia'])) {
    $idReferencia = $_GET['idReferencia'];

    try {
        $stmt = $conexao->prepare("SELECT R.*, Funcionario.nome AS nomeFuncionario FROM Referencia R
                                    INNER JOIN Funcionario ON R.id_funcionario = Funcionario.id_funcionario
                                    WHERE R.id_referencia = ?"); // Alterado para id_referencia
        $stmt->bindParam(1, $idReferencia, PDO::PARAM_INT);
        $stmt->execute();
        $referencia = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$referencia) {
            die("Referência de Restaurante não encontrada.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar Referência de Restaurante: " . $e->getMessage());
    }
} else {
    die("ID de Referência de Restaurante não fornecido.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $stmt = $conexao->prepare("DELETE FROM Referencia WHERE id_referencia = ?");
        $stmt->bindParam(1, $idReferencia, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: ../2-Read/listar-referencia.php");
    } catch (PDOException $e) {
        die("Erro ao excluir Referência de Restaurante: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Excluir Referência de Restaurante</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Excluir Referência de Restaurante</h1>
    <p>Tem certeza de que deseja excluir a referência de restaurante de <?= $referencia['nomeFuncionario']; ?>?</p>
    <form method="POST" action="">
        <input type="submit" value="Sim, Excluir">
    </form>
    <a href="../2-Read/listar-referencia.php">Cancelar</a>
</body>
</html>
