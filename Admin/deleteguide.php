<?php
session_start();

if(!isset($_SESSION['admin_id']) || $_SESSION['type'] !== 2) {
    header("Location: ../login.php");
    exit;
}
require_once '../includes/dbconnect.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if(!$id) {
    header("Location: manageguides.php");
    exit;
}

// Fetch username to delete from login
$fetchStmt = $conn->prepare("SELECT username FROM `tour guides` WHERE guide_id = ?");
$fetchStmt->bind_param('i', $id);
$fetchStmt->execute();
$fetchStmt->bind_result($username);
$fetchStmt->fetch();
$fetchStmt->close();

// Delete from tour guides
$delStmt = $conn->prepare("DELETE FROM `tour guides` WHERE guide_id = ?");
$delStmt->bind_param('i', $id);
$delStmt->execute();
$delStmt->close();

// Delete from login table
if($username) {
    $loginDel = $conn->prepare("DELETE FROM `login` WHERE username = ?");
    $loginDel->bind_param('s', $username);
    $loginDel->execute();
    $loginDel->close();
}

header("Location: manageguides.php?message=Guide+deleted+from+both+tables");
exit;
?>