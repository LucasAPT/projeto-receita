<?php
include "../conexao.php";

try {
    $stmt = $conexao->prepare("SELECT Livro.idLivro, Livro.titulo, Livro.isbn, Funcionario.nome AS nome_editor
                              FROM Livro
                              JOIN Funcionario ON Livro.editor = Funcionario.id_funcionario");
    $stmt->execute();
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar livros: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listagem de Livros</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Listagem de Livros</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>ISBN</th>
            <th>Editor</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($livros as $livro): ?>
            <tr>
                <td><?= $livro['idLivro'] ?></td>
                <td><?= $livro['titulo'] ?></td>
                <td><?= $livro['isbn'] ?></td>
                <td><?= $livro['nome_editor'] ?></td>
                <td>
                    <a href="../3-Update/editar-livro.php?id=<?= $livro['idLivro'] ?>">Editar</a>
                    <a href="../4-Delete/excluir-livro.php?id=<?= $livro['idLivro'] ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../1-Create/cadastrar-livro.php">Cadastrar Novo Livro</a>
</body>
</html>
