<?php 
session_start();

if(!isset($_SESSION['admin_id']) || (!($_SESSION['type'] === 2))) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
</head>
<body>
    <h1>You are an admin and have successfully logged in</h1>
</body>
</html>