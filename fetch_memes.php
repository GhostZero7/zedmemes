<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();

try {
    require 'db.php';
    if (!$pdo) throw new Exception('PDO connection failed');
} catch (Exception $e) {
    error_log('DB error in fetch_memes.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error during meme fetch']);
    exit;
}

// Pagination + sorting
$page   = isset($_GET['page'])  ? max(0, (int)$_GET['page'])  : 0;
$limit  = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 5;
$offset = $page * $limit;
$sort   = $_GET['sort'] ?? 'new';

$orderBy = 'm.uploaded_at DESC'; // default
$useTrendingTime = false;

switch ($sort) {
    case 'hot':
        $orderBy = 'upvotes DESC, m.uploaded_at DESC';
        break;
    case 'popular':
        $orderBy = 'likes DESC, m.uploaded_at DESC';
        break;
    case 'trending':
        $orderBy = 'likes_last_hour DESC, m.uploaded_at DESC';
        $useTrendingTime = true;
        $timeLimit = date('Y-m-d H:i:s', strtotime('-1 hour'));
        break;
    case 'all':
        $orderBy = 'RAND()'; // RANDOM ORDER for 'all'
        break;
    case 'new':
    default:
        $orderBy = 'm.uploaded_at DESC';
        break;
}

try {
    $totalMemes = (int)$pdo->query('SELECT COUNT(*) FROM memes')->fetchColumn();

    $sql = "
        SELECT
            m.id,
            m.filename,
            m.title,
            m.uploaded_at,
            m.user_id,
            u.username,
            COALESCE(lkt.likes, 0) AS likes,
            COALESCE(upt.upvotes, 0) AS upvotes,
            COALESCE(tlk.likes_last_hour, 0) AS likes_last_hour
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
        LEFT JOIN (
            SELECT meme_id, COUNT(*) AS likes_last_hour
            FROM reactions
            WHERE type = 'like' AND created_at >= :timeLimit
            GROUP BY meme_id
        ) tlk ON tlk.meme_id = m.id
        ORDER BY {$orderBy}
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':timeLimit', $useTrendingTime ? $timeLimit : '2000-01-01 00:00:00', PDO::PARAM_STR);
    $stmt->execute();
    $memes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success'    => true,
        'memes'      => $memes,
        'totalMemes' => $totalMemes
    ]);
} catch (Exception $e) {
    error_log('fetch_memes error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error fetching memes: ' . $e->getMessage()]);
}
