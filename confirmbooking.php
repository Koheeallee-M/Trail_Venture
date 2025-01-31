<?php
// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to login page

if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link rel="stylesheet" href="confirmbooking.css">
</head>
<body>
    
<div class="container">

    <div class="horizontal_bar">
        <div class="logo-img"><img src="logo.png" alt=""></div>
        <p class="Trail-position">Trailventure</p>
        <div class="title">Booking</div>
    </div>

    <div class="checkoutLayout">


        <div class="right">
            <h1>Confirm Booking</h1>

            <br><br>

            <div class="form">
                <div class="group">
                    <label for="name">First Name</label>
                    <input type="text" name="fname" id="fname" required>
                </div>

                <div class="group">
                    <label for="name">Last Name</label>
                    <input type="text" name="lname" id="lname" required>
                </div>
    
                <div class="group">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" id="phone" required>
                </div>
    
                <div class="group">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" required>
                </div>
    
                <div class="group">
                    <label for="quantity">Quantity</label>
                    <input type="text" name="quantity" id="quantity" placeholder="1" required>
                </div>
    
                
            </div>

            <div class="return">
                <div class="row">
                    <div>Total Price</div>
                    <div class="totalPrice">Rs400</div>
                </div>
                <br>
                <p>Note: All payments are to be made on-site.</p>
            </div>
            <a href="#"><button class="buttonCheckout">Book Now</button></a>
            </div>
    </div>
</div>

</body>
</html>