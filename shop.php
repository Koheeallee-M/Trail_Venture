<?php
session_start();

// Check if the user is logged in, otherwise redirect to login page

if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit;
}

require_once 'includes/dbconnect.php'; // Database connection file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/shop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            function saveToLocalStorage() {
            const cartItems = [];
            const cartItemsWithoutImages = [];
            let totalAmount = 0;
            let uniqueItemCount = 0; // Count of unique items, not quantities

            $(".listCart .items").each(function() {
                const id = $(this).data("id");
                const name = $(this).find(".name").text();
                const price = parseFloat($(this).data("price"));
                const quantity = parseInt($(this).find(".quantity span").text());
                const image = $(this).find("img").attr("src");
                cartItems.push({ id, name, price, quantity, image });
                cartItemsWithoutImages.push({ id, name, price, quantity });
                totalAmount += price * quantity;
                uniqueItemCount += 1; // Count each item only once
            });

            localStorage.setItem("cartItems", JSON.stringify(cartItems));
            localStorage.setItem("cartItemsWithoutImages", JSON.stringify(cartItemsWithoutImages));
            localStorage.setItem("cartTotal", totalAmount.toFixed(2));
            localStorage.setItem("cartCount", uniqueItemCount.toString());
            
            // Update the cart count display
            $(".icon-cart span").text(uniqueItemCount);
        }

            $(".addCart").click(function(){
                var item = $(this).closest(".item");
                var name = item.find("h2").text();
                var price = parseFloat(item.find(".price").text().replace("Rs", "").trim());    
                var image = item.find("img").attr("src");

                const newItem = `                
                    <div class="items" data-price="${price}" data-id="${item.data('id')}">
                        <div class="image">
                            <img src="${image}" alt="">
                        </div>
                        <div class="name">${name}</div>
                        <div class="totalPrice">${price}</div>
                        <div class="quantity">
                            <i class="fa-solid fa-trash"></i>
                            <span>1</span>
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </div>
                `;

                

            $(".listCart").append(newItem);

            $(this).text("Already added").attr('disabled', true).css("background-color", "grey");
            
            let totalAmount = parseFloat($(".total-amount").text().trim());
            totalAmount += parseFloat(price);
            $(".total-amount").text(totalAmount.toFixed(2));

            saveToLocalStorage();
            })

            $("div.icon-cart").click(function(){
                console.log("Cart icon clicked!"); //checked if the icon cart has been clicked when inspecting
                $("body").addClass("showCart");
            })

            $(".close").click(function(){
                $("body").removeClass("showCart");
            })


           $(".listCart").on("click", ".fa-plus", function () {
                let quantitySpan = $(this).siblings("span");
                let currentQty = parseInt(quantitySpan.text());
                quantitySpan.text(currentQty + 1);

                let trashOrMinusIcon = $(this).siblings(".fa-trash, .fa-minus");
                if (trashOrMinusIcon.hasClass("fa-trash")) {
                    trashOrMinusIcon.removeClass("fa-trash").addClass("fa-minus");// Replace trash icon with minus if it's still trash
                }

                let item = $(this).closest(".items");
                let price = item.data("price");
                let totalItemPrice = price * (currentQty + 1);
                item.find(".totalPrice").text(totalItemPrice.toFixed(2));// Update total price

                let totalAmount = parseFloat($(".total-amount").text());
                totalAmount += price;
                $(".total-amount").text(totalAmount.toFixed(2)); // Update total cart amount
                saveToLocalStorage()
            });


            // MINUS OR TRASH BUTTON OPERATION
            $(".listCart").on("click", ".fa-minus, .fa-trash", function () {
                let item = $(this).closest(".items");
                let price = item.data("price");
                let quantitySpan = $(this).siblings("span");
                let currentQty = parseInt(quantitySpan.text());

                if (currentQty < 1) return; // in case span becomes -1
                
                if (currentQty > 1) {
                    quantitySpan.text(currentQty - 1);

                    if (currentQty - 1 === 1) {
                        $(this).removeClass("fa-minus").addClass("fa-trash");
                    }

                    let newTotal = price * (currentQty - 1);
                    item.find(".totalPrice").text(newTotal.toFixed(2));// Update item's total price

                    let totalAmount = parseFloat($(".total-amount").text());
                    totalAmount -= price;
                    $(".total-amount").text(totalAmount.toFixed(2));// Update total cart amount
                    saveToLocalStorage()
                } else {
                    item.remove();

                    let productId = item.data("id");// Enable add-to-cart button again in product list
                    $(".listProduct .item[data-id='" + productId + "'] button")//Id is used to find which button to modify back
                        .text("Add To Cart")
                        .attr("disabled", false)
                        .css("background-color", "#353432");

                    let totalAmount = parseFloat($(".total-amount").text());
                    $(".total-amount").text((totalAmount - price).toFixed(2));
                    saveToLocalStorage()
                }        
                
            });

            $(".checkout").click(function() {
                const cartData = {
                    items: JSON.parse(localStorage.getItem("cartItemsWithoutImages") || "[]"),
                    total: localStorage.getItem("cartTotal") || "0.00",
                    count: localStorage.getItem("cartCount") || "0"
                };

                console.log(cartData);
                
                $.ajax({
                    url: 'api/cart',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(cartData),
                    success: function(response) {
                        // Check if the response status is success and a redirect URL exists
                        console.log(response);
                        if (response.status === "success" && response.redirect) {
                            // Redirect to the checkout.php page
                            window.location.href = response.redirect;
                        } else {
                            console.log('Redirect URL not found in response.');
                            alert('Cart saved, but no redirect URL provided.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Server error:', xhr.responseText);
                        alert('Error: checkout failed (server error)');
                    }
                });
            });

            loadCartFromLocalStorage();
        });

        function loadCartFromLocalStorage() {
        const cartItems = JSON.parse(localStorage.getItem("cartItems")) || [];

        if (cartItems.length === 0) {//check if cart is empty
        $(".total-amount").text("0.00");
        $(".icon-cart span").text("0");
        return;
    }

        let total = 0; // Recalculate total from scratch
        let uniqueItemCount = 0;

        $(".listCart").empty(); // Clear existing items

        cartItems.forEach(item => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal; // Add to running total

            const newItem = `
                <div class="items" data-price="${item.price}" data-id="${item.id}">
                    <div class="image">
                        <img src="${item.image}" alt="">
                    </div>
                    <div class="name">${item.name}</div>
                    <div class="totalPrice">${itemTotal.toFixed(2)}</div>
                    <div class="quantity">
                        <i class="${item.quantity > 1 ? 'fa-solid fa-minus' : 'fa-solid fa-trash'}"></i>
                        <span>${item.quantity}</span>
                        <i class="fa-solid fa-plus"></i>
                    </div>
                </div>
            `;
            $(".listCart").append(newItem);

            // Disable "Add to Cart" button for this item
            $(`.item[data-id="${item.id}"] button`)
                .text("Already added")
                .attr("disabled", true)
                .css("background-color", "grey");

                uniqueItemCount += 1; // Update item count
        });

        $(".total-amount").text(total.toFixed(2));
        $(".icon-cart span").text(uniqueItemCount);
    }


        //localStorage.clear();


    </script>
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
           <div class="item" data-id="1" data-price="690">
            <img src="images/WaterBottle.jpg" alt="">
            <h2>Salomon Outlife Bottle 550Ml <br><br></h2>
            <p>Product Description: <br><br> Stay hydrated with the Outlife Bottle. Its sturdy design and is it ideal for any adventure. Available in 3 colors to match our Outlife Bags. </p>
            <br><div class="price">Rs690</div>
            <button class="addCart">Add To Cart</button>
           </div>

           <div class="item" data-id="2" data-price="4200">
            <img src="images/Shoes.jpg" alt="">
            <h2>Salomon Shoes Alphacross <br><br></h2>
            <p>Product Description: <br><br>Ideal for trails and park runs, the ALPHACROSS 3 offers excellent grip and a secure fit for better energy transfer.</p>
            <br><div class="price">Rs4200</div>
            <button class="addCart">Add To Cart</button>
           </div>

           <div class="item" data-id="3" data-price="1500">
            <img src="images/Shirt.jpg" alt="">
            <h2>Salomon Tee Outlife <br><br></h2>
            <p>Product Description: <br><br> With Salomon new logo, Outlife Logo tee adapts to your day. The organic cotton is ultra-soft and breathable to help keep your temperature right. </p>
            <br><div class="price">Rs1500</div>
            <button class="addCart">Add To Cart</button>
           </div>

        </div>
        
        <br><br><br>

        <div class="listProduct">
            <div class="item" data-id="4" data-price="3400">
             <img src="images/pants.jpg" alt="">
             <h2>Salomon Pants Outrack</h2>
             <p>Product Description: <br><br> The men’s OUTRACK pants are perfect for any adventure, with a casual style, practical pockets, and durable, stretchy fabric for easy transitions.</p>
             <br><div class="price">Rs3400</div>
             <button class="addCart">Add To Cart</button>
            </div>
 
            <div class="item" data-id="5" data-price="920">
             <img src="images/Cap.jpg" alt="">
             <h2>Salomon Caps Xa</h2>
             <p>Product Description: <br><br>The lightweight and breathable XA cap dries fast and feels light and comfortable while running. Extra venting on top for the hottest conditions.</p>
             <br><div class="price">Rs920</div>
             <button class="addCart">Add To Cart</button>
            </div>
 
            <div class="item" data-id="6" data-price="4500">
             <img src="images/Ropes.jpg" alt="">
             <h2>Sveltus Battle Rope</h2>
             <p>Product Description: <br><br> The Sveltus battle rope offers intense training for both cardio and strength. Its protective ribbons on both sides prevent fraying, ensuring durability for workouts.</p>
             <br><div class="price">Rs4500</div>
             <button class="addCart">Add To Cart</button>
            </div>
 
         </div>   

         <br><br><br><br><br><br><br><br><br>
         
    </div>

    <div class="cartTab">
        <h1>Shopping Cart</h1>
        <div class="listCart">
            <!-- this is where all the item will be added-->
        </div>

        <div class="total-container">
            <p>Total: Rs<span class="total-amount">0.00</span></p>
        </div>
        <div class="btn">
            <button class="close">CLOSE</button>
            <button class="checkout">CHECKOUT</button>
        </div>
        
    </div>
</body>
</html>
