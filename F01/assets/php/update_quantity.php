<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    http_response_code(400);
    exit('Invalid request');
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

if ($quantity <= 0) {
    $query = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
} else {
    $query = "UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND product_id = $product_id";
}

if (executeQuery($query)) {
    echo 'success';
} else {
    http_response_code(500);
    echo 'error';
}
