<?php

//tentei fazer uma atualização no banco de dados ao finalizar pedido

session_start();
require_once("classes/Database.class.php");
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: cart_view.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

try {
    $conn->beginTransaction();
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $stmt = $conn->prepare("SELECT name, stock FROM products WHERE id = :id FOR UPDATE");
        $stmt->bindParam(':id', $productId);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$product || $product['stock'] < $quantity) {
            $conn->rollBack();
            $_SESSION['error_message'] = "Desculpe, o produto '" . htmlspecialchars($product['name']) . "' não tem estoque suficiente.";
            header("Location: cart_view.php");
            exit();
        }
        $updateStmt = $conn->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :id");
        $updateStmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $updateStmt->bindParam(':id', $productId);
        $updateStmt->execute();
    }
    $conn->commit();
    unset($_SESSION['cart']);
    echo "<h1>Compra Finalizada!</h1><p>Seu pedido foi processado com sucesso.</p>";


} catch (PDOException $e) {
    $conn->rollBack();
    die("Ocorreu um erro ao finalizar seu pedido: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido feito!</title>
    <a href="products_list.php">Ver mais produtos</a> | <a href="end_session.php">Sair</a>
</head>
<body>
</body>
</html>