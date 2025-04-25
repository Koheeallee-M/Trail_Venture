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
    <title>Manage Tour Guides</title>
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
        <a href="tourguidesignup.php" class="btn btn-success">Add New Tour Guide</a>
    </div>

    <?php
    $fetchguides = "SELECT * FROM `tour guides`";
    $result = $conn->query($fetchguides);
    if($result->num_rows > 0) {
        echo "<table>";
        echo "<thead><tr>" .
             "<th>Guide ID</th>" .
             "<th>Username</th>" .
             "<th>First Name</th>" .
             "<th>Last Name</th>" .
             "<th>Email</th>" .
             "<th>Phone</th>" .
             "<th>Actions</th>" .
             "</tr></thead><tbody>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td data-label=\"Guide ID\">" . htmlspecialchars($row['guide_id']) . "</td>";
            echo "<td data-label=\"Username\">" . htmlspecialchars($row['username']) . "</td>";
            echo "<td data-label=\"First Name\">" . htmlspecialchars($row['fname']) . "</td>";
            echo "<td data-label=\"Last Name\">" . htmlspecialchars($row['lname']) . "</td>";
            echo "<td data-label=\"Email\">" . htmlspecialchars($row['email']) . "</td>";
            echo "<td data-label=\"Phone\">" . htmlspecialchars($row['phonenum']) . "</td>";
            echo "<td data-label=\"Actions\">";
            echo "<a href='deleteguide.php?id=" . intval($row['guide_id']) . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete this guide?');\">Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No tour guides found.</p>";
    }
    ?>

    <div class="signin">
        <span>Return to landing page? <a href="adminwelcomepage.php">Return to landing</a></span>
    </div>
</body>
</html>