<?php

namespace app\controllers;

use app\controllers\Controller;

class UserController extends Controller {
    private $db;

    public function __construct() {
        try {
            $this->db = new \PDO('mysql:host=localhost;dbname=lemonade;charset=utf8', 'root', 'root'); // Default MAMP credentials
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die(json_encode(['message' => 'Database connection error: ' . $e->getMessage()]));
        }
    }
    

    public function registerUser() {
        // Get JSON data from the request body
        $data = json_decode(file_get_contents('php://input'), true);

        // Sanitize input and validate
        $username = htmlspecialchars($data['username'] ?? '');
        $password = $data['password'] ?? '';
        $company_name = htmlspecialchars($data['company_name'] ?? '');
        $credit_info = htmlspecialchars($data['credit_info'] ?? null);

        if (!$username || !$password || !$company_name) {
            echo json_encode(['message' => 'All fields except credit info are required.']);
            return;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into database
        try {
            $query = $this->db->prepare("INSERT INTO users (username, password, company_name, credit_info) VALUES (?, ?, ?, ?)");
            $success = $query->execute([$username, $hashed_password, $company_name, $credit_info]);

            if ($success) {
                echo json_encode(['message' => 'Registration successful!']);
            } else {
                echo json_encode(['message' => 'Error during registration.']);
            }
        } catch (\PDOException $e) {
            echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
        }
    }

    public function loginUser() {
        // Get JSON data from the request body
        $data = json_decode(file_get_contents('php://input'), true);

        // Sanitize input
        $username = htmlspecialchars($data['username'] ?? '');
        $password = $data['password'] ?? '';

        // Validate input
        if (!$username || !$password) {
            echo json_encode(['success' => false, 'message' => 'Both username and password are required.']);
            return;
        }

        // Query the database
        try {
            $query = $this->db->prepare("SELECT * FROM users WHERE username = ?");
            $query->execute([$username]);
            $user = $query->fetch(\PDO::FETCH_ASSOC);

            // Verify password
            if ($user && password_verify($password, $user['password'])) {
                unset($user['password']); // Remove the password before sending the response
                echo json_encode(['success' => true, 'user' => $user]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
            }
        } catch (\PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
}
