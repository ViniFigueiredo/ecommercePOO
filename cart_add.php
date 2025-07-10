<?php
session_start();

require_once("classes/Database.class.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    if (!isset($_POST['product_id']) || !isset($_POST['quantity']) || !is_numeric($_POST['quantity']) || $_POST['quantity'] <= 0) {
        die("Erro: Dados inválidos para adicionar ao carrinho.");
    }

    $productId = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    $db = new Database();
    $conn = $db->getConnection();

    try {
        $conn->beginTransaction();
        $stmt = $conn->prepare("SELECT stock FROM products WHERE id = :id FOR UPDATE");
        $stmt->bindParam(':id', $productId);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product && $product['stock'] >= $quantity) {
            $updateStmt = $conn->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :id");
            $updateStmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $updateStmt->bindParam(':id', $productId);
            $updateStmt->execute();
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $quantity;
            $conn->commit();

        } else {
            $conn->rollBack();
            die("Desculpe, não há estoque suficiente para este produto. <a href='products_list.php'>Voltar</a>");
        }

    } catch (PDOException $e) {
        $conn->rollBack();
        die("Ocorreu um erro ao processar sua solicitação: " . $e->getMessage());
    }
    header("Location: products_list.php");
    exit();
}
?>