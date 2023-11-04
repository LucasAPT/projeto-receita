<?php
include "../conexao.php";

try {
    $stmt = $conexao->prepare("SELECT r.id_receita, r.nome, r.data_criacao, c.descricao AS categoria, r.ind_receita_inedita, f.nome AS cozinheiro, r.modo_preparo, r.qtde_porcao, GROUP_CONCAT(CONCAT(i.nome, ' (', comp.qtd_ingrediente, m.descricao, ')') ORDER BY i.nome ASC SEPARATOR ', ') AS ingredientes
        FROM Receita r
        LEFT JOIN Categoria c ON r.id_categoria = c.id_categoria
        LEFT JOIN Funcionario f ON r.id_cozinheiro = f.id_funcionario
        LEFT JOIN Composicao comp ON r.id_receita = comp.id_receita
        LEFT JOIN Ingrediente i ON comp.id_ingrediente = i.id_ingrediente
        LEFT JOIN Medida m ON comp.id_medida = m.id_medida
        GROUP BY r.id_receita");
    $stmt->execute();
    $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar receitas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <a href="../index.php">Menu Principal</a>
    <title>Listagem de Receitas</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <h1>Listagem de Receitas</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Data de Criação</th>
            <th>Categoria</th>
            <th>Ingredientes</th>
            <th>Modo de Preparo</th>
            <th>Qtde Porção</th>
            <th>Receita Inédita</th>
            <th>Cozinheiro</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($receitas as $receita): ?>
            <tr>
                <td><?= $receita['id_receita'] ?></td>
                <td><?= $receita['nome'] ?></td>
                <td><?= $receita['data_criacao'] ?></td>
                <td><?= $receita['categoria'] ?></td>
                <td><?= $receita['ingredientes'] ?></td>
                <td><?= isset($receita['modo_preparo']) ? $receita['modo_preparo'] : 'N/A' ?></td>
                <td><?= isset($receita['qtde_porcao']) ? $receita['qtde_porcao'] : 'N/A' ?></td>
                <td><?= $receita['ind_receita_inedita'] ? 'Sim' : 'Não' ?></td>
                <td><?= $receita['cozinheiro'] ?></td>
                <td>
                    <a href="../3-Update/editar-receita.php?id=<?= $receita['id_receita'] ?>">Editar</a>
                    <a href="../4-Delete/excluir-receita.php?id=<?= $receita['id_receita'] ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../1-Create/cadastrar-receita.php">Adicionar Receita</a>
</body>
</html>
