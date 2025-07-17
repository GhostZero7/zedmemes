<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$memeId = $_POST['meme_id'] ?? null;

if (!$memeId) {
    echo json_encode(['success' => false, 'message' => 'Meme ID missing']);
    exit;
}

// Make sure the user owns the meme
$stmt = $pdo->prepare("SELECT filename FROM memes WHERE id = ? AND user_id = ?");
$stmt->execute([$memeId, $_SESSION['user_id']]);
$meme = $stmt->fetch();

if (!$meme) {
    echo json_encode(['success' => false, 'message' => 'Meme not found or access denied']);
    exit;
}

// Delete the file
$filePath = __DIR__ . '/uploads/' . $meme['filename'];
if (file_exists($filePath)) unlink($filePath);

// Delete from database
$stmt = $pdo->prepare("DELETE FROM memes WHERE id = ?");
$deleted = $stmt->execute([$memeId]);

echo json_encode(['success' => $deleted]);
