<?php 
session_start();

if(!isset($_SESSION['admin_id']) || (!($_SESSION['type'] === 2))) {
    header("Location: ../login.php");
    exit;
}
require_once '../includes/dbconnect.php';
?>

<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tour Guides</title>
    <link rel="stylesheet" href="../css/mystyle.css">
    </head>

    <body>
    <?php
    $fetchguides= "SELECT * FROM `tour guides`";
    $result=$conn->query($fetchguides);
    if($result->num_rows > 0){
        echo "<table><tr><th>Guide ID</th><th>Username</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th><th>Action</th></tr>";
        while($row= $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>" . $row['guide_id'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['fname'] . "</td>";
            echo "<td>" . $row['lname'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['phonenum'] . "</td>";
            echo "<td><a href='deleteguide.php?id=" . $row['guide_id'] . "'>Delete</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No tour guides found.";
    }
    ?>
    </body>




</html>