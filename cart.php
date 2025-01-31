<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='shop.php'>Go back to shop</a></p>";
    exit();
}

if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']); // Clear the cart
    header("Location: cart.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['quantities'] as $item_id => $quantity) {
        if ($quantity == 0) {
            unset($_SESSION['cart'][$item_id]);
        } else {
            $_SESSION['cart'][$item_id]['quantity'] = $quantity;
        }
    }
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart</title>
</head>
<body>
    <h2>Your Cart</h2>
    <form action="cart.php" method="post">
        <table border="1">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php 
            $totalPrice = 0;
            foreach ($_SESSION['cart'] as $item_id => $item): 
                $total = $item['list_price'] * $item['quantity'];
                $totalPrice += $total;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                <td>$<?php echo number_format($item['list_price'], 2); ?></td>
                <td>
                    <input type="number" name="quantities[<?php echo $item_id; ?>]" value="<?php echo $item['quantity']; ?>" min="0">
                </td>
                <td>$<?php echo number_format($total, 2); ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3">Grand Total</td>
                <td>$<?php echo number_format($totalPrice, 2); ?></td>
            </tr>
        </table>
        <button type="submit">Update Cart</button>
    </form>

    <a href="checkout.php">Proceed to Checkout</a> | <a href="shop.php">Continue Shopping</a>
     <!-- Form to clear the cart -->
     <form action="cart.php" method="post">
        <button type="submit" name="clear_cart" style="background-color: red; color: white;">Clear Cart</button>
    </form>
</body>
</html>
