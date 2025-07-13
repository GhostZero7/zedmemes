<?php
header('Content-Type: application/json');
ini_set('display_errors',0); error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success'=>false,'message'=>'Login required']); exit;
}
if (!isset($_POST['meme_id'], $_POST['type']) ||
    !in_array($_POST['type'], ['like','upvote'], true)) {
    echo json_encode(['success'=>false,'message'=>'Bad request']); exit;
}

require 'db.php';
$userId = (int)$_SESSION['user_id'];
$memeId = (int)$_POST['meme_id'];
$type   = $_POST['type'];

try {
    /* insert if not already reacted (UNIQUE key prevents dupes) */
    $stmt = $pdo->prepare(
        'INSERT IGNORE INTO reactions (user_id,meme_id,type) VALUES (?,?,?)'
    );
    $stmt->execute([$userId,$memeId,$type]);
    echo json_encode(['success'=>true]);
} catch (Exception $e) {
    error_log('react.php: '.$e->getMessage());
    echo json_encode(['success'=>false,'message'=>'DB error']);
}
