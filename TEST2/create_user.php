<?php
$contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
$isJson = strpos($contentType, 'application/json') !== false;

if ($isJson) {
    header('Content-Type: application/json');
}

$host = "localhost";
$user = "root";
$password = "";
$database = "users_input";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    if ($isJson) {
        echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    } else {
        header('Location: admin-users/list.php?error=' . rawurlencode('Database connection failed'));
    }
    exit;
}

$input = [];
if ($isJson) {
    $input = json_decode(file_get_contents('php://input'), true);
} else {
    $input = $_POST;
}

if (!$input || !isset($input['name'], $input['email'], $input['password'], $input['role'])) {
    if ($isJson) {
        echo json_encode(['success' => false, 'error' => 'Invalid input data']);
    } else {
        header('Location: admin-users/list.php?error=' . rawurlencode('Invalid input data'));
    }
    exit;
}

$name = trim($input['name']);
$email = trim($input['email']);
$password = trim($input['password']);
$role = trim($input['role']);

if (empty($name) || empty($email) || empty($password) || empty($role)) {
    if ($isJson) {
        echo json_encode(['success' => false, 'error' => 'All fields are required']);
    } else {
        header('Location: admin-users/list.php?error=' . rawurlencode('All fields are required'));
    }
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    if ($isJson) {
        echo json_encode(['success' => false, 'error' => 'Invalid email address']);
    } else {
        header('Location: admin-users/list.php?error=' . rawurlencode('Invalid email address'));
    }
    exit;
}

try {
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
        if ($isJson) {
            echo json_encode(['success' => false, 'error' => 'Email already exists']);
        } else {
            header('Location: admin-users/list.php?error=' . rawurlencode('Email already exists'));
        }
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $passwordHash, $role]);

    if ($stmt->rowCount() > 0) {
        if ($isJson) {
            echo json_encode(['success' => true]);
        } else {
            header('Location: admin-users/list.php?created=1');
        }
    } else {
        if ($isJson) {
            echo json_encode(['success' => false, 'error' => 'Failed to create user']);
        } else {
            header('Location: admin-users/list.php?error=' . rawurlencode('Failed to create user'));
        }
    }
} catch (PDOException $e) {
    if ($isJson) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    } else {
        header('Location: admin-users/list.php?error=' . rawurlencode('Database error: ' . $e->getMessage()));
    }
}
