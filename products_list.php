<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once("classes/Database.class.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); 
}
try {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare("SELECT id, name, price, stock FROM products WHERE stock > 0");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao buscar produtos: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nossos Produtos</title>
    <style>
        body { font-family: sans-serif; }
        .product-container { display: flex; flex-wrap: wrap; gap: 20px; }
        .product-card { border: 1px solid #ccc; border-radius: 5px; padding: 15px; width: 200px; }
        .header { margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Nossos Produtos</h1>
        <p>
            Olá, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
            <a href="cart_view.php">Ver Carrinho</a> | <a href="end_session.php">Sair</a>
        </p>
    </div>

    <div class="product-container">
        <?php if ($products): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p>Preço: R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></p>
                    <p>Em estoque: <?php echo $product['stock']; ?></p>
                    
                    <form action="cart_add.php" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" style="width: 50px;">
                        <button type="submit">Adicionar ao Carrinho</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum produto encontrado no momento.</p>
        <?php endif; ?>
    </div>

</body>
</html>