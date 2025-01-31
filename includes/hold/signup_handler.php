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

        $errors =[];

        if (empty_input($username, $email, $passwd, $passschk, $firstname, $lastname, $phonenum, $address) == false){
            $errors["empty_input"] = "Please fill in all fields";
        }

        if(check_email($email)){
            $errors["invalid_email"] = "Invalid email";
        }

        if (taken_username($pdo, $username)){
            $errors["username_taken"]= "Username is already taken";

        }

        require_once 'config_session.inc.php'; //starts a session

        if($errors){
            $_SESSION["error_signup"] = $errors;
            header('../signup.php');

        }


        //SQL Query, no parameters as to avoid SQL Injection

        $query = "INSERT INTO login (username, password) VALUES(?, ?);";

        //set statment to connection, submit query

        $stmt = $pdo->prepare($query);

        //executes method

        $stmt->execute([$username, $passwd]);

        $query = null;
        $stmt = null;

        $query = "INSERT INTO `registered customer` (cust_id,username, fname, lname, email, phonenum, address) VALUES(?, ?, ?, ?, ?, ?, ?);";

        $stmt = $pdo->prepare($query);

        $stmt->execute([$id, $username, $email, $firstname, $lastname, $phonenum, $address]);

        $pdo = null;
        $stmt = null;

        header("Location: ../index.php");

        die();


    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }

}
else{
    header("Location: ../index.php");
    die();
}