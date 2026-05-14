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
        header('Location: admin-users/courses.php?error=' . rawurlencode('Database connection failed'));
    }
    exit;
}

$input = [];
if ($isJson) {
    $input = json_decode(file_get_contents('php://input'), true);
} else {
    $input = $_POST;
}

if (!$input || !isset($input['name'])) {
    if ($isJson) {
        echo json_encode(['success' => false, 'error' => 'Invalid input data']);
    } else {
        header('Location: admin-users/courses.php?error=' . rawurlencode('Invalid input data'));
    }
    exit;
}

$name = trim($input['name']);
$description = isset($input['description']) ? trim($input['description']) : '';
$url = isset($input['url']) ? trim($input['url']) : '';

if (empty($name)) {
    if ($isJson) {
        echo json_encode(['success' => false, 'error' => 'Name cannot be empty']);
    } else {
        header('Location: admin-users/courses.php?error=' . rawurlencode('Name cannot be empty'));
    }
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO courses (name, description, url) VALUES (?, ?, ?)");
    $stmt->execute([$name, $description, $url]);

    if ($stmt->rowCount() > 0) {
        if ($isJson) {
            echo json_encode(['success' => true]);
        } else {
            header('Location: admin-users/courses.php?created=1');
        }
    } else {
        if ($isJson) {
            echo json_encode(['success' => false, 'error' => 'Failed to create course']);
        } else {
            header('Location: admin-users/courses.php?error=' . rawurlencode('Failed to create course'));
        }
    }
} catch (PDOException $e) {
    $isDuplicate = $e->getCode() == 23000 || stripos($e->getMessage(), 'Duplicate entry') !== false;
    $errorMessage = $isDuplicate ? 'Error: Course name already exists.' : 'Database error: ' . $e->getMessage();

    if ($isJson) {
        echo json_encode(['success' => false, 'error' => $errorMessage]);
    } else {
        header('Location: admin-users/courses.php?error=' . rawurlencode($errorMessage));
    }
}


?>