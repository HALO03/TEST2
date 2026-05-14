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

if (!$input || !isset($input['newName']) || !isset($input['id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
    exit;
}

$newName = trim($input['newName']);
$newDescription = isset($input['newDescription']) ? trim($input['newDescription']) : '';
$newUrl = isset($input['newUrl']) ? trim($input['newUrl']) : '';
$id = $input['id'];

if (empty($newName)) {
    echo json_encode(['success' => false, 'error' => 'Name cannot be empty']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE courses SET name = ?, description = ?, url = ? WHERE id = ?");
    $stmt->execute([$newName, $newDescription, $newUrl, $id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Course not found or no changes made']);
    }
} catch (PDOException $e) {
    $isDuplicate = $e->getCode() == 23000 || stripos($e->getMessage(), 'Duplicate entry') !== false;
    $errorMessage = $isDuplicate ? 'Error: Course name already exists.' : 'Database error: ' . $e->getMessage();
    echo json_encode(['success' => false, 'error' => $errorMessage]);
}
?>