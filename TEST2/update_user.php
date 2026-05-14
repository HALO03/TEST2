<?php
header('Content-Type: application/json');


$host = "localhost";
$user = "root";
$password = "";
$database = "users_input";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}


$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['newName'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
    exit;
}

$newName = trim($input['newName']);
// $email = trim($input['email']);

if (empty($newName)) {
    echo json_encode(['success' => false, 'error' => 'Name and email cannot be empty']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?"); //sql statement to update record in DB
    $stmt->execute([$newName, $input['id']]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'User not found or no changes made']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>