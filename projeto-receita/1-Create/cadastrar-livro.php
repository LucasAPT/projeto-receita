<?php
include "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST["titulo"];
    $isbn = $_POST["isbn"];
    $editor = $_POST["editor"];

    // Verifica se um arquivo de capa foi enviado
    if (isset($_FILES['capa_livro']) && $_FILES['capa_livro']['error'] === UPLOAD_ERR_OK) {
        // Diretório de destino para o upload das imagens de capa
        $diretorio_destino = '../fotos/';

        // Nome do arquivo de capa
        $nome_arquivo = $_FILES['capa_livro']['name'];

        // Move o arquivo de capa para o diretório de destino
        move_uploaded_file($_FILES['capa_livro']['tmp_name'], $diretorio_destino . $nome_arquivo);

        try {
            $stmt = $conexao->prepare("INSERT INTO Livro (titulo, isbn, editor, capa_livro) VALUES (?, ?, ?, ?)");
            $stmt->execute([$titulo, $isbn, $editor, $diretorio_destino . $nome_arquivo]);
            header("Location: ../2-Read/listar-livro.php");
        } catch (PDOException $e) {
            die("Erro ao cadastrar livro: " . $e->getMessage());
        }
    } else {
        echo "Erro ao fazer o upload da imagem da capa.";
    }
}

// Recupere a lista de funcionários que podem ser editores
try {
    $stmtFuncionarios = $conexao->prepare("SELECT id_funcionario, nome FROM Funcionario WHERE id_cargo = (SELECT id_cargo FROM Cargo WHERE descricao = 'editor') ORDER BY nome");
    $stmtFuncionarios->execute();
    $editores = $stmtFuncionarios->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar editores: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Livro</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <a href="../index.php">Menu Principal</a>
    <h1>Cadastrar Livro</h1>
    <form method="POST" action="" enctype="multipart/form-data"> <!-- Adicione o enctype para permitir o envio de arquivos -->
        <label for="titulo">Título do Livro:</label>
        <input type="text" name="titulo" required><br>
        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" required><br>
        <label for="editor">Editor:</label>
        <select name="editor" required>
            <?php foreach ($editores as $editor): ?>
                <option value="<?= $editor['id_funcionario'] ?>"><?= $editor['nome'] ?></option>
            <?php endforeach; ?>
        </select><br>
        <label for="capa_livro">Capa do Livro:</label>
        <input type="file" name="capa_livro" accept="image/*" required><br> <!-- Campo para seleção da imagem da capa -->
        <input type="submit" value="Cadastrar">
    </form>
    <a href="../2-Read/listar-livro.php">Voltar para a Lista de Livros</a>
</body>
</html>
