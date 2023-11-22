<?php
include "../conexao.php";

// Verifique se o ID da medida foi fornecido na URL
if (isset($_GET['id'])) {
    $idMedida = $_GET['id'];

    // Verifique se a medida existe no banco de dados
    $sql = "SELECT * FROM Medida WHERE id_medida = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(1, $idMedida, PDO::PARAM_INT);
    $stmt->execute();
    $medida = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($medida) {
        // O ID da medida é válido, você pode excluir a medida aqui
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Exclua a medida do banco de dados
            $sql = "DELETE FROM Medida WHERE id_medida = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(1, $idMedida, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: ../2-Read/listar-medida.php");
        }
    } else {
        echo "Medida não encontrada.";
    }
} else {
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Excluir Unidade de Medida</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <h1>Excluir Unidade de Medida</h1>
    <p>Tem certeza de que deseja excluir a medida: <?php echo $medida['descricao']; ?>?</p>
    <form method="POST" action="">
        <input type="submit" value="Sim, Excluir">
    </form>
    <a href="../2-Read/listar-medida.php">Cancelar</a>
</body>
</html>
