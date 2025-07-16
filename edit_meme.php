<?php
// edit_meme.php  —  update the title of a meme
header('Content-Type: application/json');
ini_set('display_errors', 0); error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success'=>false,'message'=>'Login required']); exit;
}

if (empty($_POST['meme_id']) || !ctype_digit($_POST['meme_id']) ||
    empty($_POST['title'])) {
    echo json_encode(['success'=>false,'message'=>'Bad request']); exit;
}

require 'db.php';

$userId = (int)$_SESSION['user_id'];
$memeId = (int)$_POST['meme_id'];
$title  = trim($_POST['title']);

if (strlen($title) > 140) {          // arbitrary… shorten as you like
    echo json_encode(['success'=>false,'message'=>'Title too long']); exit;
}

// ── OPTIONAL admin check again ──
$isAdmin = !empty($_SESSION['is_admin']);

try {
    // Make sure the meme belongs to user (or admin)
    $ownerId = $pdo->prepare("SELECT user_id FROM memes WHERE id = ?");
    $ownerId->execute([$memeId]);
    $row = $ownerId->fetchColumn();

    if (!$row) {
        echo json_encode(['success'=>false,'message'=>'Meme not found']); exit;
    }
    if ($row != $userId && !$isAdmin) {
        echo json_encode(['success'=>false,'message'=>'Unauthorized']); exit;
    }

    // Update the title
    $stmt = $pdo->prepare("UPDATE memes SET title = ? WHERE id = ?");
    $stmt->execute([$title,$memeId]);

    echo json_encode(['success'=>true,'title'=>$title]);
} catch (Exception $e) {
    error_log("edit_meme.php: ".$e->getMessage());
    echo json_encode(['success'=>false,'message'=>'DB error']);
}
