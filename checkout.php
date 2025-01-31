<?php
session_start();
include 'includes/dbconnect.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='shop.php'>Go back to shop</a></p>";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cust_id = $_SESSION['cust_id'];
    $totalAmount = 0;

    // Prepare statements for purchases and purchase details
    $purchaseStmt = $conn->prepare("INSERT INTO purchases (cust_id, date, total) VALUES (?, NOW(), ?)");
    $result = $conn->query("SELECT MAX(pur_id) AS highest_pur_id FROM purchases");
    $row = $result->fetch_assoc();
    $nextpur_id = $row['highest_pur_id'] + 1; // Increment for new pur_id
    $purchaseDetailStmt = $conn->prepare("INSERT INTO `purchases details` (pur_id, cust_id, item_id, item_name, price_paid, qty, discount) VALUES (?, ?, ?, ?, ?, ?, ?)");

    foreach ($_SESSION['cart'] as $item_id => $item) {
        $qtyBought = $item['quantity'];
        $list_price = $item['list_price'];
        $item_name = $item['item_name'];
        $discount = 0; // Discount is set to 0 for now

        // Calculate total amount
        $totalAmount += $list_price * $qtyBought;

        // Insert into purchases_details table
        $purchaseDetailStmt->bind_param("iiisdis", $nextpur_id, $cust_id, $item_id, $item_name, $list_price, $qtyBought, $discount);
        if (!$purchaseDetailStmt->execute()) {
            echo "Error inserting purchase details: " . $purchaseDetailStmt->error;
        }

        // Decrement qtyInStock in the item table
        $updateStockStmt = $conn->prepare("UPDATE item SET qtyInStock = qtyInStock - ? WHERE item_id = ?");
        if (!$updateStockStmt) {
            echo "Failed to prepare the update statement: " . $conn->error;
            exit();
        }
        $updateStockStmt->bind_param("ii", $qtyBought, $item_id);
        
        // Execute stock update
        if (!$updateStockStmt->execute()) {
            echo "Error updating stock: " . $updateStockStmt->error;
        }
    }

    // Insert into purchases table
    $purchaseStmt->bind_param("id", $cust_id, $totalAmount);
    $purchaseStmt->execute();
    

    // Clear the cart after successful purchase
    unset($_SESSION['cart']);

    echo "<h2>Thank you for your purchase!</h2>";
    echo "<p>Your total amount is: $" . number_format($totalAmount, 2) . "</p>";
    echo "<p><a href='shop.php'>Continue Shopping</a></p>";
}

// Fetch cart details for display
//Placeholder stylesheet
$totalPrice = 0;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>
    <h2>Checkout</h2>
    <table border="1">
        <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
        <?php foreach ($_SESSION['cart'] as $item_id => $item): 
            $total = $item['list_price'] * $item['quantity'];
            $totalPrice += $total;
        ?>
        <tr>
            <td><?php echo htmlspecialchars($item['item_name']); ?></td>
            <td>$<?php echo number_format($item['list_price'], 2); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>$<?php echo number_format($total, 2); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3">Grand Total</td>
            <td>$<?php echo number_format($totalPrice, 2); ?></td>
        </tr>
    </table>
    <form method="post">
        <button type="submit">Confirm Purchase</button>
    </form>
    <a href="cart.php">Go Back to Cart</a>
</body>
</html>
