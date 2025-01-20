<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['product_id'])) {
    http_response_code(400);
    exit('Invalid request');
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

$query = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
if (executeQuery($query)) {
    echo 'success';
} else {
    http_response_code(500);
    echo 'error';
}
