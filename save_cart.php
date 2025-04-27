<?php

require_once 'includes/dbconnect.php';
session_start();
// Read incoming JSON data
$cartdata = json_decode(file_get_contents('carts/csjson'), true);

//INSERT INTO purchases TABLE
$stmtpur = $conn->prepare("INSERT INTO `purchases` (cust_id, `date`, total) VALUES (?, ?, ?)");
$cust_id = $_SESSION['cust_id'];
$date = date("Y-m-d H:i:s");
$total = $cartdata['data']['total'];

$stmtpur->bind_param("isd", $cust_id, $date, $total);
$stmtpur->execute();

$stmtpur->close();
$pur_id = $conn->insert_id;

//INSERT INTO purchase_details TABLE
$stmtdet = $conn->prepare("INSERT INTO `purchases details` (pur_id, cust_id, item_id, item_name, price_paid, qty) VALUES (?, ?, ?, ?, ?, ?)");
foreach ($cartdata['data']['items'] as $item) {
    $item_id = $item['id'];
    $item_name = $item['name'];
    $price_paid = $item['price'];
    $qty = $item['quantity'];

    $stmtdet->bind_param("iissdi", $pur_id, $cust_id, $item_id, $item_name, $price_paid, $qty);
    $stmtdet->execute();
}
$stmtdet->close();
$conn->close();

