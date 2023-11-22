<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Consultar o banco de dados para verificar as credenciais
    $sql = "SELECT * FROM administrador WHERE usuario = '$usuario' AND senha = '$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Credenciais válidas, redirecionar para a página de administração
        header("Location: pagina_administracao.php");
    } else {
        // Credenciais inválidas, redirecionar de volta para o login
        header("Location: login.php");
    }
}
?>
