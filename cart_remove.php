<?php
session_start();
if (isset($_GET['id']) && isset($_SESSION['cart'])) {
    $product_id_to_remove = $_GET['id'];
    unset($_SESSION['cart'][$product_id_to_remove]);
}
header("Location: cart_view.php");
exit();

?>