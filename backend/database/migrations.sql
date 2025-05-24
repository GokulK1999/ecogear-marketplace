-- EcoGear Database Setup
-- This file will automatically create all required tables

-- Create database
CREATE DATABASE IF NOT EXISTS ecogear_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecogear_db;

-- Users table for registration/login
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    date_of_birth DATE,
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    postal_code VARCHAR(10),
    gender ENUM('male', 'female', 'other', '') DEFAULT '',
    profile_picture VARCHAR(255),
    email_notifications BOOLEAN DEFAULT TRUE,
    sms_notifications BOOLEAN DEFAULT FALSE,
    newsletter BOOLEAN DEFAULT TRUE,
    profile_visible BOOLEAN DEFAULT TRUE,
    share_activity BOOLEAN DEFAULT FALSE,
    data_saving BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Orders table for purchases
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    order_date DATE NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    subtotal DECIMAL(10,2) NOT NULL,
    shipping DECIMAL(10,2) DEFAULT 0,
    tax DECIMAL(10,2) DEFAULT 0,
    discount DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    shipping_name VARCHAR(100),
    shipping_address TEXT,
    shipping_city VARCHAR(50),
    shipping_state VARCHAR(50),
    shipping_postal_code VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_name VARCHAR(100) NOT NULL,
    product_brand VARCHAR(50),
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Insert sample data for testing
INSERT INTO users (first_name, last_name, email, phone, password_hash, address, city, state, postal_code) VALUES
('Gokul', 'Kalla', 'gokul@ecogear.com', '+60 12-345 6789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 Jalan Sustainable', 'Kuala Lumpur', 'Kuala Lumpur', '50000');

-- Get the user ID for sample orders
SET @user_id = LAST_INSERT_ID();

-- Insert sample orders
INSERT INTO orders (user_id, order_number, order_date, status, subtotal, shipping, tax, total, shipping_name, shipping_address, shipping_city, shipping_state, shipping_postal_code) VALUES
(@user_id, 'ECO-2024-001', '2024-01-15', 'delivered', 1597.90, 46.95, 131.59, 1776.44, 'Demo User', '123 Jalan Sustainable', 'Kuala Lumpur', 'Kuala Lumpur', '50000'),
(@user_id, 'ECO-2024-002', '2024-02-03', 'shipped', 892.95, 46.95, 75.19, 925.79, 'Demo User', '123 Jalan Sustainable', 'Kuala Lumpur', 'Kuala Lumpur', '50000'),
(@user_id, 'ECO-2024-003', '2024-02-20', 'processing', 751.90, 46.95, 63.91, 862.76, 'Demo User', '123 Jalan Sustainable', 'Kuala Lumpur', 'Kuala Lumpur', '50000');

-- Insert sample order items
INSERT INTO order_items (order_id, product_name, product_brand, price, quantity, image_url) VALUES
(1, 'EcoTent Pro 4', 'EcoGear', 1409.95, 1, '/src/assets/images/eco-tent.jpg'),
(1, 'Bamboo Water Bottle', 'BambooLife', 164.45, 1, '/src/assets/images/bamboo-bottle.jpg'),
(2, 'Solar Backpack 35L', 'SolarTrek', 892.95, 1, '/src/assets/images/solar-backpack.jpg'),
(3, 'Solar Power Bank', 'SolarCharge', 375.95, 2, '/src/assets/images/bamboo-bottle.jpg');

-- Create indexes for better performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_order_items_order_id ON order_items(order_id);

SELECT 'Database setup completed successfully!' as message;