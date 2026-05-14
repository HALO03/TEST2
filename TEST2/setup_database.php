<?php
/**
 * Database Setup/Migration Script
 * This script updates the courses table and creates a user_courses table
 * Run this once to initialize the database structure
 */

$host = "localhost";
$user = "root";
$password = "";
$database = "users_input";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Migration Starting...</h2>";
    
    // Check if description and url columns exist in courses table
    $stmt = $pdo->query("DESCRIBE courses");
    $columns = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $columns[] = $row['Field'];
    }
    
    // Add description column if it doesn't exist
    if (!in_array('description', $columns)) {
        $pdo->exec("ALTER TABLE courses ADD COLUMN description LONGTEXT DEFAULT NULL AFTER name");
        echo "<p style='color: green;'>✓ Added 'description' column to courses table</p>";
    } else {
        echo "<p style='color: blue;'>ℹ 'description' column already exists</p>";
    }
    
    // Add url column if it doesn't exist
    if (!in_array('url', $columns)) {
        $pdo->exec("ALTER TABLE courses ADD COLUMN url VARCHAR(500) DEFAULT NULL AFTER description");
        echo "<p style='color: green;'>✓ Added 'url' column to courses table</p>";
    } else {
        echo "<p style='color: blue;'>ℹ 'url' column already exists</p>";
    }
    
    // Add created_at column if it doesn't exist
    if (!in_array('created_at', $columns)) {
        $pdo->exec("ALTER TABLE courses ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER url");
        echo "<p style='color: green;'>✓ Added 'created_at' column to courses table</p>";
    } else {
        echo "<p style='color: blue;'>ℹ 'created_at' column already exists</p>";
    }
    
    // Create user_courses table if it doesn't exist
    $tableExistsStmt = $pdo->query("SHOW TABLES LIKE 'user_courses'");
    if ($tableExistsStmt->rowCount() == 0) {
        $pdo->exec("
            CREATE TABLE user_courses (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                course_id INT NOT NULL,
                enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                started_at TIMESTAMP NULL DEFAULT NULL,
                completed_at TIMESTAMP NULL DEFAULT NULL,
                progress_percentage INT DEFAULT 0,
                status ENUM('enrolled', 'in_progress', 'completed', 'paused') DEFAULT 'enrolled',
                last_accessed TIMESTAMP NULL DEFAULT NULL,
                UNIQUE KEY unique_enrollment (user_id, course_id),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
                INDEX idx_user_id (user_id),
                INDEX idx_course_id (course_id),
                INDEX idx_status (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "<p style='color: green;'>✓ Created 'user_courses' table for tracking user course enrollments</p>";
    } else {
        echo "<p style='color: blue;'>ℹ 'user_courses' table already exists</p>";
    }
    
    echo "<h3 style='color: green;'>✓ Database migration completed successfully!</h3>";
    echo "<p><strong>Next steps:</strong></p>";
    echo "<ul>";
    echo "<li>Update courses through the admin panel with descriptions and URLs</li>";
    echo "<li>The user_courses table now tracks enrollments, progress, and completion status</li>";
    echo "</ul>";
    echo "<p><a href='admin-users/courses.php' style='color: blue; text-decoration: underline;'>Go to Courses Management</a></p>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>Error during migration:</h2>";
    echo "<p style='color: red;'>" . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h2, h3 {
            color: #333;
        }
        p, li {
            color: #555;
            line-height: 1.6;
        }
        a {
            color: #0066cc;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
</body>
</html>
