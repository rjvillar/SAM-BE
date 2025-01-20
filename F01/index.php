<?php
session_start();
require_once 'assets/php/connect.php';

$error = '';
$success = '';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['loginEmail']);
    $password = $_POST['loginPassword'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = executeQuery($query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            header('Location: pages/user.php');
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }
}

if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($conn, $_POST['signupName']);
    $email = mysqli_real_escape_string($conn, $_POST['signupEmail']);
    $password = password_hash($_POST['signupPassword'], PASSWORD_DEFAULT);

    $check_query = "SELECT * FROM users WHERE email='$email'";
    $check_result = executeQuery($check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $error = "Email already exists!";
    } else {
        $query = "INSERT INTO users (full_name, email, password) VALUES ('$name', '$email', '$password')";
        if (executeQuery($query)) {
            $success = "Account created successfully!";
        } else {
            $error = "Error creating account!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/images/favicon.webp" type="img/icon">
    <title>kopikolympics - Login/Signup</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Tourney:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/styles/index.css">
</head>

<body>
    <div class="container">
        <div class="message-wrapper">
            <?php if ($error): ?>
                <div class="alert alert-danger poppins-regular">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success poppins-regular">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="forms-container">
            <div class="form-section <?php echo (isset($_GET['form']) && $_GET['form'] === 'signup') ? 'hidden' : ''; ?>" id="loginForm">
                <div class="logo-container">
                    <img src="assets/images/logo.webp">
                </div>
                <form method="post" action="">
                    <div class="input-group">
                        <label for="loginEmail" class="df poppins-medium">Email</label>
                        <input type="email" class="poppins-regular" name="loginEmail" id="loginEmail" required>
                    </div>
                    <div class="input-group">
                        <label for="loginPassword" class="df poppins-medium">Password</label>
                        <div class="password-input">
                            <input type="password" class="poppins-regular" name="loginPassword" id="loginPassword" required>
                        </div>
                    </div>
                    <button type="submit" name="login" class="submit-btn poppins-regular">Login</button>
                </form>
                <p class="switch-form poppins-regular">
                    Don't have an account?
                    <a href="?form=signup">Sign up</a>
                </p>
            </div>

            <div class="form-section <?php echo (!isset($_GET['form']) || $_GET['form'] !== 'signup') ? 'hidden' : ''; ?>" id="signupForm">
                <h2 class="df poppins-bold">Create Account</h2>
                <form method="POST" action="">
                    <div class="input-group">
                        <label for="signupName" class="df poppins-medium">Full Name</label>
                        <input type="text" class="poppins-regular" name="signupName" id="signupName" required>
                    </div>
                    <div class="input-group">
                        <label for="signupEmail" class="df poppins-medium">Email</label>
                        <input type="email" class="poppins-regular" name="signupEmail" id="signupEmail" required>
                    </div>
                    <div class="input-group">
                        <label for="signupPassword" class="df poppins-medium">Password</label>
                        <div class="password-input">
                            <input type="password" class="poppins-regular" name="signupPassword" id="signupPassword" required>
                        </div>
                    </div>
                    <button type="submit" name="signup" class="submit-btn poppins-regular">Sign Up</button>
                </form>
                <p class="switch-form poppins-regular">
                    Already have an account?
                    <a href="?form=login">Login</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>