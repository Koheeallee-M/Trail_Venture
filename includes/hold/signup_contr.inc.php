<?php
declare(strict_types = 1);
require_once '/includes/signup_model.inc.php';
$empty_error = $username_error = $email_error = $password_error = ''; 

function empty_input(string $username, string $email, string $passwd, string $passchk , string $firstname, string $lastname, string $phonenum, string $address){

    if(empty($username) || empty($email) || empty($passwd) || empty($passchk) || empty($firstname) || empty($lastname) || empty($phonenum) || empty($address)){
        $empty_error = "Please fill in all fields";
    }

}

function check_email(string $email){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $email_error = "Invalid email";
    }
}

function taken_username(object $pdo, string $username){
    if(get_username($pdo, $username)){
    $username_error = "Username is already taken";
    }
}

function taken_email(object $pdo, string $email){
    if(get_email($pdo, $email)){
    $email_error = "Email already in use";
    }
}

function password_mismatch(string $passwd, string $passchk){
    if(!($passwd == $passchk)){
    $password_error = "Password does not match";
    }
}

function create_user(object $pdo, string $username, string $email, string $passwd, string $firstname, string $lastname, string $phonenum, string $address, string $id){
            //SQL Query, no parameters as to avoid SQL Injection

            $query = "INSERT INTO login (username, password) VALUES(?, ?);";

            //set statment to connection, submit query
    
            $stmt = $pdo->prepare($query);
    
            //executes method
    
            $stmt->execute([$username, $passwd]);
    
            $query = null;
            $stmt = null;
    
            $query = "INSERT INTO `registered customer` (cust_id, username, fname, lname, email, phonenum, address) VALUES(?, ?, ?, ?, ?, ?, ?);";
    
            $stmt = $pdo->prepare($query);
    
            $stmt->execute([$id, $username, $email, $firstname, $lastname, $phonenum, $address]);
    
            $pdo = null;
            $stmt = null;
    
            header("Location: ../index.php");
    
            die();
    
    /*
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    
*/
}
