<?php
header('Content-Type: application/json');
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Login required']);
    exit;
}

$userId = $_SESSION['user_id'];
$memeId = isset($_POST['meme_id']) ? (int)$_POST['meme_id'] : 0;
$comment = trim($_POST['comment'] ?? '');

if ($memeId <= 0 || $comment === '') {
    echo json_encode(['success' => false, 'message' => 'Invalid comment or meme']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO comments (meme_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->execute([$memeId, $userId, $comment]);
    echo json_encode(['success' => true, 'message' => 'Comment added']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to add comment']);
}
