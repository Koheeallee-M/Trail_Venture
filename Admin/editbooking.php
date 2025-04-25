<?php
session_start();

// Only admin type 2 can access
if (!isset($_SESSION['admin_id']) || $_SESSION['type'] !== 2) {
    header("Location: ../login.php");
    exit;
}
require_once '../includes/dbconnect.php';

// Fetch tour guides for dropdown
$guides = [];
$guideQuery = "SELECT username FROM `tour guides`";
if ($res = $conn->query($guideQuery)) {
    while ($r = $res->fetch_assoc()) {
        $guides[] = $r['username'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldVenue   = trim($_POST['old_venue'] ?? '');
    $venue      = trim($_POST['venue'] ?? '');
    $date       = trim($_POST['date'] ?? '');
    $start      = trim($_POST['start'] ?? '');
    $length     = trim($_POST['length'] ?? '');
    $price      = trim($_POST['price'] ?? '');
    $tour_guide = trim($_POST['tour_guide'] ?? '');
    $image      = trim($_POST['image'] ?? '');

    // Update booking record
    $stmt = $conn->prepare(
        "UPDATE `bookings` SET venue = ?, date = ?, start = ?, length = ?, price = ?, tour_guide = ?, image = ? WHERE venue = ?"
    );
    $stmt->bind_param(
        'ssssssss',
        $venue,
        $date,
        $start,
        $length,
        $price,
        $tour_guide,
        $image,
        $oldVenue
    );
    $stmt->execute();
    $stmt->close();

    header("Location: managebookings.php?message=Booking+updated");
    exit;
}

// GET: fetch existing booking by venue
$venue = filter_input(INPUT_GET, 'venue', FILTER_SANITIZE_STRING);
if (!$venue) {
    header("Location: managebookings.php");
    exit;
}
$stmt = $conn->prepare("SELECT * FROM `bookings` WHERE venue = ?");
$stmt->bind_param('s', $venue);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: managebookings.php");
    exit;
}
$row = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <link rel="stylesheet" href="../css/manageguides.css">
</head>
<body>
    <div class="booking-container">
        <h2 class="booking-title">Edit Booking</h2>
        <form action="editbooking.php" method="post" class="booking-form">
            <input type="hidden" name="old_venue" value="<?= htmlspecialchars($row['venue']) ?>">

            <div class="form-group">
                <label for="venue">Venue:</label>
                <input type="text" id="venue" name="venue" value="<?= htmlspecialchars($row['venue']) ?>" required>
            </div>

            <div class="form-group">
                <label for="date">Date (YYYY-MM-DD):</label>
                <input type="text" id="date" name="date" pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD" value="<?= htmlspecialchars($row['date']) ?>" required>
            </div>

            <div class="form-group">
                <label for="start">Start:</label>
                <input type="text" id="start" name="start" value="<?= htmlspecialchars($row['start']) ?>" required>
            </div>

            <div class="form-group">
                <label for="length">Length:</label>
                <input type="text" id="length" name="length" value="<?= htmlspecialchars($row['length']) ?>" required>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($row['price']) ?>" required>
            </div>

            <div class="form-group">
                <label for="tour_guide">Tour Guide:</label>
                <select id="tour_guide" name="tour_guide" required>
                    <option value="">-- Select Guide --</option>
                    <?php foreach ($guides as $g): ?>
                        <option value="<?= htmlspecialchars($g) ?>" <?php if ($g === $row['tour_guide']) echo 'selected'; ?>>
                            <?= htmlspecialchars($g) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Image Filename:</label>
                <input type="text" id="image" name="image" value="<?= htmlspecialchars($row['image']) ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-success">Update Booking</button>
                <a href="managebookings.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>