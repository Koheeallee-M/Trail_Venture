<?php
// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to login page

if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit;
}

// Access user data from the session
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" />
    <link rel="stylesheet" href="homepage.css" />
    <title>TrailVenture page</title>
  </head>

  <body>
    <section class="container">
        
      <nav>
        <div class="nav__bar">
          <a href="#" class="nav__logo">
            <img src="logo.png" alt="logo" />
            <span>TrailVenture</span>
          </a>
          <div class="nav__menu__btn" id="menu-btn">
            <i class="ri-menu-3-line"></i>
          </div>
        </div>

        <ul class="nav__links" id="nav-links">
          <li><a href="#">Home</a></li>
          <li><a href="shop.php">Shop</a></li>
          <li><a href="#">Review</a></li>
          <li><a href="#">Contact</a></li>
          <li><a href="#">About</a></li>
        </ul>
      </nav>

      <div class="container__grid">
        <img src="bg-dots.png" alt="bg" class="bg__1" />
        <img src="bg-dots.png" alt="bg" class="bg__2" />
        <img src="bg-dots.png" alt="bg" class="bg__3" />
        <div class="container__image">
          <div></div>
        </div>
        
        <div class="container__content">
          <h2>Adventure Awaits</h2><br><br>
          
          <p>
            Embark on a journey of a lifetime as you answer the serene and
            captivating call of nature. Get ready to immerse yourself in the
            beauty of nature like never before.
          </p>

          <div  class="button_position"></div>
          <a href="book.php"><button  class="button">Book</button></a>
    </div>

          <div class="socials">
            <div>
              <a href="#"><i class="ri-instagram-line"></i></a>
              <a href="#"><i class="ri-facebook-fill"></i></a>
              <a href="#"><i class="ri-twitter-fill"></i></a>
              <a href="#"><i class="ri-whatsapp-line"></i></a>
            </div>
            <span>www.trailventure.com</span>
          </div>
        </div>
      </div>

      <div class="container__content2">
        <h2>Gear Up for Your Adventure</h2><br><br>
        
        <p>
            At TrailVenture, we provide high-quality hiking gear and accessories to enhance your outdoor experience. From durable backpacks to essential safety equipment, our carefully curated selection ensures you’re prepared for any adventure. Our knowledgeable team is ready to help you find the perfect gear. Explore our shop today and get ready to hit the trails!
        </p>

      </div>

      <div  class="button_position"></div>
        <a href="shop.php"><button  class="button2">Shop</button></a>
      </div>

    </section>

    

    
  </body>
</html>