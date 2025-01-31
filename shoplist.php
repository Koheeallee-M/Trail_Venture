<?php
//start the session
session_start();

//checks the login
if (!isset($_SESSION['userName'])) {
    header("Location: index.php");
    exit;
}

//connects to database
require_once 'includes/dbconnect.php';

$sql = "SELECT item_id, item_name, list_price, description FROM item";
$result = $conn->query($sql);

// Initialize the products array
$items = [];

// If there are results, fetch the data into the array
if ($result->num_rows > 0) {
    for ($i = 0; $i < $result->num_rows; $i++) {
        $row = $result->fetch_assoc();
        $items[$i] = [
            "item_id" => $row["item_id"],
            "item_name" => $row["item_name"],
            "list_price" => $row["list_price"],
            "description" => $row["description"]
        ];
    }
} else {
    echo "No products found!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/shoplist.css">
</head>
<body class="">
    
    <div class="container">

        <header>
          <div class="horizontal_bar"> 
            <div class="title">PRODUCT LIST</div> 
            <div class="icon-cart">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 15a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0h8m-8 0-1-4m9 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-9-4h10l2-7H3m2 7L3 4m0 0-.792-3H1"/>
                </svg>
                <span>0</span>
            </div>
          </div> 
        </header>

        <div class="listProduct">
           <div class="item">
            <img src="images/WaterBottle.jpg" alt="">
            <h2><?php echo htmlspecialchars($items[0]['item_name']) ?><br><br></h2>
            <p>Product Description: <br><br><?php echo htmlspecialchars($items[0]['description']) ?></p>
            <br><div class="price">Rs <?php echo htmlspecialchars($items[0]['list_price']) ?></div>
            <button class="addCart">Add To Cart</button>
           </div>

           <div class="item">
            <img src="images/Shoes.jpg" alt="">
            <h2><?php echo htmlspecialchars($items[1]['item_name']) ?><br><br></h2>
            <p>Product Description: <br><br><?php echo htmlspecialchars($items[1]['description']) ?></p>
            <br><div class="price">Rs <?php echo htmlspecialchars($items[1]['list_price']) ?></div>
            <button class="addCart">Add To Cart</button>
           </div>

           <div class="item">
            <img src="Shirt.jpg" alt="">
            <h2><?php echo htmlspecialchars($items[2]['item_name']) ?><br><br></h2>
            <p>Product Description: <br><br><?php echo htmlspecialchars($items[2]['description']) ?></p>
            <br><div class="price">Rs <?php echo htmlspecialchars($items[2]['list_price']) ?></div>
            <button class="addCart">Add To Cart</button>
           </div>

        </div>
        
        <br><br><br>

        <div class="listProduct">
            <div class="item">
             <img src="pants.jpg" alt="">
             <h2><?php echo htmlspecialchars($items[3]['item_name']) ?><br><br></h2>
             <p>Product Description: <br><br><?php echo htmlspecialchars($items[3]['description']) ?></p>
             <br><div class="price">Rs <?php echo htmlspecialchars($items[3]['list_price']) ?></div>
             <button class="addCart">Add To Cart</button>
            </div>
 
            <div class="item">
             <img src="Cap.jpg" alt="">
             <h2><?php echo htmlspecialchars($items[4]['item_name']) ?><br><br></h2>
             <p>Product Description: <br><br><?php echo htmlspecialchars($items[4]['description']) ?></p>
             <br><div class="price">Rs <?php echo htmlspecialchars($items[4]['list_price']) ?></div>
             <button class="addCart">Add To Cart</button>
            </div>
 
            <div class="item">
             <img src="Ropes.jpg" alt="">
             <h2><?php echo htmlspecialchars($items[5]['item_name']) ?><br><br></h2>
             <p>Product Description: <br><br><?php echo htmlspecialchars($items[5]['description']) ?></p>
             <br><div class="price">Rs <?php echo htmlspecialchars($items[5]['list_price']) ?></div>
             <button class="addCart">Add To Cart</button>
            </div>
 
         </div>   

         <br><br><br><br><br><br><br><br><br>
         
    </div>

    <div class="cartTab">
        <h1>Shopping Cart</h1>
        <div class="listCart">
            <div class="item">
                <div class="image">
                    <img src="Shoes.jpg" alt="">
                </div>
                <div class="name">
                    Salomon Shoes Alphacross
                </div>
                <div class="totalPrice">
                    Rs4200
                </div>

                <div class="quantity">
                    <span class="minus">-</span>
                    <span>1</span>
                    <span class="plus">+</span>
                </div>
            </div>
        </div>
        
        <div class="btn">
            <button class="close">CLOSE</button>
            <a href="checkout.html"><button class="checkOut">Check Out</button></a>
        </div>
    </div>

    <script src="shoplist.js"></script>
</body>
</html>