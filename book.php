<?php
// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to login page

if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit;
}

require_once 'includes/dbconnect.php'; 

$sql = "SELECT b.*, 
        t.fname AS fname,
        t.lname AS lname,
        t.phonenum AS phonenum,
        t.email AS email
        FROM bookings b
        INNER JOIN `tour guides` t
        ON b.tour_guide = t.username;";

$result = mysqli_query($conn, $sql);
$resultCheck = mysqli_num_rows($result);

if (!$result){
  die("Database error: " . mysqli_error($conn));
}

$bookings = [];
if (mysqli_num_rows($result) > 0){
  while($row = mysqli_fetch_assoc($result)){
    $row['name'] = $row['fname'] . ' ' .$row['lname'];
    $bookings[] = $row;
  }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booklist</title>
    <link rel="stylesheet" href="css/book.css">
</head>

<body>
    
    <div class="container">

        <header>
          <div class="horizontal_bar">
            <div class="logo-img"><img src="images/logo.png" alt=""></div>
            <p class="Trail-position">Trailventure</p> 
            <div class="title">Booking</div> 
          </div> 
        </header>

        <div class="listProduct">
          <?php if(!empty($bookings)): ?>
            <?php foreach($bookings as $booking): ?>
            <div class="item">
            <img src="images/<?= htmlspecialchars($booking['image'])?>" alt="">
            <br><br>
            <h2>Venue: <?= htmlspecialchars($booking['venue'])?> <br><br></h2>

            <h4>Description <br></h4>
            <p>Date: <?= htmlspecialchars($booking['date'])?> <br></p>
            <p>Start: <?= htmlspecialchars($booking['start'])?> <br></p>
            <p>Length: <?= htmlspecialchars($booking['length'])?> <br><br></p>


            <h4>Tour Details<br></h4>
            <p>Name: <?= htmlspecialchars($booking['name'])?> <br></p>
            <p>Phone Number: <?= htmlspecialchars($booking['phonenum'])?><br></p>
            <p>Email: <?= htmlspecialchars($booking['email'])?> <br></p>

            
            <br><div class="price">Rs <?= htmlspecialchars($booking['price'])?></div>
            <a href="confirmbooking.php"><button class="addCart">Book</button></a>
          </div>
          <?php endforeach; ?>
            
      </div>
        
        <?php else: ?>
          <p>No bookings available.</p>
        <?php endif; ?>
        </div>
        
        <br><br><br><br><br><br><br><br><br><br><br><br>
         
    </div>

   
</body>
</html>