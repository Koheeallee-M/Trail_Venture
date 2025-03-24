<?php
// Start the session
session_start();

// Include your database connection file
require_once 'includes/dbconnect.php';

$username = $password = "";

$errors[] ="";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} // Adjust to the actual file that contains the DB connection logic

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    // Get the username and password from the POST request
    $username = $_POST['userName'];
    $password = $_POST['passwd'];

    // Check if both fields are filled
    if (empty($username) || empty($password)) {
        echo "Both fields are required!";
        exit;
    }
/*
    function validatePassword($conn, $username, $password, $tableName) {
        // SQL query to fetch username and password from the table
        $sql = "SELECT * FROM $tableName WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If the username is found, fetch the data
            $row = $result->fetch_assoc();
            // Verify the password (assuming passwords are hashed)
            if (password_verify($password, $row['password'])) {
                return true;
            }
        }
        return false;
    }

    $isPasswordValid = false;

    // Check if password is valid in registered_customer
    if (validatePassword($conn, $username, $password, 'registered customer')) {
        $isPasswordValid = true;
        $tableFound = `registered customer`;
    }
    // Check if password is valid in tour_guide
    elseif (validatePassword($conn, $username, $password, `tour guides`)) {
        $isPasswordValid = true;
        $tableFound = `tour_guides`;
    }
    // Check if password is valid in admin
    elseif (validatePassword($conn, $username, $password, 'admin')) {
        $isPasswordValid = true;
        $tableFound = 'admin';
    }
    
    if ($isPasswordValid) {
        if ($tableFound == `registered customer`) {
            $type = 1; // Registered customer
        } elseif ($tableFound == `tour guides`) {
            $type = 2; // Tour guide
        } elseif ($tableFound == 'admin') {
            $type = 3; // Admin
        }
        $_SESSION['userName'] = $user['username'];
        $_SESSION['firstName'] = $user['fname'];
        $_SESSION['lastName'] = $user['lname'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phoneNumber'] = $user['phonenum'];
        $_SESSION['type'] = $user[];

        header("Location: homepage.php");
        exit;
    }

*/
    // Prepare the SQL query to fetch the hashed password
    $stmt = $conn->prepare(" SELECT password FROM login 
        WHERE username = ?
        AND (username IN (SELECT username FROM `registered customer`)
        OR username IN (SELECT username FROM `tour guides`)
        OR username IN (SELECT username FROM `admin`))
    ");


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
            $stmt = $conn->prepare("
            SELECT cust_id, username, fname, lname, email, phonenum FROM `registered customer` WHERE username = ?
            UNION
            SELECT guide_id, username, fname, lname, email, phonenum FROM `tour guides` WHERE username = ?
            ");
            $stmt->bind_param("ss", $username, $username);
            $stmt->execute();
            $user_result = $stmt->get_result();

            if ($user_result->num_rows === 1) {
                // Fetch user details
                $user = $user_result->fetch_assoc();

                // Store user data in session
                $_SESSION['cust_id'] = $user['cust_id'];
                $_SESSION['userName'] = $user['username'];
                $_SESSION['firstName'] = $user['fname'];
                $_SESSION['lastName'] = $user['lname'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['address'] = $address;
                $_SESSION['phoneNumber'] = $user['phonenum'];

            // Redirect to the welcome page or dashboard
                header("Location: homepage.php");
                exit;
            }
        } else {
            // Invalid password
            $errors["wrong_password"] = "Invalid Password!";
        }
    } else {
        // Username not found
        $errors["notfound"] = "Invalid Username!";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset= "UTF-8">
        <meta name="viewport" content = "width=device-width, initial-scale=1.0"> <!-- Ajust website to amy devise without any zoom-->
        <Title>Login Form</Title>
        <link rel ="stylesheet" href="loginstyle.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"> <!-- Reference from "font awesome for icon"-->
    </head>

    <body>
     <form method="POST">
        <div class ="container">
            <div class = "login_container">
                
                <div class="login_title">
                    <span>Login</span>
                </div>

                <div class="trailventure">
                    <h2>Trailventure</h2>
                </div>


                <div class="input_wrapper">
                    <label for="user" class="label">Username</label>
                    <input type="text" name="userName" id="user" class="input_field" value="<?php echo htmlspecialchars($username);?>" required>
                    <?php if (isset($errors['notfound'])): ?>
                    <p class="error"><?php echo $errors['notfound']; ?></p>
                    <?php endif; ?>
                    <i class="fa-regular fa-user icon"></i>
                    
                </div>

               

                <div class="input_wrapper">
                    <label for="pass" class="label">Password</label>
                    <input type="password" name="passwd" id="pass" class="input_field" required>
                    <?php if (isset($errors['wrong_password'])): ?>
                    <p class="error"><?php echo $errors['wrong_password']; ?></p>
                    <?php endif; ?>
                    <i class="fa-solid fa-lock icon"></i>
                </div>

                

                <div class="forgot">
                    <a href="#">Forgot Password</a>
                </div>

                <div class="input_wrapper">
                    <input type="submit" class="input_submit" value="Login">
                </div>

                <br/>

                <div class="signin">
                    <span>Don't have an account? <a href="signup.php">Sign Up</a></span>
                </div>
            </div>
        </div>
    </form>
    </body>
</html>