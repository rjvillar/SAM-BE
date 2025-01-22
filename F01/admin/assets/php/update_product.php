<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['product_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $image_query = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $old_image_query = "SELECT image FROM products WHERE product_id = $id";
            $result = executeQuery($old_image_query);
            $old_image = mysqli_fetch_assoc($result)['image'];
            if ($old_image) {
                unlink('../images/' . $old_image);
            }

            $newName = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $newName);
            $image_query = ", image = '$newName'";
        }
    }

    $query = "UPDATE products 
              SET name = '$name', 
                  description = '$description', 
                  price = $price, 
                  category = '$category'
                  $image_query 
              WHERE product_id = $id";

    if (executeQuery($query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
