<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Login required']); exit;
}

if (!isset($_FILES['meme']) || $_FILES['meme']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']); exit;
}

/* ensure uploads/ folder exists */
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }

$ext      = pathinfo($_FILES['meme']['name'], PATHINFO_EXTENSION);
$filename = uniqid('meme_', true) . '.' . $ext;         // what we save in DB
$target   = $uploadDir . $filename;                     // full server path

/* move file */
if (!move_uploaded_file($_FILES['meme']['tmp_name'], $target)) {
    echo json_encode(['success' => false, 'message' => 'Failed to move file']); exit;
}

/* save only the file name */
$stmt = $pdo->prepare("INSERT INTO memes (user_id, filename, uploaded_at) VALUES (?, ?, NOW())");
$stmt->execute([$_SESSION['user_id'], $filename]);

echo json_encode(['success' => true, 'message' => 'Upload successful']);
