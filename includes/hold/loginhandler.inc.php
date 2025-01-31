<?php
// Start the session
session_start();

// Include your database connection file
require 'includes/dbconnect.php'; // Adjust to the actual file that contains the DB connection logic

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the POST request
    $username = $_POST['userName'];
    $password = $_POST['passwd'];

    // Check if both fields are filled
    if (empty($username) || empty($password)) {
        echo "Both fields are required!";
        exit;
    }

    // Prepare the SQL query to fetch the hashed password
    $stmt = $conn->prepare("SELECT password FROM login WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    // Bind the username to the query
    $stmt->bind_param("s", $username);

    // Execute the query
    if (!$stmt->execute()) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }

    // Get the result
    $result = $stmt->get_result();

    // Check if the username exists
    if ($result->num_rows === 1) {
        // Fetch the hashed password from the database
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start the session
            $_SESSION['username'] = $username;

            // Redirect to the welcome page or dashboard
            header("Location: welcome.php");
            exit;
        } else {
            // Invalid password
            echo "Incorrect password!";
        }
    } else {
        // Username not found
        echo "No account found with that username!";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
