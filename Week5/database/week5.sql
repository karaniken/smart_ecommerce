-- Week 5 products table
USE test_db;
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO products (name, description, price, stock) VALUES
('Smartphone X', 'Latest model with 128GB storage', 499.99, 25),
('Cotton T-Shirt', '100% cotton, available in multiple colors', 19.99, 100),
('Learn PHP Book', 'Complete guide for beginners', 39.99, 50),
('Desk Lamp', 'LED adjustable brightness', 29.99, 30);
