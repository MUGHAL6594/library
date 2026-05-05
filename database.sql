CREATE DATABASE IF NOT EXISTS book_library;
USE book_library;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    genre VARCHAR(100),
    pages INT,
    rating INT,
    description TEXT,
    available BOOLEAN DEFAULT TRUE,
    cover_image VARCHAR(255) DEFAULT NULL
);

-- Seed data for books
INSERT INTO books (title, author, genre, pages, rating, description, available, cover_image) VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', 'Classic', 180, 5, 'A story of wealth and love in the 1920s.', TRUE, NULL),
('1984', 'George Orwell', 'Dystopia', 328, 4, 'A novel about a totalitarian future.', TRUE, NULL),
('The Hobbit', 'J.R.R. Tolkien', 'Fantasy', 310, 5, 'A journey to the Lonely Mountain.', TRUE, NULL),
('Brave New World', 'Aldous Huxley', 'Dystopia', 268, 4, 'A satirical look at a future society.', FALSE, NULL),
('Pakistan', 'Nawaj', 'Politics', 10, 5, 'A book about politics.', TRUE, NULL);

-- Seed data for users
INSERT INTO users (name, email, password, role) VALUES
('Admin User', 'admin@example.com', 'admin123', 'admin'),
('John Doe', 'john@example.com', 'password123', 'user');
