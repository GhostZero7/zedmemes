<?php
header('Content-Type: application/json');
ini_set('display_errors', 0); // Disable error output in production
error_reporting(E_ALL);

session_start();
require 'db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Login required']);
    exit;
}

// Check if a file was uploaded
if (!isset($_FILES['meme']) || $_FILES['meme']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit;
}

// Optional title from form
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
if ($title === '') {
    $title = 'Untitled Meme'; // fallback
}

// Ensure uploads/ folder exists
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate unique filename
$ext = pathinfo($_FILES['meme']['name'], PATHINFO_EXTENSION);
$filename = uniqid('meme_', true) . '.' . $ext;
$target = $uploadDir . $filename;

// Move uploaded file
if (!move_uploaded_file($_FILES['meme']['tmp_name'], $target)) {
    echo json_encode(['success' => false, 'message' => 'Failed to move file']);
    exit;
}

// Save to database
try {
    $stmt = $pdo->prepare("INSERT INTO memes (user_id, filename, title, uploaded_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$_SESSION['user_id'], $filename, $title]);

    echo json_encode([
        'success' => true,
        'message' => 'Upload successful',
        'title' => $title,
        'filename' => $filename
    ]);
} catch (PDOException $e) {
    error_log("Upload DB error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error saving meme']);
}
?>
