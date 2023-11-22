<?php
include "../conexao.php";

try {
    $stmt = $conexao->prepare("SELECT * FROM Restaurante");
    $stmt->execute();
    $restaurantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar restaurantes: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listagem de Restaurantes</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Listagem de Restaurantes</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($restaurantes as $restaurante): ?>
            <tr>
                <td><?= $restaurante['idRestaurante'] ?></td>
                <td><?= $restaurante['nome'] ?></td>
                <td>
                    <a href="../3-Update/editar-restaurante.php?id=<?= $restaurante['idRestaurante'] ?>">Editar</a>
                    <a href="../4-Delete/excluir-restaurante.php?id=<?= $restaurante['idRestaurante'] ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../1-Create/cadastrar-restaurante.php">Cadastrar Novo Restaurante</a>
    <a href="../1-Create/cadastrar-referencia.php">Cadastrar Referência de Funcionário</a>
    <a href="../2-Read/listar-receita.php">Voltar para a Lista de Receitas</a>
</body>
</html>
