<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="process_login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <label for="password">Senha:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Entrar</button>
    </form>
    <p>Não tem uma conta? <a href="register.php">Cadastre-se</a></p>
</body>
</html>