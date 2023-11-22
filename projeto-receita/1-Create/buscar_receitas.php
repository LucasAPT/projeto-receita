<?php
include "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_cozinheiro'])) {
    $idCozinheiroSelecionado = $_POST['id_cozinheiro'];

    // Consulta SQL para buscar as receitas do cozinheiro selecionado
    try {
        $stmt = $conexao->prepare("SELECT id_receita, nome FROM Receita WHERE id_cozinheiro = ?");
        $stmt->bindParam(1, $idCozinheiroSelecionado, PDO::PARAM_INT);
        $stmt->execute();
        $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Formate as opções como HTML
        $options = '<option value="">Selecione a Receita</option>';
        foreach ($receitas as $receita) {
            $options .= '<option value="' . $receita['id_receita'] . '">' . $receita['nome'] . '</option>';
        }

        echo $options; // Retorne as opções como resposta
    } catch (PDOException $e) {
        // Lide com erros de consulta, se houver
        echo '<option value="">Erro ao buscar receitas</option>';
    }
}
?>
