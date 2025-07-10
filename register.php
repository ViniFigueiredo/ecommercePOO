<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <h2>Cadastro de Novo Usuário</h2>
    <form action="process_register.php" method="post">
        <label for="name">Nome:</label>
        <input type="text" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <label for="password">Senha:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>