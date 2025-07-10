<?php
require_once("classes/Database.class.php");
require_once("classes/User.class.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $user = new User($name, $email);
    $user->setPassword($password);

    if ($user->save()) {
        echo "Usuário cadastrado com sucesso! <a href='login.php'>Faça o login</a>";
    } else {
        echo "Erro ao cadastrar o usuário. O e-mail já pode existir.";
    }
}

?>