<?php 

function check_signup_errors() {
    if (isset($_SESSION['errors_signup'])) {
        $errors = $_SESSION['errors_signup']; // Array of all errors

        echo "<br>";
        foreach ($errors as $error) {
            echo "<p>$error</p>"; // Display errors one after another
        }

        unset($_SESSION['errors_signup']); // Unset used session variables
    } elseif (isset($_GET["signup"]) && $_GET["signup"] === "success") {
        echo '<br>';
        echo '<p>Signup success!</p>';
    }
}
/*
declare(strict_types = 1);

function check_signup_errors(){
    if(isset($_SESSION['errors_signup'])){
        $errors = $_SESSION['errors_signup']; //array of all errors
        
        echo "<br>";
        foreach($errors as $error){
          echo "<p> . $error . </p>";// errors displayed one after another
        }

        unset($_SESSION['errors_signup']); //unset used session variables
    } else if(isset($_GET["signup"]) && $_GET["signup"] === "success"){
      echo '<br>';
      echo '<p> Signup success!</p>';

    }
}*/



/*/*<div class="input_wrapper">
                    <label for="password_check" class="label">Verify Password</label>
                    <input type="password" name="password_check" id="password_check" class="input_field" required autocomplete="off"><br/>

                </div>*/