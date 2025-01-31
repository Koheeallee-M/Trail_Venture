<?php 
require_once 'dbh.inc.php';

function get_username(object $pdo,string $username){

    $query = "SELECT username FROM login WHERE username = :username;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);//fetches the first result, as an associative array
    return $result;
    
}

function get_email(object $pdo,string $email){

    $query = "SELECT email FROM login WHERE email = :email;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);//fetches the first result, as an associative array
    return $result;
    
}


function set_user(object $pdo, string $username, string $email, string $passwd, string $firstname, string $lastname, string $phonenum, string $address, string $id){
    try {
        $pdo->beginTransaction();
        $id = uniqid();

    
    $query = "INSERT INTO login (username, password) VALUES(:username, :passwd);";
    $stmt = $pdo->prepare($query);

    $options = [
        'cost' => 12 //increases the cost for this operation...stops brute force attacks
    ];

    $hashedPwd = password_hash($passwd, PASSWORD_BCRYPT, $options);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":passwd", $hashedPwd);
    $stmt->execute();

    $query = NULL;
    $stmt = NULL;


    $query = "INSERT INTO `registered customer` (cust_id, username, fname, lname, email, phonenum, address) VALUES(:cust_id, :username, :fname, :lname, :email, :phonenum, :address);";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":cust_id", $id);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":fname", $firstname);
    $stmt->bindParam(":lname", $lastname);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":phonenum", $phonenum);
    $stmt->bindParam(":address", $address);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $pdo->commit(); // Commit transaction if successful
        return true;
    } else {
        $pdo->rollBack(); // Rollback if insertion failed
        return false;
    }
} catch (\Throwable $th) {
    //throw $th;
}




    $query = NULL;
    $stmt = NULL;

    


}
   
