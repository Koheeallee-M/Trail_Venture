<?php
session_start();


if (!isset($_SESSION['admin_id']) || $_SESSION['type'] !== 2) {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/dbconnect.php';

$venue = filter_input(INPUT_GET, 'venue', FILTER_SANITIZE_STRING);
if (!$venue) {
    header("Location: managebookings.php");
    exit;
}

// Prepare and execute deletion
$stmt = $conn->prepare("DELETE FROM `bookings` WHERE venue = ?");
$stmt->bind_param('s', $venue);
$stmt->execute();
$stmt->close();

// Redirect back with a success message
header("Location: managebookings.php?message=Booking+deleted");
exit;
?>