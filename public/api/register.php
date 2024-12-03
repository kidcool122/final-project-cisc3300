<?php
require_once 'setup.php';

$input = json_decode(file_get_contents('php://input'), true);

$username = htmlspecialchars($input['username']);
$password = password_hash($input['password'], PASSWORD_BCRYPT);
$companyName = htmlspecialchars($input['companyName']);
$creditInfo = htmlspecialchars($input['creditInfo']);

if (!$username || !$password || !$companyName) {
    echo json_encode(['success' => false, 'message' => 'All required fields must be filled.']);
    return;
}

$query = $db->prepare("INSERT INTO users (username, password, company_name, credit_info) VALUES (?, ?, ?, ?)");
$success = $query->execute([$username, $password, $companyName, $creditInfo]);

echo json_encode(['success' => $success, 'message' => $success ? 'Registration successful!' : 'Error occurred during registration.']);
