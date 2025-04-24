<?php 
session_start();

if(!isset($_SESSION['admin_id']) || (!($_SESSION['type'] === 2))) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/adminwelcomepage.css">
</head>

<body>

    <div class ="container">

        <div class="horizontal_bar">
            <p class="Trail-position">Trailventure</p>
            <p class="position">Come join us for a fun adventure!</p>
        </div>

        <div class="image">
            <img src="../images/Logo.jpeg">
        </div>

        <div class="Welcome">
            <p>Welcome <span id="admin-name">xxx</span></p>
        </div>

        <div class="button-container">
            <button class="admin_button">Manage Item</button>
            <button class="admin_button">Manage Booking</button>
        </div>
        
        
    </div>

</html>
