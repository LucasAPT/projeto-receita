<?php
include "../conexao.php";

try {
    $stmt = $conexao->prepare("SELECT D.*, Funcionario.nome AS nomeDegustador, C.nome AS nomeCozinheiro FROM Degustacao D
                                INNER JOIN Funcionario ON D.degustador = Funcionario.id_funcionario
                                INNER JOIN Funcionario C ON D.idcozinheiro = C.id_funcionario");
    $stmt->execute();
    $degustacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar degustações: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listagem de Degustações</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Listagem de Degustações</h1>
    <table>
        <tr>
            <th>Degustador</th>
            <th>Cozinheiro</th>
            <th>Nome da Receita</th>
            <th>Data de Degustação</th>
            <th>Nota da Degustação</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($degustacoes as $degustacao): ?>
            <tr>
                <td><?= $degustacao['nomeDegustador'] ?></td>
                <td><?= $degustacao['nomeCozinheiro'] ?></td>
                <td><?= $degustacao['nome'] ?></td>
                <td><?= $degustacao['data_degustacao'] ?></td>
                <td><?= $degustacao['nota_degustacao'] ?></td>
                <td>
                    <a href="../3-Update/editar-degustacao.php?id=<?= $degustacao['id_degustacao'] ?>">Editar</a>
                    <a href="../4-Delete/excluir-degustacao.php?id=<?= $degustacao['id_degustacao'] ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../1-Create/cadastrar-degustacao.php">Cadastrar Nova Degustação</a>
</body>
</html>
