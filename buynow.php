 <?php 
 session_start();
 require_once 'includes/dbconnect.php';

 if (!isset($_SESSION['userName'])) {
  header("Location: login.php");
  exit;
}

$Card_Num = $Card_Holder = $Exp_Date = $CVC = "";



if($_SERVER['REQUEST_METHOD'] === "POST"){
  $Card_Num = trim($_POST['Card_Num']);
  $Card_Holder = trim($_POST['Card_Holder']);
  $Exp_Date = trim($_POST['Exp_Date']);
  $CVC = trim($_POST['CVC']);


  $errors = [];
  if (empty($Card_Num) || empty($Card_Holder) || empty($Exp_Date) || empty($CVC)) {
  $errors["empty_fields"] = "All fields are required.";
  }

  if (!preg_match('/^\d{13,16}$/', $cardNumber)) {
    $errors[] = "Invalid card number format";
  } 

    if (!preg_match('/^[a-zA-Z\s\']+$/', $cardHolder)) {
      $errors[] = "Invalid card holder name";
      }
    
    
      if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expDate, $matches)) {
        $errors[] = "Invalid expiry date format (MM/YY)";
    } else {
        $expMonth = (int)$matches[1];
        $expYear = (int)$matches[2];
        $currentYear = (int)date('y');
        $currentMonth = (int)date('m');

        if ($expYear < $currentYear || 
           ($expYear == $currentYear && $expMonth < $currentMonth)) {
            $errors[] = "Card has expired";
        }
    }

    if (!preg_match('/^\d{3,4}$/', $cvc)) {
      $errors[] = "Invalid CVC code";
    }

  // If no errors, process payment
  if (empty($errors)) {
      // Process payment here (connect to payment gateway)
      header("Location: payment_success.php");
      exit();
  }

  }

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Checkout Form</title>
	<link rel="stylesheet" href="css/buynow.css">
</head>     
<body>

<div class="wrapper">
    
    <div class="horizontal_bar">
        <div class="logo-img"><img src="images/logo.png" alt=""></div>
        <p class="Trail-position">Trailventure</p>
    </div>

  <div class="container">
    <div class="title">Checkout Form</div>
    
    <div class="input-form">
      <div class="section-1">
        <div class="items">
          <label class="label">card number</label>
          <input type="text" class="input" name= "Card_Num" data-mask="0000 0000 0000 0000" placeholder="1234 1234 1234 1234" class="input_field" value = "<?php echo htmlspecialchars($Card_Num);?>" required>
        </div>
      </div>

      <div class="section-2">
        <div class="items">
          <label class="label">card holder</label>
          <input type="text" class="input" name= "Card_Holder" placeholder="Name Here" class="input_field" value = "<?php echo htmlspecialchars($Card_Holder);?>" required>
        </div>
      </div>

      <div class="section-3">
        <div class="items">
          <label class="label">Expire date</label>
          <input type="text" class="input" name= "Exp_Date" data-mask="00 / 00" placeholder="MM / YY" class="input_field" value = "<?php echo htmlspecialchars($Exp_Date);?>" required>
        </div>

        <div class="items">
          <div class="cvc">
            <label class="label">cvc code</label>
            <div class="tooltip">?
              <div class="cvc-img"><img src="images/cart.png" alt=""></div>
            </div>
          </div>
          <input type="text" class="input" name= "CVC" data-mask="000" placeholder="000" class="input_field" value = "<?php echo htmlspecialchars($CVC);?>" required>
        </div>
      </div>
    </div>
    
    <div class="btn" onclick="openPopup()">proceed</div>
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
</script>
</body>
</html>