<?php
include "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $id_receita = $_GET["id"];

    try {
        $stmt = $conexao->prepare("SELECT r.id_receita, r.nome, r.data_criacao, r.foto_receita, r.id_categoria, r.modo_preparo, r.qtde_porcao, r.ind_receita_inedita, r.id_cozinheiro
            FROM Receita r
            WHERE r.id_receita = ?");
        $stmt->bindParam(1, $id_receita);
        $stmt->execute();
        $receita = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifique se a receita existe
        if (!$receita) {
            die("Receita não encontrada.");
        }

        // Obtenha os ingredientes da receita
        $stmt = $conexao->prepare("SELECT i.id_ingrediente, i.nome, c.qtd_ingrediente, c.id_medida
            FROM Composicao c
            INNER JOIN Ingrediente i ON c.id_ingrediente = i.id_ingrediente
            WHERE c.id_receita = ?");
        $stmt->bindParam(1, $id_receita);
        $stmt->execute();
        $ingredientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erro ao buscar receita: " . $e->getMessage());
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_receita"])) {
    $id_receita = $_POST["id_receita"];
    $nome = $_POST["nome"];
    $data_criacao = $_POST["data_criacao"];
    $categoria = $_POST["categoria"];
    $modo_preparo = $_POST["modo_preparo"];
    $qtde_porcao = $_POST["qtde_porcao"];
    $ind_receita_inedita = isset($_POST["ind_receita_inedita"]) ? 1 : 0;
    $cozinheiro = $_POST["cozinheiro"];

    // Processamento da imagem da receita
    $foto_receita = $_POST["foto_receita_atual"];

    if (isset($_FILES["foto_receita"]) && $_FILES["foto_receita"]["error"] == UPLOAD_ERR_OK) {
        $uploadDir = "../fotos/";
        $uploadFile = $uploadDir . basename($_FILES["foto_receita"]["name"]);

        if (move_uploaded_file($_FILES["foto_receita"]["tmp_name"], $uploadFile)) {
            $foto_receita = $uploadFile;
        }
    }

    // Processamento dos ingredientes selecionados
    $ingredientes_selecionados = isset($_POST["ingredientes_selecionados"]) ? $_POST["ingredientes_selecionados"] : array();
    $quantidades = $_POST["quantidade"];
    $medidas = $_POST["medida"];

    try {
        $stmt = $conexao->prepare("UPDATE Receita SET nome = ?, data_criacao = ?, id_categoria = ?, modo_preparo = ?, qtde_porcao = ?, ind_receita_inedita = ?, id_cozinheiro = ?, foto_receita = ? WHERE id_receita = ?");
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $data_criacao);
        $stmt->bindParam(3, $categoria);
        $stmt->bindParam(4, $modo_preparo);
        $stmt->bindParam(5, $qtde_porcao);
        $stmt->bindParam(6, $ind_receita_inedita);
        $stmt->bindParam(7, $cozinheiro);
        $stmt->bindParam(8, $foto_receita);
        $stmt->bindParam(9, $id_receita);
        $stmt->execute();

        // Exclua os ingredientes existentes desta receita
        $stmt = $conexao->prepare("DELETE FROM Composicao WHERE id_receita = ?");
        $stmt->bindParam(1, $id_receita);
        $stmt->execute();

        // Insira os ingredientes selecionados na tabela de composição
        for ($i = 0; $i < count($ingredientes_selecionados); $i++) {
            $stmt = $conexao->prepare("INSERT INTO Composicao (id_receita, id_ingrediente, id_medida, qtd_ingrediente) VALUES (?, ?, ?, ?)");
            $stmt->bindParam(1, $id_receita);
            $stmt->bindParam(2, $ingredientes_selecionados[$i]);
            $stmt->bindParam(3, $medidas[$ingredientes_selecionados[$i]]);
            $stmt->bindParam(4, $quantidades[$ingredientes_selecionados[$i]]);
            $stmt->execute();
        }

        header("Location: ../2-Read/listar-receita.php");
    } catch (PDOException $e) {
        die("Erro ao atualizar receita: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Receita</title>
    <link rel="stylesheet" type="text/css" href="../css/CRUD.css">
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
                        // Código PHP para buscar ingredientes
                        $sqlIngredientes = "SELECT id_ingrediente, nome FROM Ingrediente ORDER BY nome";
                        $stmtIngredientes = $conexao->prepare($sqlIngredientes);
                        $stmtIngredientes->execute();
                        $resultIngredientes = $stmtIngredientes->fetchAll(PDO::FETCH_ASSOC);
                        if (!empty($resultIngredientes)) {
                            foreach ($resultIngredientes as $rowIngrediente) {
                                echo '<option value="' . $rowIngrediente['id_ingrediente'] . '">' . $rowIngrediente['nome'] . '</option>';
                            }
                        } else {
                            echo '<option value="">Nenhum ingrediente disponível</option>';
                        }
                        ?>
                    </select>
                    <input type="text" name="quantidade[]" placeholder="Quantidade" required>
                    <select name="medida[]">
                        <?php
                        // Código PHP para buscar medidas
                        $sqlMedidas = "SELECT id_medida, descricao FROM Medida";
                        $stmtMedidas = $conexao->prepare($sqlMedidas);
                        $stmtMedidas->execute();
                        $resultMedidas = $stmtMedidas->fetchAll(PDO::FETCH_ASSOC);
                        if (!empty($resultMedidas)) {
                            foreach ($resultMedidas as $rowMedida) {
                                echo '<option value="' . $rowMedida['id_medida'] . '">' . $rowMedida['descricao'] . '</option>';
                            }
                        } else {
                            echo '<option value="">Nenhuma medida disponível</option>';
                        }
                        ?>
                    </select>
                    <button type="button" class="remove-ingredient" onclick="removeIngredient(this)">Remover</button>
                `;
                ingredientContainer.appendChild(ingredientDiv);
            }

            // Função para remover campos de ingredientes
            function removeIngredient(button) {
                ingredientContainer.removeChild(button.parentNode);
            }

            // Adicionar um campo de ingrediente quando o botão "Adicionar Ingrediente" é clicado
            addIngredientButton.addEventListener("click", createIngredientFields);

            // Verifique se há ingredientes existentes e adicione-os
            <?php
            if (!empty($ingredientes)) {
                foreach ($ingredientes as $ingrediente) {
                    echo 'createIngredientFields();'; // Chame a função para criar campos de ingredientes
                    echo 'const lastIngredientDiv = ingredientContainer.lastChild;';
                    echo 'lastIngredientDiv.querySelector("select[name=\'ingredientes_selecionados[]\']").value = ' . $ingrediente['id_ingrediente'] . ';';
                    echo 'lastIngredientDiv.querySelector("input[name=\'quantidade[]\']").value = "' . $ingrediente['qtd_ingrediente'] . '";';
                    echo 'lastIngredientDiv.querySelector("select[name=\'medida[]\']").value = ' . $ingrediente['id_medida'] . ';';
                }
            }
            ?>
        });
    </script>
</head>
<body>
    <h1>Editar Receita</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="id_receita" value="<?= $id_receita ?>">
        <input type="hidden" name="foto_receita_atual" value="<?= $receita['foto_receita'] ?>">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?= $receita['nome'] ?>" required><br>
        <label for="data_criacao">Data de Criação:</label>
        <input type="date" name="data_criacao" value="<?= $receita['data_criacao'] ?>" required><br>
        <label for="categoria">Categoria:</label>
        <select name="categoria" required>
            <?php
            $sqlCategorias = "SELECT id_categoria, descricao FROM Categoria ORDER BY descricao";
            $stmtCategorias = $conexao->prepare($sqlCategorias);
            $stmtCategorias->execute();
            $resultCategorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($resultCategorias)) {
                foreach ($resultCategorias as $rowCategoria) {
                    $selected = ($rowCategoria['id_categoria'] == $receita['id_categoria']) ? "selected" : "";
                    echo '<option value="' . $rowCategoria['id_categoria'] . '" ' . $selected . '>' . $rowCategoria['descricao'] . '</option>';
                }
            } else {
                echo '<option value="">Nenhuma categoria disponível</option>';
            }
            ?>
        </select><br>
        <label for="foto_receita">Foto da Receita:</label>
        <input type="file" name="foto_receita" accept="image/*"><br>
        <img src="<?= $receita['foto_receita'] ?>" width="150"><br>
        <label for="ingredientes">Ingredientes:</label><br>
        <div id="ingredient-container">
            <?php
            if (!empty($ingredientes)) {
                foreach ($ingredientes as $ingrediente) {
                    echo '<div>';
                    echo '<select name="ingredientes_selecionados[]">';
                    $sqlIngredientes = "SELECT id_ingrediente, nome FROM Ingrediente ORDER BY nome";
                    $stmtIngredientes = $conexao->prepare($sqlIngredientes);
                    $stmtIngredientes->execute();
                    $resultIngredientes = $stmtIngredientes->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($resultIngredientes)) {
                        foreach ($resultIngredientes as $rowIngrediente) {
                            $selected = ($rowIngrediente['id_ingrediente'] == $ingrediente['id_ingrediente']) ? "selected" : "";
                            echo '<option value="' . $rowIngrediente['id_ingrediente'] . '" ' . $selected . '>' . $rowIngrediente['nome'] . '</option>';
                        }
                    } else {
                        echo '<option value="">Nenhum ingrediente disponível</option>';
                    }
                    echo '</select>';
                    echo '<input type="text" name="quantidade[]" value="' . $ingrediente['qtd_ingrediente'] . '" placeholder="Quantidade">';
                    echo '<select name="medida[]">';
                    $sqlMedidas = "SELECT id_medida, descricao FROM Medida";
                    $stmtMedidas = $conexao->prepare($sqlMedidas);
                    $stmtMedidas->execute();
                    $resultMedidas = $stmtMedidas->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($resultMedidas)) {
                        foreach ($resultMedidas as $rowMedida) {
                            $selected = ($rowMedida['id_medida'] == $ingrediente['id_medida']) ? "selected" : "";
                            echo '<option value="' . $rowMedida['id_medida'] . '" ' . $selected . '>' . $rowMedida['descricao'] . '</option>';
                        }
                    } else {
                        echo '<option value="">Nenhuma medida disponível</option>';
                    }
                    echo '</select>';
                    echo '<button type="button" class="remove-ingredient" onclick="removeIngredient(this)">Remover</button>';
                    echo '</div>';
                }
            }
            ?>
        </div>
        <button type="button" id="add-ingredient">Adicionar Ingrediente</button>
        <a href="../1-Create/cadastrar-ingrediente.php">Cadastrar novo ingrediente</a>
        <label for="modo_preparo">Modo de Preparo:</label>
        <textarea name="modo_preparo" maxlength="255" required><?= $receita['modo_preparo'] ?></textarea><br>
        <label for="qtde_porcao">Qtd Porção:</label>
        <input type="number" name="qtde_porcao" value="<?= $receita['qtde_porcao'] ?>" required><br>
        <label for="ind_receita_inedita">Receita Inédita:</label>
        <input type="checkbox" name="ind_receita_inedita" value="1" <?= $receita['ind_receita_inedita'] ? "checked" : "" ?>> Sim <br>
        <label for="cozinheiro">Cozinheiro:</label>
        <select name="cozinheiro" required>
            <?php
            $sqlCozinheiros = "SELECT f.id_funcionario, f.nome FROM Funcionario f
                INNER JOIN Cargo c ON f.id_cargo = c.id_cargo
                WHERE c.descricao = 'cozinheiro' ORDER BY f.nome";
            $stmtCozinheiros = $conexao->prepare($sqlCozinheiros);
            $stmtCozinheiros->execute();
            $resultCozinheiros = $stmtCozinheiros->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($resultCozinheiros)) {
                foreach ($resultCozinheiros as $rowCozinheiro) {
                    $selected = ($rowCozinheiro['id_funcionario'] == $receita['id_cozinheiro']) ? "selected" : "";
                    echo '<option value="' . $rowCozinheiro['id_funcionario'] . '" ' . $selected . '>' . $rowCozinheiro['nome'] . '</option>';
                }
            } else {
                echo '<option value="">Nenhum cozinheiro disponível</option>';
            }
            ?>
        </select>
        <input type="submit" value="Atualizar Receita">
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