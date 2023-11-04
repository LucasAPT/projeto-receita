<?php
$servername = "localhost";
$username = "root"; // Altere para o seu nome de usuário MySQL
$password = ""; // Altere para a sua senha MySQL
$dbname = "acervoreceitas"; // Altere para o nome do seu banco de dados

try {
    $conexao = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Defina o modo de erro do PDO como exceção
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
