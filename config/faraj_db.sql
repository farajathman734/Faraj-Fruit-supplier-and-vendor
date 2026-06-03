-- ============================================
-- Faraj Fruit Supplier and Vendor
-- Database Setup Script
-- Run this in phpMyAdmin > SQL tab
-- ============================================

-- Step 1: Create the database
CREATE DATABASE IF NOT EXISTS faraj_db;

-- Step 2: Use it
USE faraj_db;

-- Step 3: Create a test table (will be expanded in later weeks)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    price_retail DECIMAL(10,2),
    price_wholesale DECIMAL(10,2),
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Step 4: Insert sample data
INSERT INTO products (name, category, price_retail, price_wholesale, stock) VALUES
('Banana', 'Tropical', 10.00, 7.00, 200),
('Mango', 'Tropical', 25.00, 18.00, 150),
('Apple', 'Temperate', 30.00, 22.00, 100),
('Watermelon', 'Melons', 50.00, 35.00, 80);
