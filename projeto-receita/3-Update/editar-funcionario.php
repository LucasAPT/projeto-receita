<?php
include "../conexao.php";

if (isset($_GET["id"])) {
    $id_funcionario = $_GET["id"];
    
    try {
        $stmt = $conexao->prepare("SELECT * FROM Funcionario WHERE id_funcionario = ?");
        $stmt->bindParam(1, $id_funcionario);
        $stmt->execute();
        $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$funcionario) {
            die("Funcionário não encontrado.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar funcionário: " . $e->getMessage());
    }
} else {
    die("ID de funcionário não fornecido.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $rg = $_POST["rg"];
    $data_ingresso = $_POST["data_ingresso"];
    $salario = $_POST["salario"];
    $id_cargo = $_POST["id_cargo"];
    $nome_fantasia = $_POST["nome_fantasia"];
    
    // Processamento da imagem do funcionário
    $foto_funcionario = $funcionario['foto_funcionario']; // Manter a foto existente como padrão

    if (isset($_FILES["foto_funcionario"]) && $_FILES["foto_funcionario"]["error"] == UPLOAD_ERR_OK) {
        $uploadDir = "../fotos/"; // Altere para o diretório correto
        $uploadFile = $uploadDir . basename($_FILES["foto_funcionario"]["name"]);

        if (move_uploaded_file($_FILES["foto_funcionario"]["tmp_name"], $uploadFile)) {
            // O upload foi bem-sucedido, você pode salvar o caminho da imagem no banco de dados
            $foto_funcionario = $uploadFile;
        }
    }

    try {
        $stmt = $conexao->prepare("UPDATE Funcionario SET nome = ?, rg = ?, data_ingresso = ?, salario = ?, id_cargo = ?, nome_fantasia = ?, foto_funcionario = ? WHERE id_funcionario = ?");
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $rg);
        $stmt->bindParam(3, $data_ingresso);
        $stmt->bindParam(4, $salario);
        $stmt->bindParam(5, $id_cargo);
        $stmt->bindParam(6, $nome_fantasia);
        $stmt->bindParam(7, $foto_funcionario); // Salve o caminho da imagem no banco
        $stmt->bindParam(8, $id_funcionario);
        $stmt->execute();
        header("Location: ../2-Read/listar-funcionario.php");
    } catch (PDOException $e) {
        die("Erro ao editar funcionário: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Funcionário</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <h1>Editar Funcionário</h1>
    <form method="POST" action="" enctype="multipart/form-data"> <!-- Adicionado enctype para suportar upload de arquivo -->
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?= $funcionario['nome'] ?>" required><br>
        <label for="rg">RG:</label>
        <input type="text" name="rg" value="<?= $funcionario['rg'] ?>" required><br>
        <label for="data_ingresso">Data de Ingresso:</label>
        <input type="date" name="data_ingresso" value="<?= $funcionario['data_ingresso'] ?>" required><br>
        <label for="salario">Salário:</label>
        <input type="text" name="salario" value="<?= $funcionario['salario'] ?>" required><br>
        <label for="id_cargo">Cargo:</label>
        <input type="text" name="id_cargo" value="<?= $funcionario['id_cargo'] ?>" required><br>
        <label for="nome_fantasia">Nome Fantasia:</label>
        <input type="text" name="nome_fantasia" value="<?= $funcionario['nome_fantasia'] ?>" required><br>
        <label for="foto_funcionario">Nova Foto do Funcionário:</label> <!-- Campo para fazer o upload da nova imagem -->
        <input type="file" name="foto_funcionario" accept="image/*"><br> <br>
        <input type="submit" value="Salvar">
    </form>
    <a href="../2-Read/listar-funcionario.php">Voltar</a>
</body>
</html>
