<!DOCTYPE html>
<html>
<head>
    <title>Login de Administrador</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h2>Login de Administrador</h2>
    <form action="validar_login.php" method="post">
        <label for="usuario">Usuário:</label>
        <input type="text" id="usuario" name="usuario" required><br><br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
