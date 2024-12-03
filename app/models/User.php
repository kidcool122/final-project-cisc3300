<?php

namespace app\models;

require_once __DIR__ . '/../core/setup.php';

class User {
    private $db;

    public function __construct() {
        $this->db = connectDatabase();
    }

    public function register($username, $password, $companyName, $creditInfo = null) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password, company_name, credit_info) VALUES (:username, :password, :company_name, :credit_info)");
        return $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':company_name' => $companyName,
            ':credit_info' => $creditInfo
        ]);
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
