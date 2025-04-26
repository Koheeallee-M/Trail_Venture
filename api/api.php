<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/../vendor/autoload.php';

use Opis\JsonSchema\Validator;
use Opis\JsonSchema\Schema;
use Opis\JsonSchema\Errors\ErrorFormatter;

define('CART_FILE', __DIR__ . '/../carts/csjson');

// Handle POST request 
function handlePost() {
    $schemaPath = __DIR__ . '/cart_schema.json';

    // Check if schema exists
    if (!file_exists($schemaPath)) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Schema file not found"]);
        return;
    }

    // Load and decode schema
    $schemaData = json_decode(file_get_contents($schemaPath));
    if ($schemaData === null) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Invalid schema JSON"]);
        return;
    }

    $input = json_decode(file_get_contents('php://input'));

    if ($input === null) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid JSON sent"]);
        return;
    }

    $validator = new Validator();
    $result = $validator->validate($input, $schemaData);

    if($result -> isValid()){
        $cartId = uniqid('cart_', true);
        $input->cart_id = $cartId;
    
        if (!is_dir(dirname(CART_FILE))) {
            mkdir(dirname(CART_FILE), 0777, true);
        }
    
        $cartData = ['cart_id' => $cartId, 'data' => $input];
        file_put_contents(CART_FILE, json_encode($cartData, JSON_PRETTY_PRINT));
    
        echo json_encode([
            "status" => "success",
            "message" => "Cart saved successfully.",
            "redirect" => "checkout.php",
        ]);
    }else{
        http_response_code(422);//validation errors
        $formatter = new ErrorFormatter();
            $error = $formatter->format($result->error());
            throw new Exception("Validation error: " . json_encode($error));
    }
}

// Handle GET request 
function handleGet() {
    header('Content-Type: application/json');

    // Check if the cart file exists
    if (!file_exists(CART_FILE)) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "No cart found"]);
        return;
    }

    // Read the contents of the cart file
    $data = json_decode(file_get_contents(CART_FILE), true);

    // Check if the cart data is empty
    if (empty($data)) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "No valid cart data found"]);
        return;
    }

    // Here we're assuming that the cart_id is stored directly within the JSON data
    $cartId = $data['cart_id'];  // If the cart_id is directly in the JSON root, retrieve it.

    // Check if the cart_id exists in the file's data
    if (!$cartId) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "No cart_id found in the data"]);
        return;
    }

    // Retrieve the cart data
    echo json_encode([
        "status" => "success",
        "data" => $data // Return the entire cart data
    ]);
}


// Handle routing based on the request method
header('Content-Type: application/json');
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        handlePost();
        break;
    case 'GET':
        handleGet();
        break;
    default:
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Method Not Allowed"]);
        break;
}
?>
