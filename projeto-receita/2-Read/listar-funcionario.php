<?php
include "../conexao.php";

try {
    $stmt = $conexao->prepare("SELECT f.id_funcionario, f.nome, f.rg, f.data_ingresso, f.salario, c.descricao as cargo, f.nome_fantasia FROM Funcionario f LEFT JOIN Cargo c ON f.id_cargo = c.id_cargo");
    $stmt->execute();
    $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar funcionários: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listagem de Funcionários</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Listagem de Funcionários</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>RG</th>
            <th>Data de Ingresso</th>
            <th>Salário</th>
            <th>Cargo</th>
            <th>Nome Fantasia (Apelido)</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($funcionarios as $funcionario): ?>
            <tr>
                <td><?= $funcionario['id_funcionario'] ?></td>
                <td><?= $funcionario['nome'] ?></td>
                <td><?= $funcionario['rg'] ?></td>
                <td><?= $funcionario['data_ingresso'] ?></td>
                <td><?= $funcionario['salario'] ?></td>
                <td><?= $funcionario['cargo'] ?></td> <!-- Mostra a descrição do cargo -->
                <td><?= $funcionario['nome_fantasia'] ?></td>
                <td>
                    <a href="../3-Update/editar-funcionario.php?id=<?= $funcionario['id_funcionario'] ?>">Editar</a>
                    <a href="../4-Delete/excluir-funcionario.php?id=<?= $funcionario['id_funcionario'] ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../1-Create/cadastrar-funcionario.php">Adicionar Funcionário</a>
</body>
</html>
