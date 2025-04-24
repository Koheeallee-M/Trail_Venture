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
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    // Get the username and password from the POST request
    $username = $_POST['userName'];
    $password = $_POST['passwd'];

    // Check if both fields are filled
    if (empty($username) || empty($password)) {
        echo "Both fields are required!";
        exit;
    }

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

    $stmt->close();

    // Check if the username exists
    if ($result->num_rows === 1) {
        // Fetch the hashed password from the database
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        $result->free();
       

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start the session
             // Check the type of user
             $stmtType = $conn->prepare("SELECT `type` FROM login WHERE username = ?");
             $stmtType->bind_param("s", $username);
             $stmtType->execute();

             
             $stmtType->store_result();  
             $stmtType->bind_result($type);  

             if ($stmtType->fetch()) {
             $type = (int)$type; 
             } else {
            die("User not found or type missing.");
             }

            $stmtType->close();
            $_SESSION['type'] = $type;


            if($type === 2){
                //User is an admin
                $stmtAdmin = $conn->prepare("
                SELECT admin_id, username
                FROM admin
                WHERE username = ?"
            );

                $stmtAdmin->bind_param("s", $username);
                $stmtAdmin->execute();
                $admin_result = $stmtAdmin->get_result();

                $admin = $admin_result->fetch_assoc();

                $stmtAdmin->close();

                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['username'] = $admin['username'];

                // Redirect to the admin page
                header('Location: Admin/adminwelcomepage.php');
                exit;
            }
            else if($type === 0){
            $stmtcust = $conn->prepare("
            SELECT cust_id, username, fname, lname, email, phonenum, address FROM `registered customer` WHERE username = ?
            ");
            $stmtcust->bind_param("s", $username);
            $stmtcust->execute();
            $user_result = $stmtcust->get_result();

            $stmtcust->close();

            if ($user_result->num_rows === 1) {
                // Fetch user details
                $user = $user_result->fetch_assoc();

                // Store user data in session
                $_SESSION['cust_id'] = $user['cust_id'];
                $_SESSION['userName'] = $user['username'];
                $_SESSION['firstName'] = $user['fname'];
                $_SESSION['lastName'] = $user['lname'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['address'] = $user['address'];
                $_SESSION['phoneNumber'] = $user['phonenum'];

            // Redirect to the welcome page
                echo "Reached header";
                header("Location: homepage.php");
                exit;
            }
            }
            else if($type === 1){
                $stmttour = $conn->prepare("
                SELECT guide_id, username, fname, lname, email, phonenum FROM `tour guides` WHERE username = ?
                ");
                $stmttour->bind_param("s", $username);
                $stmttour->execute();
                $user_result = $stmttour->get_result();

                $stmttour->close();
    
                if ($user_result->num_rows === 1) {
                    // Fetch user details
                    $user = $user_result->fetch_assoc();
    
                    // Store user data in session
                    $_SESSION['guide_id'] = $user['guide_id'];
                    $_SESSION['userName'] = $user['username'];
                    $_SESSION['firstName'] = $user['fname'];
                    $_SESSION['lastName'] = $user['lname'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['phoneNumber'] = $user['phonenum'];
    
                // Redirect to the book page
                    header("Location: book.php");
                    exit;
                }   
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
    
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset= "UTF-8">
        <meta name="viewport" content = "width=device-width, initial-scale=1.0"> <!-- Ajust website to amy devise without any zoom-->
        <Title>Login Form</Title>
        <link rel ="stylesheet" href="css/loginstyle.css">
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