<?php
include "../conexao.php";

try {
    $stmt = $conexao->prepare("SELECT R.*, Restaurante.nome AS nomeRestaurante, Funcionario.nome AS nomeFuncionario FROM Referencia R
                            INNER JOIN Restaurante ON R.idRestaurante = Restaurante.idRestaurante
                            INNER JOIN Funcionario ON R.id_funcionario = Funcionario.id_funcionario");
    $stmt->execute();
    $referencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar referências: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listagem de Referências de Restaurante</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Listagem de Referências de Restaurante</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Funcionário</th>
            <th>Restaurante</th>
            <th>Data de Início</th>
            <th>Data de Término</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($referencias as $referencia): ?>
            <tr>
                <td><?= $referencia['idReferencia'] ?></td>
                <td><?= $referencia['nomeFuncionario'] ?></td>
                <td><?= $referencia['nomeRestaurante'] ?></td>
                <td><?= $referencia['data_inicio'] ?></td>
                <td><?= $referencia['data_fim'] ?></td>
                <td>
                    <a href="../3-Update/editar-referencia.php?idReferencia=<?= $referencia['idReferencia'] ?>">Editar</a>
                    <a href="../4-Delete/excluir-referencia.php?idReferencia=<?= $referencia['idReferencia'] ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../1-Create/cadastrar-referencia.php">Adicionar Referência de Restaurante</a>
</body>
</html>
