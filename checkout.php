<?php
session_start();
require_once 'includes/dbconnect.php';

if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit;
}

$Card_Num = $Card_Holder = $Exp_Date = $CVC = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Get form data
    $Card_Num = trim($_POST['card-number']);
    $Card_Holder = trim($_POST['card-holder']);
    $Exp_Date = trim($_POST['expire-date']);
    $CVC = trim($_POST['cvc-code']);

    // Validation
    if (empty($Card_Num) || empty($Card_Holder) || empty($Exp_Date) || empty($CVC)) {
        $errors["empty_fields"] = "All fields are required.";
    }

    if (!preg_match('/^\d{13,16}$/', $Card_Num)) {  
        $errors["card-number"] = "Invalid card number format";
    } 

    if (!preg_match('/^[a-zA-Z\s\']+$/', $Card_Holder)) {  
        $errors["card-holder"] = "Invalid card holder name";
    }
    
    if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $Exp_Date, $matches)) {  
        $errors["expire-date"] = "Invalid expiry date format (MM/YY)";
    } else {
        $expMonth = (int)$matches[1];
        $expYear = (int)$matches[2];
        $currentYear = (int)date('y');
        $currentMonth = (int)date('m');

        if ($expYear < $currentYear || ($expYear == $currentYear && $expMonth < $currentMonth)) {
            $errors["expire-date"] = "Card has expired";
        }
    }

    if (!preg_match('/^\d{3,4}$/', $CVC)) {  
        $errors["cvc-code"] = "Invalid CVC code";
    }

    // Handle AJAX response
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode([
            'errors' => $errors,
            'success' => empty($errors)
        ]);
        exit;
    }
}

$errors = [];
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
     // Declare cartData globally
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
        <form id="checkoutForm" method="POST">
        <div class="input-form">
            <div class="section-1">
                <div class="items">
                    <label class="label">Card Number</label>
                    <input type="text" id="card-number" name="card-number" class="input" data-mask="0000 0000 0000 0000" placeholder="1234 1234 1234 1234"value = "<?php echo htmlspecialchars($Card_Num);?>" required>
                    <?php if (isset($errors['card-number'])): ?>
                    <p class="error"><?php echo $errors['card-number']; ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section-2">
                <div class="items">
                    <label class="label">Card Holder</label>
                    <input type="text" id="card-holder" name="card-holder" class="input" placeholder="Your Name"value = "<?php echo htmlspecialchars($Card_Holder);?>" required>
                    <?php if (isset($errors['card-holder'])): ?>
                    <p class="error"><?php echo $errors['card-holder']; ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section-3">
                <div class="items">
                    <label class="label">Expire Date</label>
                    <input type="text" id="expire-date" name="expire-date" class="input" data-mask="00 / 00" placeholder="MM / YY"value = "<?php echo htmlspecialchars($Exp_Date);?>" required>
                    <?php if (isset($errors['expire-date'])): ?>
                    <p class="error"><?php echo $errors['expire-date']; ?></p>
                    <?php endif; ?>
                    <?php if (isset($errors['expire-date'])): ?>
                    <p class="error"><?php echo $errors['expire-date']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="items">
                    <div class="cvc">
                        <label class="label">CVC Code</label>
                        <div class="tooltip">?
                            <div class="cvc-img"><img src="images/cart.png" alt=""></div>
                        </div>
                    </div>
                    <input type="text" id="cvc-code" name="cvc-code" class="input" data-mask="000" placeholder="000"value = "<?php echo htmlspecialchars($CVC);?>" required>
                    <?php if (isset($errors['cvc-code'])): ?>
                    <p class="error"><?php echo $errors['cvc-code']; ?></p>
                    <?php endif; ?>
                </div>
                <?php if (isset($errors['empty_fields'])): ?>
                    <p class="error"><?php echo $errors['empty_fields']; ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="btn" onclick="ProcessTransaction()" id="proceed-btn">Proceed</div>
    </form>
    </div>
</div>


<div class="popup" id="popup">
            <img src="images/check.png">
            <h2>Purchase Confirmed!</h2>
            <p>Your details have been successfully submitted.</p>
            <button type="button" onclick="closePopup()">OK</button>
            <a href="shop.php" class="continue-shopping">Continue Shopping</a>
</div>


<script>
  let popup = document.getElementById("popup");


  function openPopup(){
    popup.classList.add("open-popup");
  }

  function closePopup(){
    popup.classList.remove("open-popup");
  }

  function ProcessTransaction() {
    openPopup();
    
    fetch('save_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.text())
    .then(data => {
        console.log('Server says:', data);
    })
    .catch(error => {
        console.error('Error in transaction:', error);
    });
}
    
</script>

</body>
</html>