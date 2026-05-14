<?php

session_start();
require 'config.php';

if (isset($_POST['register'])) {
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $conn->real_escape_string($_POST['role']);

    $checkemail = $conn->query("SELECT email FROM users WHERE email='$email'");
    if ($checkemail->num_rows > 0) {
        $_SESSION['register_error'] = "Email already exists.";
        $_SESSION['active_form'] = 'register-form';
    } else {
        $conn->query("INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')");
        $_SESSION['register_error'] = "Registration successful. Please login.";
        $_SESSION['active_form'] = 'login-form';
    }

    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: dashboard-admin.html");
            } else {
                header("Location: dashboard.html");
            }
            exit();
        }
    }

    $_SESSION['login_error'] = "Invalid email or password.";
    $_SESSION['active_form'] = 'login-form';
    header("Location: index.php");
    exit();
}

?>