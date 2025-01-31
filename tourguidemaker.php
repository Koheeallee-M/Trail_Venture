<?php
// Database connection
$host = "localhost";
$dbname = "trailventure";
$dbusername = "root";
$dbpassword = "";
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $email = $password = $confirm_password = $first_name = $last_name = $phone = "";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $username = trim($_POST['userName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['password_check'];
    $first_name = trim($_POST['firstName']);
    $last_name = trim($_POST['lastName']);
    $phone = trim($_POST['phoneNumber']);

    // Error array to store validation issues
    $errors = [];

    // Check for empty fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) ||
        empty($first_name) || empty($last_name) || empty($phone)) {
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

    //Check if email matches format
    if(!preg_match('/^[\w\.-]+@[a-zA-Z\d\.-]+\.[a-zA-Z]{2,6}$/', $email)){
        $errors["bad_email"] = "Incorrect email format";
    }
    else{
        //Check if email already in use
        $stmt = $conn->prepare("SELECT * FROM `tour guides` WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors["taken_email"] = "Email is already in use.";
        }
    }
    
    
    //Add some contraints to password
    if(!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)){
        $errors["bad_password"] = "Password must be at least 8 characters long. Include an uppercase letter, a lowercase letter, a special character and a number";
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors["mismatch_password"] = "Passwords do not match.";
    }
    // Validate phone number (assuming 10 digits)
    if (!preg_match("/^[0-9]{8}$/", $phone)) {
        $errors["bad_phonenum"] = "Phone number must be 8 digits.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Encrypt the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into login table
        $stmt = $conn->prepare("INSERT INTO login (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        if ($stmt->execute()) {
            // Get the inserted user ID
            $login_id = $conn->insert_id;

            // Insert into registered customer table
            $stmt = $conn->prepare(
                "INSERT INTO `tour guides` (username, fname, lname, email, phonenum) 
                 VALUES (?, ?, ?, ?, ?)"
            );
            // Corrected bind_param with proper type definition string "ssssss" for six strings
            $stmt->bind_param("sssss", $username, $first_name, $last_name, $email, $phone);
            if ($stmt->execute()) {
                echo "Registration successful!";
                $_SESSION['userName'] = $username;
                $_SESSION['firstName'] = $first_name;
                $_SESSION['lastName'] = $last_name;
                $_SESSION['email'] = $email;
                $_SESSION['phoneNumber'] = $phone;
                // Redirect to success or login page
                header("Location: homepage.php");
                exit();
            } else {
                echo "Error registering guide information: " . $conn->error;
            }
        } else {
            echo "Error inserting login information: " . $conn->error;
        }
    } /*else {
        // Display errors
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
            */
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset= "UTF-8">
        <meta name="viewport" content = "width=device-width, initial-scale=1.0"> <!-- Ajust website to amy devise without any zoom-->
        <Title>Signup Guide Page</Title>
        <link rel ="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"> <!-- Reference from "font awesome for icon"-->
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
                    <span>Signup Guide</span>
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
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class ="input_field" value="<?php echo htmlspecialchars($email);?>" required>
                <?php if (isset($errors['bad_email'])): ?>
                <p class="error"><?php echo $errors['bad_email']; ?></p>
                <?php endif; ?>
                <?php if (isset($errors['taken_email'])): ?>
                <p class="error"><?php echo $errors['taken_email']; ?></p>
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
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" class = "input_field" value="<?php echo htmlspecialchars($first_name);?>" required>
                <i class="fa-regular fa-user icon"></i>
            </div>

            <div class="input_wrapper">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" class = "input_field" value="<?php echo htmlspecialchars($last_name);?>" required>
                <i class="fa-regular fa-user icon"></i>
                    
            </div>

            <div class="input_wrapper">
                <label for="phoneNumber">Phone Number:</label>
                <input type="tel" id="phoneNumber" name="phoneNumber" class = "input_field" value="<?php echo htmlspecialchars($phone);?>" required>
                <?php if (isset($errors['bad_phonenum'])): ?>
                <p class="error"><?php echo $errors['bad_phonenum']; ?></p>
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
                
    </form>
</body>
</html>