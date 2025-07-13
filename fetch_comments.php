<?php
header('Content-Type: application/json');
require 'db.php';

if (!isset($_GET['meme_id'])) {
    echo json_encode(['success' => false, 'message' => 'Meme ID is required']);
    exit;
}

$memeId = (int) $_GET['meme_id'];

try {
    $stmt = $pdo->prepare("
        SELECT comments.comment, comments.created_at, users.username 
        FROM comments 
        JOIN users ON comments.user_id = users.id 
        WHERE meme_id = ? 
        ORDER BY comments.created_at ASC
    ");
    $stmt->execute([$memeId]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'comments' => $comments]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching comments']);
}
