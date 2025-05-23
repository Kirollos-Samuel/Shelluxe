-- Create database
CREATE DATABASE IF NOT EXISTS shelluxe;
USE shelluxe;

-- Customer table
CREATE TABLE IF NOT EXISTS Customer (
    customer_ID INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(100) NOT NULL,
    contact_add VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Address table
CREATE TABLE IF NOT EXISTS Address (
    address_ID INT PRIMARY KEY AUTO_INCREMENT,
    customer_ID INT,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    country VARCHAR(50) NOT NULL,
    is_default BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (customer_ID) REFERENCES Customer(customer_ID) ON DELETE CASCADE
);

-- Category table
CREATE TABLE IF NOT EXISTS Category (
    category_ID INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(50) NOT NULL,
    category_type VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seller table
CREATE TABLE IF NOT EXISTS Seller (
    seller_ID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS Products (
    product_ID INT PRIMARY KEY AUTO_INCREMENT,
    category_ID INT,
    product_name VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_ID) REFERENCES Category(category_ID) ON DELETE SET NULL
);

-- Seller Products table
CREATE TABLE IF NOT EXISTS Seller_Products (
    seller_ID INT,
    product_ID INT,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    PRIMARY KEY (seller_ID, product_ID),
    FOREIGN KEY (seller_ID) REFERENCES Seller(seller_ID) ON DELETE CASCADE,
    FOREIGN KEY (product_ID) REFERENCES Products(product_ID) ON DELETE CASCADE
);

-- Shopping Order table
CREATE TABLE IF NOT EXISTS Shopping_Order (
    order_ID INT PRIMARY KEY AUTO_INCREMENT,
    customer_ID INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_ID) REFERENCES Customer(customer_ID) ON DELETE SET NULL
);

-- Order Items table
CREATE TABLE IF NOT EXISTS Order_Items (
    product_ID INT,
    order_ID INT,
    quantity INT NOT NULL,
    price_at_time DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (product_ID, order_ID),
    FOREIGN KEY (product_ID) REFERENCES Products(product_ID) ON DELETE CASCADE,
    FOREIGN KEY (order_ID) REFERENCES Shopping_Order(order_ID) ON DELETE CASCADE
);

-- Payment table
CREATE TABLE IF NOT EXISTS Payment (
    payment_ID INT PRIMARY KEY AUTO_INCREMENT,
    order_ID INT,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('credit_card', 'debit_card', 'paypal', 'bank_transfer') NOT NULL,
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_ID) REFERENCES Shopping_Order(order_ID) ON DELETE CASCADE
);

-- Deliveries table
CREATE TABLE IF NOT EXISTS Deliveries (
    delivery_ID INT PRIMARY KEY AUTO_INCREMENT,
    order_ID INT,
    address_ID INT,
    status ENUM('pending', 'in_transit', 'delivered', 'failed') DEFAULT 'pending',
    tracking_number VARCHAR(50) UNIQUE,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_ID) REFERENCES Shopping_Order(order_ID) ON DELETE CASCADE,
    FOREIGN KEY (address_ID) REFERENCES Address(address_ID) ON DELETE SET NULL
);

-- Wishlist table
CREATE TABLE IF NOT EXISTS Wishlist (
    wishlist_ID INT PRIMARY KEY AUTO_INCREMENT,
    customer_ID INT,
    product_ID INT,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_ID) REFERENCES Customer(customer_ID) ON DELETE CASCADE,
    FOREIGN KEY (product_ID) REFERENCES Products(product_ID) ON DELETE CASCADE
);

-- Cart table
CREATE TABLE IF NOT EXISTS Cart (
    cart_ID INT PRIMARY KEY AUTO_INCREMENT,
    customer_ID INT,
    product_ID INT,
    quantity INT NOT NULL DEFAULT 1,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_ID) REFERENCES Customer(customer_ID) ON DELETE CASCADE,
    FOREIGN KEY (product_ID) REFERENCES Products(product_ID) ON DELETE CASCADE
);

-- Product Reviews table
CREATE TABLE IF NOT EXISTS Product_Reviews (
    review_ID INT PRIMARY KEY AUTO_INCREMENT,
    product_ID INT,
    customer_ID INT,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    date_posted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_ID) REFERENCES Products(product_ID) ON DELETE CASCADE,
    FOREIGN KEY (customer_ID) REFERENCES Customer(customer_ID) ON DELETE CASCADE
); 