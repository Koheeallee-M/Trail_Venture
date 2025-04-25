<?php
session_start();

if(!isset($_SESSION['admin_id']) || $_SESSION['type'] !== 2) {
    header("Location: ../login.php");
    exit;
}
require_once '../includes/dbconnect.php';

// Fetch old username
$oldUsername = null;
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['guide_id'];
    $fetchStmt = $conn->prepare("SELECT username FROM `tour guides` WHERE guide_id = ?");
    $fetchStmt->bind_param('i', $id);
    $fetchStmt->execute();
    $fetchStmt->bind_result($oldUsername);
    $fetchStmt->fetch();
    $fetchStmt->close();

    // Update tour guides table
    $username = $_POST['username'];
    $fname    = $_POST['fname'];
    $lname    = $_POST['lname'];
    $email    = $_POST['email'];
    $phone    = $_POST['phonenum'];

    $stmt = $conn->prepare("UPDATE `tour guides` SET username = ?, fname = ?, lname = ?, email = ?, phonenum = ? WHERE guide_id = ?");
    $stmt->bind_param('sssssi', $username, $fname, $lname, $email, $phone, $id);
    $stmt->execute();
    $stmt->close();

    // Update login table if username changed
    if($oldUsername && $oldUsername !== $username) {
        $loginStmt = $conn->prepare("UPDATE `login` SET username = ? WHERE username = ?");
        $loginStmt->bind_param('ss', $username, $oldUsername);
        $loginStmt->execute();
        $loginStmt->close();
    }

    header("Location: manageguides.php?message=Guide+and+login+updated");
    exit;
}

$id = $_GET['id'] ?? null;
if(!$id) {
    header("Location: manageguides.php");
    exit;
}
$selectStmt = $conn->prepare("SELECT * FROM `tour guides` WHERE guide_id = ?");
$selectStmt->bind_param('i', $id);
$selectStmt->execute();
$result = $selectStmt->get_result();
if($result->num_rows === 0) {
    header("Location: manageguides.php");
    exit;
}
$row = $result->fetch_assoc();
$selectStmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tour Guide</title>
    <link rel="stylesheet" href="../css/mystyle.css">
</head>
<body>
    <h2>Edit Tour Guide</h2>
    <form action="editguide.php" method="post">
        <input type="hidden" name="guide_id" value="<?= htmlspecialchars($row['guide_id']) ?>">
        <label>Username:<input type="text" name="username" value="<?= htmlspecialchars($row['username']) ?>" required></label><br>
        <label>First Name:<input type="text" name="fname" value="<?= htmlspecialchars($row['fname']) ?>" required></label><br>
        <label>Last Name:<input type="text" name="lname" value="<?= htmlspecialchars($row['lname']) ?>" required></label><br>
        <label>Email:<input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required></label><br>
        <label>Phone:<input type="text" name="phonenum" value="<?= htmlspecialchars($row['phonenum']) ?>" required></label><br>
        <button type="submit" class="btn btn-primary">Update Guide</button>
        <a href="manageguides.php" class="btn">Cancel</a>
    </form>
</body>
</html>