<?php
include "../conexao.php";

try {
    $stmt = $conexao->prepare("SELECT id_categoria, descricao FROM Categoria");
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar categorias: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listagem de Categorias</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Listagem de Categorias</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($categorias as $categoria): ?>
            <tr>
                <td><?= $categoria['id_categoria'] ?></td>
                <td><?= $categoria['descricao'] ?></td>
                <td>
                    <a href="../3-Update/editar-categoria.php?id=<?= $categoria['id_categoria'] ?>">Editar</a>
                    <a href="../4-Delete/excluir-categoria.php?id=<?= $categoria['id_categoria'] ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../1-Create/cadastrar-categoria.php">Cadastrar Nova Categoria</a>
</body>
</html>
