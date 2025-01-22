<?php
session_start();
require_once 'assets/php/connect.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: pages/admin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kopikolympics - Admin Login</title>
    <link rel="icon" href="assets/images/favicon.webp" type="img/icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Tourney:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/styles/index.css">
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-container" style="border-radius: 15px;">
            <div class="olympic-rings">
                <div class="ring"></div>
                <div class="ring"></div>
                <div class="ring"></div>
                <div class="ring"></div>
                <div class="ring"></div>
            </div>
            <div class="logo-container">
                <img src="assets/images/logo-admin.webp" class="mt-2 mb-4">
            </div>
            <h2 class="text-center mb-4 fs-5 sf poppins-bold">Admin Login</h2>
            <div id="loginError" class="alert alert-danger mb-3 poppins-regular" style="display: none;">
                Invalid username or password
            </div>
            <form id="loginForm">
                <div class="mb-3">
                    <label for="username" class="form-label sf poppins-medium">Username</label>
                    <input type="text" class="form-control poppins-regular" id="username" name="username" required>
                    <div class="invalid-feedback poppins-light">Please enter your username</div>
                </div>
                <div class="mb-5">
                    <label for="password" class="form-label sf poppins-medium">Password</label>
                    <input type="password" class="form-control poppins-regular" id="password" name="password" required>
                    <div class="invalid-feedback poppins-light">Please enter your password</div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary poppins-regular">Login</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const loginError = document.getElementById('loginError');

            username.classList.remove('is-invalid');
            password.classList.remove('is-invalid');
            loginError.style.display = 'none';

            const formData = new FormData(this);

            fetch('assets/php/login.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = 'pages/admin.php';
                    } else {
                        loginError.style.display = 'block';
                        username.classList.add('is-invalid');
                        password.classList.add('is-invalid');
                        password.value = '';
                    }
                })
                .catch(error => {
                    console.error('Login error:', error);
                    loginError.textContent = 'An error occurred. Please try again.';
                    loginError.style.display = 'block';
                });
        });
    </script>
</body>

</html>