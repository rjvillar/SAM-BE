<?php
require_once 'connect.php';

$query = "SELECT *, DATE_FORMAT(created_at, '%Y-%m-%d') as date FROM feedback ORDER BY created_at DESC";
$result = executeQuery($query);

$feedbacks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $feedbacks[] = $row;
}

echo json_encode($feedbacks);
