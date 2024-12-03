CREATE DATABASE IF NOT EXISTS lemonade;
USE lemonade;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    credit_info VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, company_name, credit_info) VALUES
('john_doe', '$2y$10$K4/Vi0ryH4mVUEt/mk6EsO5hG/ItOcznCp7Iz6nIMpB1PDb92Pp7e', 'John Lemonade Co.', '1234-5678-9012'),
('jane_doe', '$2y$10$X3QhK7/8EStEN6tXllCgqeO2mVCYOpYv.MYCEkcbF8vT75R/v/6jy', 'Jane’s Lemon Shack', NULL);

-- Notes:
-- Passwords are hashed using bcrypt for security. 
-- Credit info is optional, as shown by the second row.
