<?php
// delete_meme.php  —  hard‑delete a meme you own (or if admin)
header('Content-Type: application/json');
ini_set('display_errors', 0); error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success'=>false,'message'=>'Login required']); exit;
}

if (empty($_POST['meme_id']) || !ctype_digit($_POST['meme_id'])) {
    echo json_encode(['success'=>false,'message'=>'Bad request']); exit;
}

require 'db.php';

$userId = (int)$_SESSION['user_id'];
$memeId = (int)$_POST['meme_id'];

// ── OPTIONAL: if you keep an “is_admin” flag in the session  ────────────────
$isAdmin = !empty($_SESSION['is_admin']); // adjust to your auth logic

try {
    // 1. Verify the meme exists and belongs to this user (or admin)
    $stmt = $pdo->prepare("SELECT filename, user_id FROM memes WHERE id = ?");
    $stmt->execute([$memeId]);
    $meme = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$meme) {
        echo json_encode(['success'=>false,'message'=>'Meme not found']); exit;
    }
    if ($meme['user_id'] != $userId && !$isAdmin) {
        echo json_encode(['success'=>false,'message'=>'Unauthorized']); exit;
    }

    $pdo->beginTransaction();

    // 2. Delete reactions & comments if you don’t have ON DELETE CASCADE
    $pdo->prepare("DELETE FROM reactions WHERE meme_id = ?")->execute([$memeId]);
    $pdo->prepare("DELETE FROM comments  WHERE meme_id = ?")->execute([$memeId]);

    // 3. Delete the meme row
    $pdo->prepare("DELETE FROM memes WHERE id = ?")->execute([$memeId]);

    $pdo->commit();

    // 4. Optionally remove the image file from disk
    $filePath = __DIR__ . '/uploads/' . $meme['filename'];
    if (is_file($filePath)) { @unlink($filePath); }

    echo json_encode(['success'=>true]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log("delete_meme.php: ".$e->getMessage());
    echo json_encode(['success'=>false,'message'=>'DB error']);
}
