CREATE DATABASE IF NOT EXISTS ai_analyzer CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ai_analyzer;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('user','admin') DEFAULT 'user',
    lang ENUM('ru','kz','en') DEFAULT 'ru'
);

CREATE TABLE analyses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    type ENUM('resume','gpa'),
    input_text TEXT,
    file_name VARCHAR(255) DEFAULT NULL,
    result TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    hr_id INT DEFAULT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name,email,password,role) VALUES ('Admin','admin@gmail.com',SHA2('admin',256),'admin');
