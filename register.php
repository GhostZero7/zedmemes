<?php
session_start();

include 'db.php';  // INCLUDE DB first to define $pdo

if (!$pdo) {
    die("DB connection failed");
}

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Check if user exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
$stmt->execute([$email, $username]);

if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'User already exists']);
    exit;
}

// Insert new user
$stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");

if ($stmt->execute([$username, $email, $password_hash])) {
    $newUserId = $pdo->lastInsertId();
    $_SESSION['user_id'] = $newUserId;
    echo json_encode(['success' => true, 'user_id' => $newUserId, 'username' => $username]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add user']);
}
