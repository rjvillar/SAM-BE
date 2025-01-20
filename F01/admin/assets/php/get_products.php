<?php
require_once 'connect.php';
$query = "SELECT * FROM products";
$result = executeQuery($query);
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}
echo json_encode($products);
