<?php
// Database connection
require_once '../includes/dbconnect.php';

session_start();

if(!isset($_SESSION['admin_id']) || (!($_SESSION['type'] === 2))) {
    header("Location: ../login.php");
    exit;
}


$username = $password = $confirm_password = $type ="";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $username = trim($_POST['userName']);
    $password = $_POST['password'];
    $confirm_password = $_POST['password_check'];
    $type = 2;

    // Error array to store validation issues
    $errors = [];

    // Check for empty fields
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $errors["empty_fields"] = "All fields are required.";
    }

    // Check if username is already taken
    $stmt = $conn->prepare("SELECT * FROM login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors["taken_username"] = "Username is already taken.";
    }
    
    
    //Add some contraints to password
    if(!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)){
        $errors["bad_password"] = "Password must be at least 8 characters long. Include an uppercase letter, a lowercase letter, a special character and a number";
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors["mismatch_password"] = "Passwords do not match.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Encrypt the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into login table
        $stmt = $conn->prepare("INSERT INTO login (username, password, type) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $username, $hashed_password, $type);
        if ($stmt->execute()) {
            // Get the inserted user ID
            $login_id = $conn->insert_id;

            // Insert into registered customer table
            $stmt = $conn->prepare(
                "INSERT INTO `admin` (username) 
                 VALUES (?)"
            );

            $stmt->bind_param("s", $username);
            if ($stmt->execute()) {
                echo "Admin registration successful!";
                header("Location: adminwelcome.php");
                exit();
            } else {
                echo "Error registering admin information: " . $conn->error;
            }
        } else {
            echo "Error inserting admin information: " . $conn->error;
        }
    } 
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset= "UTF-8">
        <meta name="viewport" content = "width=device-width, initial-scale=1.0"> <!-- Ajust website to amy devise without any zoom-->
        <Title>Admin Creator</Title>
        <link rel ="stylesheet" href="../css/signupstyle.css">
        
        <style>
            textarea{
                resize: none;
            }
        </style>
    </head>

    <body>
    <form method="POST">
        <div class = "container">
          <div class = "login_container">

            <div class="login_title">
                    <span>Create an Admin</span>
            </div>
            

            <div class="input_wrapper">
                <label for="userName">Username:</label>
                <input type="text" id="userName" name="userName" class="input_field" value="<?php echo htmlspecialchars($username);?>" required>
                <?php if (isset($errors['taken_username'])): ?>
                <p class="error"><?php echo $errors['taken_username']; ?></p>
                <?php endif; ?>
                <i class="fa-regular fa-user icon"></i>
                    
            </div>

            <div class="input_wrapper">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class = "input_field" required>
                <?php if (isset($errors['bad_password'])): ?>
                <p class="error"><?php echo $errors['bad_password']; ?></p>
                <?php endif; ?>
                <i class="fa-regular fa-user icon"></i>
                    
            </div>

            <div class="input_wrapper">
                <label for="password_check">Re-enter password:</label>
                <input type="password" id="password_check" name="password_check" class = "input_field" required>
                <?php if (isset($errors['mismatch_password'])): ?>
                <p class="error"><?php echo $errors['mismatch_password']; ?></p>
                <?php endif; ?>
                <i class="fa-regular fa-user icon"></i>
                    
            </div>
        
            <div class="input_wrapper">
                    <input type="submit" class="input_submit" value="Sign up"><br><br>
            </div>

            <div>
            <?php if (isset($errors['empty_fields'])): ?>
                <p class="error"><?php echo $errors['empty_fields']; ?></p>
                <?php endif; ?>
            </div>

            <div class="signin">
                    <span>Return to landing page? <a href="adminwelcomepage.php">Return to landing</a></span>
            </div>
        </div>
    </div>       
    </form>
</body>
</html>
