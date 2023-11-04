<?php
include "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $data_criacao = $_POST["data_criacao"];
    $categoria = $_POST["categoria"];
    $modo_preparo = $_POST["modo_preparo"];
    $qtde_porcao = $_POST["qtde_porcao"];
    $ind_receita_inedita = isset($_POST["ind_receita_inedita"]) ? 1 : 0;
    $cozinheiro = $_POST["cozinheiro"];

    // Processamento da imagem da receita
    $foto_receita = "fotos/"; // Caminho padrão se nenhuma imagem for fornecida

    if (isset($_FILES["foto_receita"]) && $_FILES["foto_receita"]["error"] == UPLOAD_ERR_OK) {
        $uploadDir = "../fotos/"; // Altere para o diretório correto
        $uploadFile = $uploadDir . basename($_FILES["foto_receita"]["name"]);

        if (move_uploaded_file($_FILES["foto_receita"]["tmp_name"], $uploadFile)) {
            // O upload foi bem-sucedido, você pode salvar o caminho da imagem no banco de dados
            $foto_receita = $uploadFile;
        }
    }

    // Processamento dos ingredientes selecionados
    $ingredientes_selecionados = isset($_POST["ingredientes_selecionados"]) ? $_POST["ingredientes_selecionados"] : array();
    $quantidades = $_POST["quantidade"]; // Array de quantidades
    $medidas = $_POST["medida"]; // Array de medidas

    try {
        $stmt = $conexao->prepare("INSERT INTO Receita (nome, data_criacao, id_categoria, modo_preparo, qtde_porcao, ind_receita_inedita, id_cozinheiro, foto_receita) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $data_criacao);
        $stmt->bindParam(3, $categoria);
        $stmt->bindParam(4, $modo_preparo);
        $stmt->bindParam(5, $qtde_porcao);
        $stmt->bindParam(6, $ind_receita_inedita);
        $stmt->bindParam(7, $cozinheiro);
        $stmt->bindParam(8, $foto_receita); // Salve o caminho da imagem no banco
        $stmt->execute();

        $id_receita = $conexao->lastInsertId(); // Obtém o ID da última receita inserida

        // Insere os ingredientes selecionados na tabela de composição
        for ($i = 0; $i < count($ingredientes_selecionados); $i++) {
            $stmt = $conexao->prepare("INSERT INTO Composicao (id_receita, id_ingrediente, id_medida, qtd_ingrediente) VALUES (?, ?, ?, ?)");
            $stmt->bindParam(1, $id_receita);
            $stmt->bindParam(2, $ingredientes_selecionados[$i]);
            $stmt->bindParam(3, $medidas[$i]);
            $stmt->bindParam(4, $quantidades[$i]);
            $stmt->execute();
        }

        header("Location: ../2-Read/listar-receita.php");
    } catch (PDOException $e) {
        die("Erro ao cadastrar receita: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Receita</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
</head>
<body>
    <h1>Cadastro de Receita</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required><br>
        <label for="data_criacao">Data de Criação:</label>
        <input type="date" name="data_criacao" required><br>
        <label for="categoria">Categoria:</label>
        <select name="categoria" required>
            <?php
            // Consulta para buscar as categorias existentes em ordem alfabética
            $sqlCategorias = "SELECT id_categoria, descricao FROM Categoria ORDER BY descricao";
            $stmtCategorias = $conexao->prepare($sqlCategorias);
            $stmtCategorias->execute();
            $resultCategorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

            // Verifique se há categorias disponíveis
            if (!empty($resultCategorias)) {
                foreach ($resultCategorias as $rowCategoria) {
                    // Crie uma opção para cada categoria disponível
                    echo '<option value="' . $rowCategoria['id_categoria'] . '">' . $rowCategoria['descricao'] . '</option>';
                }
            } else {
                echo '<option value="">Nenhuma categoria disponível</option>';
            }
            ?>
        </select>
        <label for="foto_receita">Foto da Receita:</label>
        <input type="file" name="foto_receita" accept="image/*" required><br>
        <label for="ingredientes">Ingredientes:</label><br>
        <div id="ingredient-container">
            <!-- Aqui adicionamos dinamicamente os campos de ingredientes -->
        </div>
        <button type="button" id="add-ingredient">Adicionar Ingrediente</button>
        <a href="../1-Create/cadastrar-ingrediente.php">Cadastrar novo ingrediente</a>
        <label for="modo_preparo">Modo de Preparo:</label>
        <textarea name="modo_preparo" maxlength="255" required></textarea><br>
        <label for="qtde_porcao">Qtd Porção:</label>
        <input type="number" name="qtde_porcao" required><br>
        <label for="ind_receita_inedita">Receita Inédita:</label>
        <input type="checkbox" name="ind_receita_inedita" value="1"> Sim <br>
        <label for="cozinheiro">Cozinheiro:</label>
        <select name="cozinheiro" required>
            <?php
            // Consulta para buscar os cozinheiros cadastrados em ordem alfabética que tenham o cargo de cozinheiro
            $sqlCozinheiros = "SELECT f.id_funcionario, f.nome FROM Funcionario f
                INNER JOIN Cargo c ON f.id_cargo = c.id_cargo
                WHERE c.descricao = 'cozinheiro' ORDER BY f.nome";
            $stmtCozinheiros = $conexao->prepare($sqlCozinheiros);
            $stmtCozinheiros->execute();
            $resultCozinheiros = $stmtCozinheiros->fetchAll(PDO::FETCH_ASSOC);

            // Verifique se há cozinheiros disponíveis
            if (!empty($resultCozinheiros)) {
                foreach ($resultCozinheiros as $rowCozinheiro) {
                    // Crie uma opção para cada cozinheiro disponível
                    echo '<option value="' . $rowCozinheiro['id_funcionario'] . '">' . $rowCozinheiro['nome'] . '</option>';
                }
            } else {
                echo '<option value="">Nenhum cozinheiro disponível</option>';
            }
            ?>
        </select>
        <input type="submit" value="Cadastrar Receita">
    </form>
    <a href="../2-Read/listar-receita.php">Voltar</a>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ingredientContainer = document.getElementById("ingredient-container");
            const addIngredientButton = document.getElementById("add-ingredient");

            // Função para criar campos de ingredientes dinamicamente
            function createIngredientFields() {
                const ingredientDiv = document.createElement("div");
                ingredientDiv.innerHTML = `
                    <select name="ingredientes_selecionados[]">
                        <?php
                        // Consulta para buscar os ingredientes cadastrados em ordem alfabética
                        $sqlIngredientes = "SELECT id_ingrediente, nome FROM Ingrediente ORDER BY nome";
                        $stmtIngredientes = $conexao->prepare($sqlIngredientes);
                        $stmtIngredientes->execute();
                        $resultIngredientes = $stmtIngredientes->fetchAll(PDO::FETCH_ASSOC);

                        // Verifique se há ingredientes disponíveis
                        if (!empty($resultIngredientes)) {
                            foreach ($resultIngredientes as $rowIngrediente) {
                                // Crie uma opção para cada ingrediente disponível
                                echo '<option value="' . $rowIngrediente['id_ingrediente'] . '">' . $rowIngrediente['nome'] . '</option>';
                            }
                        } else {
                            echo '<option value="">Nenhum ingrediente disponível</option>';
                        }
                        ?>
                    </select>
                    <input type="number" name="quantidade[]" min="1" placeholder="Quantidade" required>
                    <select name="medida[]">
                        <?php
                        // Consulta para buscar as medidas cadastradas
                        $sqlMedidas = "SELECT id_medida, descricao FROM Medida";
                        $stmtMedidas = $conexao->prepare($sqlMedidas);
                        $stmtMedidas->execute();
                        $resultMedidas = $stmtMedidas->fetchAll(PDO::FETCH_ASSOC);

                        // Verifique se há medidas disponíveis
                        if (!empty($resultMedidas)) {
                            foreach ($resultMedidas as $rowMedida) {
                                // Crie uma opção para cada medida disponível
                                echo '<option value="' . $rowMedida['id_medida'] . '">' . $rowMedida['descricao'] . '</option>';
                            }
                        } else {
                            echo '<option value="">Nenhuma medida disponível</option>';
                        }
                        ?>
                    </select>
                    <button type="button" class="remove-ingredient">Remover</button>
                `;
                ingredientContainer.appendChild(ingredientDiv);

                // Configura um manipulador de eventos para remover o campo de ingrediente
                const removeButton = ingredientDiv.querySelector(".remove-ingredient");
                removeButton.addEventListener("click", function () {
                    ingredientContainer.removeChild(ingredientDiv);
                });
            }

            // Adicionar um campo de ingrediente quando o botão "Adicionar Ingrediente" é clicado
            addIngredientButton.addEventListener("click", createIngredientFields);

            // Adicione um campo de ingrediente inicialmente
            createIngredientFields();
        });
    </script>
</body>
</html>