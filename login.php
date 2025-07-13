<?php
session_start();
include 'db.php';

$username_or_email = $_POST['username'] ?? ''; // Assuming frontend sends 'username' for login as well
$password = $_POST['password'] ?? '';

// Try to find user by username or email
$stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = ? OR email = ?");
$stmt->execute([$username_or_email, $username_or_email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['id'];
    // Return the username for display on the frontend
    echo json_encode(['success' => true, 'username' => $user['username']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid username/email or password']);
}
?>