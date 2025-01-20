<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit('Unauthorized');
}

$cart_query = "SELECT c.*, p.name, p.price 
               FROM cart c 
               JOIN products p ON c.product_id = p.product_id 
               WHERE c.user_id = " . $_SESSION['user_id'];
$cart_result = executeQuery($cart_query);
$cart_items = [];
$total = 0;

while ($row = mysqli_fetch_assoc($cart_result)) {
    $cart_items[] = $row;
    $total += $row['price'] * $row['quantity'];
}

header('Content-Type: application/json');
echo json_encode([
    'items' => $cart_items,
    'total' => $total
]);
