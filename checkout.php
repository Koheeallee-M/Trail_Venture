<?php
session_start();
include 'includes/dbconnect.php';

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
    <title>Checkout Form</title>
    <link rel="stylesheet" href="css/checkout.css?v=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function() {

    // Fetch cart data only when View Orders button is clicked
    $('#view-orders-btn').click(function() {
        $.ajax({
            url: 'api/cart',
            method: 'GET',
            success: function(response) {
                console.log("AJAX Response:", response);

                if (response.status === 'success' && response.data && response.data.data) {
                    const cartData = response.data.data;
                    console.log("Cart ID:", cartData.cart_id);

                    // Clear previous items
                    $('#cart-items').empty();

                    let totalPrice = 0;
                    let totalQuantity = 0;

                    cartData.items.forEach(item => {
                        const itemTotal = item.price * item.quantity;
                        totalPrice += itemTotal;
                        totalQuantity += item.quantity;

                        $('#cart-items').append(`
                            <div class='cart-item'>
                                <div class='item-name'>${item.name}</div>
                                <div class='item-quantity'>Qty: ${item.quantity}</div>
                                <div class='item-price'>Rs ${item.price.toFixed(2)} each</div>
                                <div class='item-total'>Total: Rs ${itemTotal.toFixed(2)}</div>
                            </div>
                        `);
                    });
                    
                    $('#cart-items').append(`
                <div class="total-summary">
                    <div>Total Quantity: ${totalQuantity}</div>
                    <div>Total Price: Rs ${totalPrice.toFixed(2)}</div>
                </div>
            `);
                } else {
                    alert('Error: Invalid cart data received.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                console.log('Response Text:', xhr.responseText);
                alert('Error loading cart data.');
            }
        });
    });

});

</script>
</head>
<body>

<div class="wrapper">

    <div class="horizontal_bar">
        <div class="logo-img"><img src="images/logo.png" alt=""></div>
        <p class="Trail-position">Trailventure</p>
    </div>

    <div class="container">
        <div class="title">Checkout Form</div>

        <!-- Cart summary section -->
        <div class="cart-summary">
            <h2>Your Cart</h2>

            <button id="view-orders-btn">View Orders</button>
            <div class="items" id="cart-items">
                <!-- Cart items will be populated here after the AJAX call is made -->
            </div>
        </div>

        <!-- Checkout form -->
        <div class="input-form">
            <div class="section-1">
                <div class="items">
                    <label class="label">Card Number</label>
                    <input type="text" id="card-number" class="input" data-mask="0000 0000 0000 0000" placeholder="1234 1234 1234 1234">
                </div>
            </div>

            <div class="section-2">
                <div class="items">
                    <label class="label">Card Holder</label>
                    <input type="text" id="card-holder" class="input" placeholder="Your Name">
                </div>
            </div>

            <div class="section-3">
                <div class="items">
                    <label class="label">Expire Date</label>
                    <input type="text" id="expire-date" class="input" data-mask="00 / 00" placeholder="MM / YY">
                </div>

                <div class="items">
                    <div class="cvc">
                        <label class="label">CVC Code</label>
                        <div class="tooltip">?
                            <div class="cvc-img"><img src="images/cart.png" alt=""></div>
                        </div>
                    </div>
                    <input type="text" id="cvc-code" class="input" data-mask="000" placeholder="000">
                </div>
            </div>
        </div>

        <div class="btn" id="proceed-btn">Proceed</div>

    </div>
</div>

</body>
</html>


