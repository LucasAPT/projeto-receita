<?php
include "../conexao.php";

// Recuperar as publicações de livros
$publicacoes = $conexao->query("SELECT p.idLivro, l.titulo AS Livro, p.nome AS NomeDaPublicacao, GROUP_CONCAT(r.nome SEPARATOR ', ') AS Receitas
                                FROM Publicacao p
                                INNER JOIN Livro l ON p.idLivro = l.idLivro
                                LEFT JOIN Receita r ON p.idReceita = r.id_receita
                                GROUP BY p.idLivro, l.titulo, p.nome")
    ->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listagem de Publicações de Livros</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            background-color: #337ab7;
            color: #fff;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #337ab7;
            color: #fff;
        }
    </style>
</head>
<body>
<a href="../index.php">Menu Principal</a>
    <h1>Listagem de Publicações de Livros</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Livro</th>
                <th>Receita</th>
                <th>Nome da Publicação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($publicacoes as $publicacao) : ?>
                <tr>
                    <td><?= $publicacao['idLivro'] ?></td>
                    <td><?= $publicacao['Livro'] ?></td>
                    <td><?= $publicacao['Receitas'] ?></td>
                    <td><?= $publicacao['NomeDaPublicacao'] ?></td>
                    <td>
                        <a href="../3-Update/editar-publicacao.php?id=<?= $publicacao['idLivro'] ?>">Editar</a>
                        <a href="../4-Delete/excluir-publicacao.php?id=<?= $publicacao['idLivro'] ?>">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="../1-Create/cadastrar-publicacao.php">Cadastrar Nova Publicação de Livro</a>
</body>
</html>