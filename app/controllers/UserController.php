<?php

namespace app\controllers;

use app\controllers\Controller;

class UserController extends Controller {
    private $db;

    public function __construct() {
        session_start(); // Start session for all requests
        try {
            $this->db = new \PDO('mysql:host=localhost;dbname=lemonade;charset=utf8', 'root', 'root');
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die(json_encode(['message' => 'Database connection error: ' . $e->getMessage()]));
        }
    }

    public function registerUser() {
        $data = json_decode(file_get_contents('php://input'), true);

        $username = htmlspecialchars($data['username'] ?? '');
        $password = $data['password'] ?? '';
        $company_name = htmlspecialchars($data['company_name'] ?? '');
        $credit_info = htmlspecialchars($data['credit_info'] ?? '');

        if (!$username || !$password || !$company_name) {
            echo json_encode(['message' => 'All fields except credit info are required.']);
            return;
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            $query = $this->db->prepare("INSERT INTO users (username, password, company_name, credit_info) VALUES (?, ?, ?, ?)");
            $success = $query->execute([$username, $hashed_password, $company_name, $credit_info]);

            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Registration successful']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error during registration.']);
            }
        } catch (\PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }

    public function loginUser() {
        $data = json_decode(file_get_contents('php://input'), true);

        $username = htmlspecialchars($data['username'] ?? '');
        $password = $data['password'] ?? '';

        if (!$username || !$password) {
            echo json_encode(['success' => false, 'message' => 'Both username and password are required.']);
            return;
        }

        try {
            $query = $this->db->prepare("SELECT * FROM users WHERE username = ?");
            $query->execute([$username]);
            $user = $query->fetch(\PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Mark user as logged in in the database
                $updateQuery = $this->db->prepare("UPDATE users SET is_logged_in = 1 WHERE id = ?");
                $updateQuery->execute([$user['id']]);

                echo json_encode(['success' => true, 'message' => 'Login successful']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
            }
        } catch (\PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }

    public function logoutUser() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'No user session found.']);
            return;
        }

        try {
            $userId = $_SESSION['user_id'];

            // Mark user as logged out in the database
            $updateQuery = $this->db->prepare("UPDATE users SET is_logged_in = 0 WHERE id = ?");
            $updateQuery->execute([$userId]);

            // Clear session
            session_unset();
            session_destroy();

            echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
        } catch (\PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }

    public function getUser() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['authenticated' => false]);
            return;
        }

        try {
            $query = $this->db->prepare("SELECT username, company_name, credit_info FROM users WHERE id = ?");
            $query->execute([$_SESSION['user_id']]);
            $user = $query->fetch(\PDO::FETCH_ASSOC);

            if ($user) {
                echo json_encode(['authenticated' => true, 'user' => $user]);
            } else {
                echo json_encode(['authenticated' => false]);
            }
        } catch (\PDOException $e) {
            echo json_encode(['authenticated' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
}
