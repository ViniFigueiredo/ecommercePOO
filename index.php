<?php

session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: products_list.php");
    exit(); //Começa já na tela de produto se o login tiver sido feito
} else {
    header("Location: login.php");
    exit();
}
?>