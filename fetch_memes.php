<?php
header('Content-Type: application/json');

/* production‑safe error handling */
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

session_start();

/* DB connection */
try {
    require 'db.php';              //  defines $pdo
    if (!$pdo) throw new Exception('PDO connection failed');
} catch (Exception $e) {
    error_log('DB error: '.$e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}

/* pagination */
$page   = isset($_GET['page'])  ? max(0, (int)$_GET['page'])  : 0;
$limit  = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 5;
$offset = $page * $limit;

try {
    /* total memes for front‑end pagination */
    $totalMemes = (int)$pdo->query('SELECT COUNT(*) FROM memes')->fetchColumn();

    /* main query: memes + username + reaction counts */
    $sql = "
        SELECT 
            m.id,
            m.filename,                --  e.g. meme_abc.jpg  (no 'uploads/' prefix)
            m.uploaded_at,
            u.username,
            COALESCE(l.likes,   0) AS likes,
            COALESCE(upt.upvotes, 0) AS upvotes
        FROM memes m
        JOIN users u ON u.id = m.user_id
        /* aggregate likes  */
        LEFT JOIN (
            SELECT meme_id, COUNT(*) AS likes
            FROM reactions
            WHERE type = 'like'
            GROUP BY meme_id
        ) l   ON l.meme_id = m.id
        /* aggregate up‑votes  */
        LEFT JOIN (
            SELECT meme_id, COUNT(*) AS upvotes
            FROM reactions
            WHERE type = 'upvote'
            GROUP BY meme_id
        ) upt ON upt.meme_id = m.id
        ORDER BY m.uploaded_at DESC
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $memes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'memes'   => $memes,
        'total'   => $totalMemes
    ]);
} catch (Exception $e) {
    error_log('fetch_memes error: '.$e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error fetching memes']);
}
