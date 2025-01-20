<?php
session_start();
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Direct comparison since password is stored as plain text
    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = executeQuery($query);

    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $admin['admin_id'];
        echo json_encode(['status' => 'success']);
        exit;
    }

    // Log failed attempt
    error_log("Failed login attempt for username: $username");
    echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
}
