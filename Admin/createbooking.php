<?php
session_start();

// Only admin type 2 can access
if(!isset($_SESSION['admin_id']) || $_SESSION['type'] !== 2) {
    header("Location: ../login.php");
    exit;
}
require_once '../includes/dbconnect.php';

// Initialize variables
$errors = [];
$venue = '';
date_default_timezone_set('UTC');
$date = '';
$start = '';
$length = '';
$price = '';
$tour_guide = '';
$image = '';

// Fetch tour guides for dropdown
$guides = [];
$guideQuery = "SELECT username FROM `tour guides`";
if($res = $conn->query($guideQuery)) {
    while($r = $res->fetch_assoc()) {
        $guides[] = $r['username'];
    }
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $venue      = trim($_POST['venue'] ?? '');
    $date       = trim($_POST['date'] ?? '');
    $start      = trim($_POST['start'] ?? '');
    $length     = trim($_POST['length'] ?? '');
    $price      = trim($_POST['price'] ?? '');
    $tour_guide = trim($_POST['tour_guide'] ?? '');
    $image      = trim($_POST['image'] ?? '');

    // Validate inputs
    if(!$venue)        $errors[] = 'Venue is required.';
    if(!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) $errors[] = 'Date must be YYYY-MM-DD.';
    if(!$start)        $errors[] = 'Start time is required.';
    if(!$length)       $errors[] = 'Length is required.';
    if(!$price || !is_numeric($price)) $errors[] = 'Price must be a number.';
    if(!in_array($tour_guide, $guides)) $errors[] = 'Select a valid tour guide.';
    if(!$image)        $errors[] = 'Image filename is required.';

    // Insert if no errors
    if(empty($errors)) {
        $stmt = $conn->prepare(
            "INSERT INTO `bookings` (venue, date, start, length, price, tour_guide, image) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('ssssiss', $venue, $date, $start, $length, $price, $tour_guide, $image);
        if($stmt->execute()) {
            header("Location: managebookings.php?message=Booking+created");
            exit;
        } else {
            $errors[] = 'Database error: ' . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Booking</title>
    <link rel="stylesheet" href="../css/manageguides.css">
</head>
<body>
    <h2>Create New Booking</h2>

    <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="createbooking.php" method="post">
        <label>Venue:<br>
            <input type="text" name="venue" value="<?= htmlspecialchars($venue) ?>" required>
        </label><br><br>

        <label>Date (YYYY-MM-DD):<br>
            <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" required>
        </label><br><br>

        <label>Start:<br>
            <input type="text" name="start" value="<?= htmlspecialchars($start) ?>" required>
        </label><br><br>

        <label>Length:<br>
            <input type="text" name="length" value="<?= htmlspecialchars($length) ?>" required>
        </label><br><br>

        <label>Price:<br>
            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($price) ?>" required>
        </label><br><br>

        <label>Tour Guide:<br>
            <select name="tour_guide" required>
                <option value="">-- Select Guide --</option>
                <?php foreach($guides as $g): ?>
                    <option value="<?= htmlspecialchars($g) ?>" <?php if($g === $tour_guide) echo 'selected'; ?>>
                        <?= htmlspecialchars($g) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br><br>

        <label>Image Filename:<br>
            <input type="text" name="image" value="<?= htmlspecialchars($image) ?>" required>
        </label><br><br>

        <button type="submit" class="btn btn-success">Create Booking</button>
        <a href="managebookings.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>
