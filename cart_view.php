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

$cart_items = [];
$total_price = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    try {
        $db = new Database();
        $conn = $db->getConnection();

        $product_ids = array_keys($_SESSION['cart']);
        
        $placeholders = implode(',', array_fill(0, count($product_ids), '?'));

        $sql = "SELECT id, name, price FROM products WHERE id IN ($placeholders)";
        $stmt = $conn->prepare($sql);
        
        $stmt->execute($product_ids);
        
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $product) {
            $product_id = $product['id'];
            $quantity = $_SESSION['cart'][$product_id];
            $subtotal = $product['price'] * $quantity;
            $total_price += $subtotal;

            $cart_items[] = [
                'id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }

    } catch (PDOException $e) {
        die("Erro ao buscar detalhes do carrinho: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Carrinho de Compras</title>
    <style>
        body { font-family: sans-serif; }
        .container { width: 80%; margin: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; font-size: 1.2em; text-align: right; margin-top: 20px; }
        .actions { text-align: right; margin-top: 20px; }
        .remove-link { color: #cc0000; text-decoration: none; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Meu Carrinho</h1>
        <p><a href="products_list.php">&larr; Continuar Comprando</a></p>

        <?php if (!empty($cart_items)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço Unitário</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th>Ações</th> </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>R$ <?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
                            <td>
                                <a class="remove-link" href="cart_remove.php?id=<?php echo $item['id']; ?>">Remover</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total">
                Total do Pedido: R$ <?php echo number_format($total_price, 2, ',', '.'); ?>
            </div>

            <div class="actions">
                <form action="final.php" method="post">
                    <button type="submit" style="padding: 10px 20px; font-size: 1em; cursor: pointer;">Finalizar Pedido</button>
                </form>
            </div>

        <?php else: ?>
            <p>Seu carrinho está vazio.</p>
        <?php endif; ?>
    </div>

</body>
</html>