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
    error_log('DB error in fetch_memes.php: '.$e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error during meme fetch']);
    exit;
}

/* pagination and sorting */
$page   = isset($_GET['page'])  ? max(0, (int)$_GET['page'])  : 0;
$limit  = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 5;
$offset = $page * $limit;
$sort   = isset($_GET['sort'])  ? $_GET['sort']               : 'new'; // Default sort to 'new'

$orderBy = '';
switch ($sort) {
    case 'hot':
        // Order by upvotes (descending), then by upload date (descending)
        $orderBy = 'upvotes DESC, m.uploaded_at DESC';
        break;
    case 'trending':
        // For a more advanced "trending," you'd typically incorporate a time decay
        // or a combination of upvotes, comments, and views within a recent period.
        // For now, we'll use a similar logic to 'hot'.
        $orderBy = 'upvotes DESC, m.uploaded_at DESC';
        break;
    case 'all':
        // Order by upload date (descending) for 'all' memes
        $orderBy = 'm.uploaded_at DESC';
        break;
    case 'new':
    default:
        // Default sort by upload date (descending)
        $orderBy = 'm.uploaded_at DESC';
        break;
}

try {
    /* total memes for front‑end pagination */
    // Note: totalMemes currently counts all memes, not filtered ones.
    $totalMemes = (int)$pdo->query('SELECT COUNT(*) FROM memes')->fetchColumn();

    /* main query: memes + username + reaction counts (upvotes and downvotes) */
    $sql = "
        SELECT
            m.id,
            m.filename,                --  e.g. meme_abc.jpg  (no 'uploads/' prefix)
            m.uploaded_at,
            u.username,
            COALESCE(upt.upvotes, 0) AS upvotes,   -- Count upvotes
            COALESCE(dpt.downvotes, 0) AS downvotes -- Count downvotes (new)
        FROM memes m
        JOIN users u ON u.id = m.user_id
        /* aggregate up-votes */
        LEFT JOIN (
            SELECT meme_id, COUNT(*) AS upvotes
            FROM reactions
            WHERE type = 'upvote'
            GROUP BY meme_id
        ) upt ON upt.meme_id = m.id
        /* aggregate down-votes */
        LEFT JOIN (
            SELECT meme_id, COUNT(*) AS downvotes
            FROM reactions
            WHERE type = 'downvote'
            GROUP BY meme_id
        ) dpt ON dpt.meme_id = m.id
        ORDER BY {$orderBy}
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $memes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success'    => true,
        'memes'      => $memes,
        'totalMemes' => $totalMemes // Renamed 'total' to 'totalMemes' for clarity and consistency with JS
    ]);

} catch (Exception $e) {
    error_log('fetch_memes error: '.$e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error fetching memes: '.$e->getMessage()]);
}

?>