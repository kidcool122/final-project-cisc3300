<?php
require_once 'setup.php';

$input = json_decode(file_get_contents('php://input'), true);

$username = htmlspecialchars($input['username']);
$password = $input['password'];

$query = $db->prepare("SELECT * FROM users WHERE username = ?");
$query->execute([$username]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    session_start();
    $_SESSION['user'] = $user;
    echo json_encode(['success' => true, 'message' => 'Login successful!', 'user' => $user]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
}
