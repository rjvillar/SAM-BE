<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    $query = "SELECT image FROM products WHERE product_id = $product_id";
    $result = executeQuery($query);
    $product = mysqli_fetch_assoc($result);

    if ($product && $product['image']) {
        $image_path = '../../../assets/images/products/' . $product['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    $delete_query = "DELETE FROM products WHERE product_id = $product_id";
    if (executeQuery($delete_query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'error']);
}
