<?php
session_start();

// Check if the user is logged in, otherwise redirect to login page

if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit;
}

require_once 'includes/dbconnect.php'; // Database connection file


// Fetch all products from the database
$sql = "SELECT item_id, item_name, list_price, description FROM item";
$result = $conn->query($sql);

// Handle adding to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // Initialize the cart if it's not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        // Fetch product details for the first time
        $item = $conn->query("SELECT * FROM item WHERE item_id = $item_id")->fetch_assoc();
        $_SESSION['cart'][$item_id] = [
            'item_name' => $item['item_name'],
            'list_price' => $item['list_price'],
            'description' =>$item['description'],
            'qtyInStock' =>$item['qtyInStock'],
            'quantity' => $quantity,
        ];
    }

    // Redirect back to the shop page after adding the item
    header("Location: shop.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop</title>
</head>
<body>
    <h2>Shop Items</h2>

    <div>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div>
                <h3><?php echo htmlspecialchars($row['item_name']); ?></h3>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <p>Price: $<?php echo number_format($row['list_price'], 2); ?></p>

                <form action="shop.php" method="post">
                    <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" value="0" min="0" required>
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
            <hr>
        <?php endwhile; ?>
    </div>

    <a href="cart.php">View Cart</a>
</body>
</html>
