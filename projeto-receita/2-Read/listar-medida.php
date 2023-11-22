<?php
include "../conexao.php";

try {
    $stmt = $conexao->prepare("SELECT id_medida, descricao FROM Medida");
    $stmt->execute();
    $medidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar medidas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listagem de Medidas</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Listagem de Medidas</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($medidas as $medida): ?>
            <tr>
                <td><?= $medida['id_medida'] ?></td>
                <td><?= $medida['descricao'] ?></td>
                <td>
                    <a href="../3-Update/editar-medida.php?id=<?= $medida['id_medida'] ?>">Editar</a>
                    <a href="../4-Delete/excluir-medida.php?id=<?= $medida['id_medida'] ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../1-Create/cadastrar-medida.php">Cadastrar Nova Medida</a>
</body>
</html>
