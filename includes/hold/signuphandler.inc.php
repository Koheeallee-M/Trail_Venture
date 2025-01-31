<?php
//This...handles the signup
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = uniqid();
    $username = $_POST["userName"];
    $email = $_POST["email"];
    $passwd = $_POST["password"];
    $passchk = $_POST["password_check"];
    $firstname = $_POST["firstName"];
    $lastname = $_POST["lastName"];
    $phonenum = $_POST["phoneNumber"];
    $address = $_POST["address"];

    

   //Outputs error message if something messes up 

    try {
        require_once "dbh.inc.php";
        require_once 'signup_model.inc.php';
        require_once 'signup_contr.inc.php';

        //ERROR HANDLING

        /*


        if (empty_input($username, $email, $passwd, $passchk, $firstname, $lastname, $phonenum, $address)){
            $empty_error = "Please fill in all fields";
        }

        if(check_email($email)){
            $email_error = "Invalid email";
        }

        if (taken_username($pdo, $username)){
            $username_error = "Username is already taken";

        }

        if (password_mismatch($passwd, $passchk)){
            $password_error = "Password incorrectly written";

        }
            */

        require_once 'config_session.inc.php'; //starts a session


        //create_user($pdo, $username, $email, $passwd, $firstname, $lastname, $phonenum, $address, $id);//creates user
        if(set_user($pdo, $username, $email, $passwd, $firstname, $lastname, $phonenum, $address, $id) == true){
            header("Location: ../login.php");
        }
        else{
            header("Location: ../index.php");
        }


        die();


    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }

}
else{
    header("Location: ../index.php");
    die();
}