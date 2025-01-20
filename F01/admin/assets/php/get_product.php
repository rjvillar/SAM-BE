<?php
require_once 'connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM products WHERE product_id = $id";
    $result = executeQuery($query);

    if ($product = mysqli_fetch_assoc($result)) {
        echo json_encode($product);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
    }
}
