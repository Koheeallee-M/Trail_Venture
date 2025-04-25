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
        <a href="createbooking.php" class="btn btn-success">Add New Booking</a>
    </div>

    <?php
    $fetchbookings = "SELECT * FROM `bookings`";
    $result = $conn->query($fetchbookings);
    if($result->num_rows > 0) {
        echo "<table>";
        echo "<thead><tr>" .
             "<th>Venue</th>" .
             "<th>Date</th>" .
             "<th>Start</th>" .
             "<th>Length</th>" .
             "<th>Price</th>" .
             "<th>Tour Guide</th>" .
             "<th>Image</th>" .
             "</tr></thead><tbody>";
        while($row = $result->fetch_assoc()) {
            $imgPath = '../images/' . htmlspecialchars($row['image']);
            echo "<tr>";
            echo "<td data-label=\"Guide ID\">" . htmlspecialchars($row['venue']) . "</td>";
            echo "<td data-label=\"Username\">" . htmlspecialchars($row['date']) . "</td>";
            echo "<td data-label=\"First Name\">" . htmlspecialchars($row['start']) . "</td>";
            echo "<td data-label=\"Last Name\">" . htmlspecialchars($row['length']) . "</td>";
            echo "<td data-label=\"Email\">" . htmlspecialchars($row['price']) . "</td>";
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