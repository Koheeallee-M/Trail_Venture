<?php
//Sets up cookies 
//Changes some ini settings
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 1800, //sets duration to 30 mins
    'domain' => 'localhost',
    'path' => '/',
    'secure' => true,
    'httponly' => true
]);

//regeneratess session id
function regen_id(){
    session_regenerate_id();
    $_SESSION["last_regen"] = time();
}

session_start();

if (!isset($_SESSION["last_regen"])) {
    regen_id();//set session id if not set
}

else{
    $interval  = 60*30;
    if(time() - $_SESSION["last_regen"] >= $interval){
        regen_id();//resets session id after 30 mins(expired)
    }
}