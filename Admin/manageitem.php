<?php
session_start();

if(!isset($_SESSION['admin_id']) || $_SESSION['type'] !== 2) {
    header("Location: ../login.php");
    exit;
}
require_once '../includes/dbconnect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <!-- Include custom stylesheet -->
    <link rel="stylesheet" href="../css/manageguides.css">
</head>
<body>
    <!-- Display success message if provided -->
    <?php if(isset($_GET['message'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['message']) ?>
        </div>
    <?php endif; ?>

    <div class="actions">
        <a href="createitem.php" class="btn btn-success">Add New Booking</a>
    </div>

    <?php
    $fetchitem = "SELECT * FROM `item`";
    $result = $conn->query($fetchitem);
    if($result->num_rows > 0) {
        echo "<table>";
        echo "<thead><tr>" .
             "<th>Item ID</th>" .
             "<th>Item Name</th>" .
             "<th>Price</th>" .
             "<th>Description</th>" .
             "<th>QtyInStock</th>" .
             "<th>Image</th>" .
             "</tr></thead><tbody>";
        while($row = $result->fetch_assoc()) {
            $imgPath = '../images/' . htmlspecialchars($row['image']);
            echo "<tr>";
            echo "<td data-label=\"Item ID\">" . htmlspecialchars($row['item_id']) . "</td>";
            echo "<td data-label=\"Item Name\">" . htmlspecialchars($row['item_name']) . "</td>";
            echo "<td data-label=\"Price\">" . htmlspecialchars($row['list_price']) . "</td>";
            echo "<td data-label=\"Description\">" . htmlspecialchars($row['description']) . "</td>";
            echo "<td data-label=\"QtyInStock\">" . htmlspecialchars($row['qtyInStock']) . "</td>";
            echo "<td data-label=\"Phone\">" . htmlspecialchars($row['tour_guide']) . "</td>";
            echo "<td data-label=\"Image\"><img src='" . $imgPath . "' alt='Booking Image' style='max-width:100px; height:auto;'></td>";
            echo "<td data-label=\"Actions\">";
            echo "<a href='editbooking.php?venue=" . urlencode($row['venue']) . "' class='btn btn-primary'>Edit</a>";
            echo "<a href='deletebooking.php?venue=" . urlencode($row['venue']) . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete this guide?');\">Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No bookings found.</p>";
    }
    ?>

    <div class="signin">
        <span>Return to landing page? <a href="adminwelcomepage.php">Return to landing</a></span>
    </div>
</body>
</html>