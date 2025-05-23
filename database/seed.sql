USE shelluxe;

-- Insert sample customers
INSERT INTO Customer (customer_name, contact_add, email, password) VALUES
('John Doe', '123-456-7890', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
('Jane Smith', '987-654-3210', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Bob Johnson', '555-123-4567', 'bob@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample addresses
INSERT INTO Address (customer_ID, address, city, state, postal_code, country, is_default) VALUES
(1, '123 Main St', 'New York', 'NY', '10001', 'USA', TRUE),
(2, '456 Oak Ave', 'Los Angeles', 'CA', '90001', 'USA', TRUE),
(3, '789 Pine Rd', 'Chicago', 'IL', '60601', 'USA', TRUE);

-- Insert sample categories
INSERT INTO Category (category_name, category_type, description) VALUES
('Bracelets', 'Jewelry', 'Beautiful handcrafted bracelets'),
('Charm Bracelets', 'Jewelry', 'Personalized charm bracelets'),
('Friendship Bracelets', 'Jewelry', 'Colorful friendship bracelets'),
('Luxury Bracelets', 'Jewelry', 'High-end luxury bracelets'),
('Beaded Bracelets', 'Jewelry', 'Handmade beaded bracelets');

-- Insert sample sellers
INSERT INTO Seller (name, email, phone, address) VALUES
('Elegant Designs', 'elegant@example.com', '111-222-3333', '123 Design St, NY'),
('Charm Masters', 'charms@example.com', '444-555-6666', '456 Charm Ave, CA');

-- Insert sample products
INSERT INTO Products (category_ID, product_name, description, image_url) VALUES
(1, 'Silver Chain Bracelet', 'Elegant silver chain bracelet with adjustable clasp', '/assets/images/products/silver-chain.jpg'),
(2, 'Charm Collection', 'Personalized charm bracelet with multiple charms', '/assets/images/products/charm-collection.jpg'),
(3, 'Rainbow Friendship', 'Colorful friendship bracelet made with premium threads', '/assets/images/products/rainbow-friendship.jpg'),
(4, 'Diamond Tennis Bracelet', 'Luxury tennis bracelet with genuine diamonds', '/assets/images/products/diamond-tennis.jpg'),
(5, 'Boho Beaded Bracelet', 'Handmade beaded bracelet with natural stones', '/assets/images/products/boho-beaded.jpg');

-- Insert seller products with prices
INSERT INTO Seller_Products (seller_ID, product_ID, price, stock_quantity) VALUES
(1, 1, 49.99, 50),
(1, 3, 19.99, 100),
(1, 5, 29.99, 75),
(2, 2, 79.99, 30),
(2, 4, 299.99, 10);

-- Insert sample reviews
INSERT INTO Product_Reviews (product_ID, customer_ID, rating, review_text) VALUES
(1, 1, 5, 'Beautiful bracelet, exactly as described!'),
(2, 2, 4, 'Great quality, but shipping took longer than expected.'),
(3, 3, 5, 'Perfect gift for my best friend!'),
(4, 1, 5, 'Absolutely stunning piece of jewelry!'),
(5, 2, 4, 'Love the colors and craftsmanship.'); 