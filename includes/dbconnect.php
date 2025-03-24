<?php
$host = "localhost";
$dbname = "new_trailventure";
$dbusername = "root";
$dbpassword = "";
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}