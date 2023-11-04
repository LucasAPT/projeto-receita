<?php
include "../conexao.php";

if (isset($_GET['id'])) {
    $idPublicacao = $_GET['id'];

    $sql = "SELECT nome FROM Publicacao WHERE idLivro = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(1, $idPublicacao, PDO::PARAM_INT);
    $stmt->execute();
    $publicacao = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($publicacao) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $sql = "DELETE FROM Publicacao WHERE idLivro = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(1, $idPublicacao, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: ../2-Read/listar-publicacao.php");
        }
    } else {
        echo "Publicação não encontrada.";
    }
} else {
    echo "ID da publicação não fornecido.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Excluir Publicação de Livro</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <h1>Excluir Publicação de Livro</h1>
    <p>Tem certeza de que deseja excluir a publicação: <?php echo $publicacao['nome']; ?>?</p>
    <form method="POST" action="">
        <input type="submit" value="Sim, Excluir">
    </form>
    <a href="../2-Read/listar-publicacao.php">Cancelar</a>
</body>
</html>
