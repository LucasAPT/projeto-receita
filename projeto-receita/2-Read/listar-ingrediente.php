<?php
include "../conexao.php";

try {
    $stmt = $conexao->prepare("SELECT * FROM Ingrediente");
    $stmt->execute();
    $ingredientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar ingredientes: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listagem de Ingredientes</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Listagem de Ingredientes</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($ingredientes as $ingrediente): ?>
            <tr>
                <td><?= $ingrediente['id_ingrediente'] ?></td>
                <td><?= $ingrediente['nome'] ?></td>
                <td><?= isset($ingrediente['descricao']) ? $ingrediente['descricao'] : 'N/A' ?></td>
                <td>
                    <a href="../3-Update/editar-ingrediente.php?id=<?= $ingrediente['id_ingrediente'] ?>">Editar</a>
                    <a href="../4-Delete/excluir-ingrediente.php?id=<?= $ingrediente['id_ingrediente'] ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../1-Create/cadastrar-ingrediente.php">Adicionar Ingrediente</a>
    <a href="../2-Read/listar-receita.php">Voltar para a Lista de Receitas</a>
</body>
</html>
