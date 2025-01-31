<?php
// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to login page

if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit;
}

require_once 'includes/dbconnect.php'; // Database connection file


// Fetch all products from the database

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booklist</title>
    <link rel="stylesheet" href="book.css">
</head>

<body>
    
    <div class="container">

        <header>
          <div class="horizontal_bar">
            <div class="logo-img"><img src="logo.png" alt=""></div>
            <p class="Trail-position">Trailventure</p> 
            <div class="title">Booking</div> 
          </div> 
        </header>

        <div class="listProduct">
           <div class="item">
            <img src="7cascades.jpeg" alt="">
            <br><br>
            <h2>Venue: Seven Cascades <br><br></h2>

            <h4>Description <br></h4>
            <p>Date: 10/11/2024 <br></p>
            <p>Start: 10am <br></p>
            <p>Length: 2.1km <br><br></p>


            <h4>Tour Details<br></h4>
            <p>Name: Kai Havertz <br></p>
            <p>Phone Number: +230 58325136 <br></p>
            <p>Email: Kai7@gmail.com <br></p>

            
            <br><div class="price">Rs300</div>
            <button class="addCart">Book</button>
           </div>

           <div class="item">
            <img src="LeMorne.jpeg" alt="">
            <br><br>
            <h2>Venue: Le Morne Brabant <br><br></h2>

            <h4>Description <br></h4>
            <p>Date: 15/11/2024 <br></p>
            <p>Start: 11am <br></p>
            <p>Length: 6.6km <br><br></p>


            <h4>Tour Details<br></h4>
            <p>Name: William Saliba <br></p>
            <p>Phone Number: +230 58221205 <br></p>
            <p>Email: Wsali@gmail.com <br></p>

            
            <br><div class="price">Rs500</div>
            <button class="addCart">Book</button>
           </div>

           <div class="item">
            <img src="LePouce.jpeg" alt="">
            <br><br>
            <h2>Venue: Le Pouce <br><br></h2>

            <h4>Description <br></h4>
            <p>Date: 17/11/2024 <br></p>
            <p>Start: 11am <br></p>
            <p>Length: 4.3km <br><br></p>


            <h4>Tour Details<br></h4>
            <p>Name: Bukayo Saka<br></p>
            <p>Phone Number: +230 59981291 <br></p>
            <p>Email: Bsaka@gmail.com <br></p>

            
            <br><div class="price">Rs400</div>
            <a href="confirmbooking.php"><button class="addCart">Book</button></a>
           </div>

        </div>
        
        <br><br><br><br><br><br><br><br><br><br><br><br>
         
    </div>

   
</body>
</html>