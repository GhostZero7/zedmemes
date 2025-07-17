<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Login required']);
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT m.id, m.filename, m.title, m.uploaded_at, u.username,
        COALESCE(lkt.likes, 0) AS likes,
        COALESCE(upt.upvotes, 0) AS upvotes
    FROM memes m
    JOIN users u ON u.id = m.user_id
    LEFT JOIN (
        SELECT meme_id, COUNT(*) AS likes
        FROM reactions
        WHERE type = 'like'
        GROUP BY meme_id
    ) lkt ON lkt.meme_id = m.id
    LEFT JOIN (
        SELECT meme_id, COUNT(*) AS upvotes
        FROM reactions
        WHERE type = 'upvote'
        GROUP BY meme_id
    ) upt ON upt.meme_id = m.id
    WHERE m.user_id = ?
    ORDER BY m.uploaded_at DESC
");
$stmt->execute([$userId]);

$memes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'memes' => $memes]);
