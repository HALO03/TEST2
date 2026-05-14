<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">

</head>
<body style="background: #fff;">
    <h1>Welcome, <span><?= $_SESSION['name'] ?></span></h1>
    <p>this is an <span>admin</span> page</p>
    <button onclick="window.location.href='logout.php'">Logout</button>
</body>
</html>