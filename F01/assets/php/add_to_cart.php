<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['product_id'])) {
    http_response_code(400);
    exit('Invalid request');
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

$check_query = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
$result = executeQuery($check_query);

if (mysqli_num_rows($result) > 0) {
    $query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id";
} else {
    $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)";
}

if (executeQuery($query)) {
    echo 'success';
} else {
    http_response_code(500);
    echo 'error';
}
