<?php
include "../conexao.php";

try {
    $stmt = $conexao->prepare("SELECT * FROM Cargo");
    $stmt->execute();
    $cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar cargos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listagem de Cargos</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Listagem de Cargos</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($cargos as $cargo): ?>
            <tr>
                <td><?= $cargo['id_cargo'] ?></td>
                <td><?= $cargo['descricao'] ?></td>
                <td>
                    <a href="../3-Update/editar-cargo.php?id=<?= $cargo['id_cargo'] ?>">Editar</a>
                    <a href="../4-Delete/excluir-cargo.php?id=<?= $cargo['id_cargo'] ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../1-Create/cadastrar-cargo.php">Cadastrar Novo Cargo</a>
</body>
</html>
