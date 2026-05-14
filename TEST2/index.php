<?php
session_start();

if (isset($_SESSION['email'])) {
    header('Location: dashboard.php');
    exit();
}

$error = [
    'login_error' => $_SESSION['login_error'] ?? '',
    'register_error' => $_SESSION['register_error'] ?? '',
];
$active_form = $_SESSION['active_form'] ?? 'login-form';

unset($_SESSION['login_error'], $_SESSION['register_error'], $_SESSION['active_form']);

function showError($error) {
    return $error ? "<p class='error-message'>$error</p>" : '';
} 

function isActiveform($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}

?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<div class="container">
<div class="form-box <?= isActiveform('login-form', $active_form) ?>" id="login-form">
    <form action="login_register.php" method="post">
        <h2>Login</h2>
        <?= showError($error['login_error']) ?>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
        <p>Don't have an account? <a href="javascript:void(0)" onclick="showForm('register-form')">Register</a></p>
    </form>
</div>

<div class="form-box <?= isActiveform('register-form', $active_form) ?>" id="register-form">
    <form action="login_register.php" method="post">
        <h2>Register</h2>
        <?= showError($error['register_error']) ?>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" required>
            <option value="">--Select Role--</option>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit" name="register">Register</button>
        <p>Already have an account? <a href="javascript:void(0)" onclick="showForm('login-form')">Login</a></p>
    </form>
</div>
</div>
<script src="script.js"></script>
</body>
</html>