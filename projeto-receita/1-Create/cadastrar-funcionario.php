<?php
include "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $rg = $_POST["rg"];
    $data_ingresso = $_POST["data_ingresso"];
    $salario = $_POST["salario"];
    $id_cargo = $_POST["id_cargo"];
    $nome_fantasia = $_POST["nome_fantasia"];
    
    // Processamento da imagem do funcionário
    $foto_funcionario = "caminho/para/diretorio/imagens/funcionarios/default.jpg"; // Caminho padrão se nenhuma imagem for fornecida

    if (isset($_FILES["foto_funcionario"]) && $_FILES["foto_funcionario"]["error"] == UPLOAD_ERR_OK) {
        $uploadDir = "../fotos/"; // Altere para o diretório correto
        $uploadFile = $uploadDir . basename($_FILES["foto_funcionario"]["name"]);

        if (move_uploaded_file($_FILES["foto_funcionario"]["tmp_name"], $uploadFile)) {
            // O upload foi bem-sucedido, você pode salvar o caminho da imagem no banco de dados
            $foto_funcionario = $uploadFile;
        }
    }

    try {
        $stmt = $conexao->prepare("INSERT INTO Funcionario (nome, rg, data_ingresso, salario, id_cargo, nome_fantasia, foto_funcionario) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $rg);
        $stmt->bindParam(3, $data_ingresso);
        $stmt->bindParam(4, $salario);
        $stmt->bindParam(5, $id_cargo);
        $stmt->bindParam(6, $nome_fantasia);
        $stmt->bindParam(7, $foto_funcionario); // Salve o caminho da imagem no banco
        $stmt->execute();
        header("Location: ../2-Read/listar-funcionario.php");
    } catch (PDOException $e) {
        die("Erro ao cadastrar funcionário: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Funcionário</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <h1>Cadastro de Funcionário</h1>
    <form method="POST" action="" enctype="multipart/form-data"> <!-- Adicionado enctype para suportar upload de arquivo -->
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required><br>
        <label for="rg">RG:</label>
        <input type="text" name="rg" required><br>
        <label for="data_ingresso">Data de Admissão:</label>
        <input type="date" name="data_ingresso" required><br>
        <label for="salario">Salário:</label>
        <input type="text" name="salario" required><br>
        <label for="id_cargo">Cargo:</label>
        <select name="id_cargo" required>
            <?php
            // Consulta para buscar os cargos disponíveis no banco de dados
            $sql = "SELECT id_cargo, descricao FROM Cargo ORDER BY descricao";
            $stmt = $conexao->prepare($sql);
            $stmt->execute();

            // Verifique se há resultados
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($result)) {
                foreach ($result as $row) {
                    // Crie uma opção para cada cargo disponível
                    echo '<option value="' . $row['id_cargo'] . '">' . $row['descricao'] . '</option>';
                }
            } else {
                echo '<option value="">Nenhum cargo disponível</option>';
            }
            ?>
        </select><br>
        <label for="nome_fantasia">Nome Fantasia (Apelido):</label>
        <input type="text" name="nome_fantasia"><br>
        <label for="foto_funcionario">Foto do Funcionário:</label> <!-- Campo para fazer o upload da imagem -->
        <input type="file" name="foto_funcionario" accept="image/*" required><br> <br>
        <input type="submit" value="Cadastrar">
    </form>
    <a href="../2-Read/listar-funcionario.php">Voltar</a>
</body>
</html>