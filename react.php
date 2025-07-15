<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Login required']);
    exit;
}

if (!isset($_POST['meme_id'], $_POST['type']) ||
    !in_array($_POST['type'], ['like', 'upvote'], true)) {
    echo json_encode(['success' => false, 'message' => 'Bad request']);
    exit;
}

require 'db.php';

$userId = (int)$_SESSION['user_id'];
$memeId = (int)$_POST['meme_id'];
$type = $_POST['type'];

try {
    // Check if reaction exists
    $stmt = $pdo->prepare('SELECT 1 FROM reactions WHERE user_id = ? AND meme_id = ? AND type = ?');
    $stmt->execute([$userId, $memeId, $type]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        // Reaction exists, remove it (toggle off)
        $stmt = $pdo->prepare('DELETE FROM reactions WHERE user_id = ? AND meme_id = ? AND type = ?');
        $stmt->execute([$userId, $memeId, $type]);
        $action = 'removed';
    } else {
        // Insert new reaction (toggle on)
        $stmt = $pdo->prepare('INSERT INTO reactions (user_id, meme_id, type) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $memeId, $type]);
        $action = 'added';
    }

    echo json_encode(['success' => true, 'action' => $action]);
} catch (Exception $e) {
    error_log('react.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'DB error']);
}