CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_salt VARCHAR(16) NOT NULL,
    password_hash VARCHAR(64) NOT NULL,
    encrypted_password VARCHAR(255) NOT NULL
);

ALTER TABLE users MODIFY password_salt CHAR(128);