<?php
include "../conexao.php";

if (isset($_GET['id'])) {
    $idLivro = $_GET['id'];

    try {
        $stmt = $conexao->prepare("SELECT Livro.idLivro, Livro.titulo, Livro.isbn, Livro.editor, Livro.capa_livro, Funcionario.nome AS nome_editor
                                  FROM Livro
                                  JOIN Funcionario ON Livro.editor = Funcionario.id_funcionario
                                  WHERE idLivro = ?");
        $stmt->bindParam(1, $idLivro, PDO::PARAM_INT);
        $stmt->execute();
        $livro = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$livro) {
            die("Livro não encontrado.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar Livro: " . $e->getMessage());
    }
} else {
    die("ID de Livro não fornecido.");
}

// Buscar todos os funcionários com cargo "editor"
try {
    $stmt = $conexao->prepare("SELECT id_funcionario, nome FROM Funcionario WHERE id_cargo = (SELECT id_cargo FROM Cargo WHERE descricao = 'editor')");
    $stmt->execute();
    $editores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar editores: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST["titulo"];
    $isbn = $_POST["isbn"];
    $editor = $_POST["editor"];

    // Verifica se um novo arquivo de capa foi enviado
    if (isset($_FILES['nova_capa_livro']) && $_FILES['nova_capa_livro']['error'] === UPLOAD_ERR_OK) {
        // Diretório de destino para o upload das imagens de capa
        $diretorio_destino = '../fotos/';

        // Nome do arquivo de capa
        $nome_arquivo = $_FILES['nova_capa_livro']['name'];

        // Move o novo arquivo de capa para o diretório de destino
        move_uploaded_file($_FILES['nova_capa_livro']['tmp_name'], $diretorio_destino . $nome_arquivo);

        // Atualize o caminho da capa do livro no banco de dados
        $stmt = $conexao->prepare("UPDATE Livro SET capa_livro = ? WHERE idLivro = ?");
        $stmt->execute([$diretorio_destino . $nome_arquivo, $idLivro]);
    }

    // Atualize as informações do livro (título, ISBN, editor)
    try {
        $stmt = $conexao->prepare("UPDATE Livro
                                  SET titulo = ?, isbn = ?, editor = ?
                                  WHERE idLivro = ?");
        $stmt->bindParam(1, $titulo);
        $stmt->bindParam(2, $isbn);
        $stmt->bindParam(3, $editor, PDO::PARAM_INT);
        $stmt->bindParam(4, $idLivro, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: ../2-Read/listar-livro.php");
    } catch (PDOException $e) {
        die("Erro ao editar Livro: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Livro</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <h1>Editar Livro</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="titulo">Título do Livro:</label>
        <input type="text" name="titulo" value="<?= $livro['titulo'] ?>" required>
        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" value="<?= $livro['isbn'] ?>" required>
        <label for="editor">Editor:</label>
        <select name="editor" required>
            <?php foreach ($editores as $editor): ?>
                <option value="<?= $editor['id_funcionario'] ?>" <?= ($editor['id_funcionario'] == $livro['editor']) ? 'selected' : '' ?>>
                    <?= $editor['nome'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="nova_capa_livro">Nova Capa do Livro:</label>
        <input type="file" name="nova_capa_livro" accept="image/*"><br> <!-- Campo para seleção da nova imagem da capa -->
        <input type="submit" value="Salvar Alterações">
    </form>
    <a href="../2-Read/listar-livro.php">Voltar para a Lista de Livros</a>
</body>
</html>
