-- =====================================================
-- Shelluxe E-commerce Database Seed Data
-- Sample data for testing and development
-- =====================================================

-- =====================================================
-- CATEGORIES DATA
-- =====================================================

INSERT INTO categories (category_name, category_slug, parent_category_id, description, display_order, is_active) VALUES
('Bracelets', 'bracelets', NULL, 'Beautiful handcrafted bracelets for every occasion', 1, TRUE),
('Leather Bracelets', 'leather-bracelets', 1, 'Premium leather bracelets with various designs', 1, TRUE),
('Metal Bracelets', 'metal-bracelets', 1, 'Stainless steel, silver, and gold bracelets', 2, TRUE),
('Beaded Bracelets', 'beaded-bracelets', 1, 'Colorful beaded bracelets with unique patterns', 3, TRUE),
('Charm Bracelets', 'charm-bracelets', 1, 'Personalized charm bracelets', 4, TRUE),
('Men\'s Collection', 'mens-collection', NULL, 'Stylish bracelets designed for men', 2, TRUE),
('Women\'s Collection', 'womens-collection', NULL, 'Elegant bracelets for women', 3, TRUE),
('Gift Sets', 'gift-sets', NULL, 'Perfect gift combinations', 4, TRUE);

-- =====================================================
-- USERS DATA
-- =====================================================

-- Password hash for 'password123' using bcrypt
INSERT INTO users (username, email, password_hash, first_name, last_name, phone, date_of_birth, gender, email_verified, account_status) VALUES
('john_doe', 'john.doe@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John', 'Doe', '+1234567890', '1990-05-15', 'Male', TRUE, 'Active'),
('jane_smith', 'jane.smith@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane', 'Smith', '+1234567891', '1992-08-22', 'Female', TRUE, 'Active'),
('mike_wilson', 'mike.wilson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mike', 'Wilson', '+1234567892', '1988-11-10', 'Male', TRUE, 'Active'),
('sarah_jones', 'sarah.jones@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sarah', 'Jones', '+1234567893', '1995-03-25', 'Female', TRUE, 'Active'),
('admin_user', 'admin@shelluxe.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', '+1234567899', '1985-01-01', 'Other', TRUE, 'Active');

-- =====================================================
-- USER ADDRESSES DATA
-- =====================================================

INSERT INTO user_addresses (user_id, address_type, first_name, last_name, address_line1, address_line2, city, state_province, postal_code, country, phone, is_default) VALUES
(1, 'Both', 'John', 'Doe', '123 Main Street', 'Apt 4B', 'New York', 'NY', '10001', 'United States', '+1234567890', TRUE),
(2, 'Both', 'Jane', 'Smith', '456 Oak Avenue', NULL, 'Los Angeles', 'CA', '90001', 'United States', '+1234567891', TRUE),
(3, 'Both', 'Mike', 'Wilson', '789 Pine Road', 'Suite 200', 'Chicago', 'IL', '60601', 'United States', '+1234567892', TRUE),
(4, 'Both', 'Sarah', 'Jones', '321 Elm Street', NULL, 'Houston', 'TX', '77001', 'United States', '+1234567893', TRUE),
(1, 'Shipping', 'John', 'Doe', '999 Work Address', 'Floor 10', 'New York', 'NY', '10002', 'United States', '+1234567890', FALSE);

-- =====================================================
-- PRODUCTS DATA
-- =====================================================

INSERT INTO products (category_id, product_name, product_slug, sku, short_description, full_description, price, compare_at_price, cost_price, stock_quantity, low_stock_threshold, weight, material, color, size, is_active, is_featured, meta_title, meta_description) VALUES
(2, 'Classic Leather Wrap Bracelet', 'classic-leather-wrap-bracelet', 'BR-LTH-001', 'Handcrafted genuine leather wrap bracelet with adjustable closure', 'This elegant leather wrap bracelet features premium genuine leather, hand-stitched edges, and a secure adjustable closure. Perfect for everyday wear or special occasions. Available in multiple colors.', 29.99, 39.99, 15.00, 50, 10, 0.05, 'Genuine Leather', 'Brown', 'One Size', TRUE, TRUE, 'Classic Leather Wrap Bracelet - Shelluxe', 'Premium handcrafted leather wrap bracelet with adjustable closure. Free shipping available.'),
(2, 'Braided Leather Bracelet', 'braided-leather-bracelet', 'BR-LTH-002', 'Stylish braided design with metal accents', 'Beautifully braided leather bracelet with polished metal accents. Durable construction ensures long-lasting wear. Perfect gift for men and women.', 34.99, 44.99, 18.00, 75, 15, 0.06, 'Genuine Leather, Metal', 'Black', 'One Size', TRUE, TRUE, 'Braided Leather Bracelet - Shelluxe', 'Stylish braided leather bracelet with metal accents. Premium quality guaranteed.'),
(3, 'Stainless Steel Link Bracelet', 'stainless-steel-link-bracelet', 'BR-MTL-001', 'Modern stainless steel bracelet with secure clasp', 'Contemporary stainless steel bracelet featuring interlocking links and a secure lobster clasp. Hypoallergenic and tarnish-resistant. Water-resistant design.', 49.99, 69.99, 25.00, 100, 20, 0.08, 'Stainless Steel', 'Silver', 'Adjustable', TRUE, TRUE, 'Stainless Steel Link Bracelet - Shelluxe', 'Modern stainless steel bracelet with secure clasp. Perfect for daily wear.'),
(3, 'Silver Plated Charm Bracelet', 'silver-plated-charm-bracelet', 'BR-MTL-002', 'Elegant charm bracelet with customizable charms', 'Beautiful silver-plated charm bracelet with multiple attachment points for charms. Includes 3 starter charms. Additional charms available separately.', 59.99, 79.99, 30.00, 60, 12, 0.10, 'Silver Plated', 'Silver', '7 inches', TRUE, FALSE, 'Silver Plated Charm Bracelet - Shelluxe', 'Elegant charm bracelet perfect for personalization. Great gift idea.'),
(4, 'Colorful Bead Bracelet Set', 'colorful-bead-bracelet-set', 'BR-BD-001', 'Set of 3 beaded bracelets in vibrant colors', 'Handcrafted beaded bracelet set featuring natural stones and glass beads. Each bracelet is unique with vibrant colors and patterns. Stack them or wear individually.', 24.99, 34.99, 12.00, 80, 15, 0.04, 'Natural Stone, Glass Beads', 'Multi-Color', 'Adjustable', TRUE, TRUE, 'Colorful Bead Bracelet Set - Shelluxe', 'Set of 3 beautiful beaded bracelets. Perfect for stacking or wearing individually.'),
(4, 'Minimalist Bead Bracelet', 'minimalist-bead-bracelet', 'BR-BD-002', 'Simple and elegant single-strand beaded bracelet', 'Minimalist design featuring high-quality beads in neutral tones. Perfect for those who prefer subtle, elegant jewelry. Comfortable elastic band.', 19.99, 29.99, 10.00, 90, 18, 0.03, 'Natural Stone', 'Neutral', 'One Size', TRUE, FALSE, 'Minimalist Bead Bracelet - Shelluxe', 'Simple and elegant beaded bracelet. Perfect for everyday wear.'),
(5, 'Personalized Initial Charm Bracelet', 'personalized-initial-charm-bracelet', 'BR-CHM-001', 'Customizable charm bracelet with initial charm', 'Beautiful bracelet with your choice of initial charm. Made from high-quality materials with a secure clasp. Perfect personalized gift.', 39.99, 49.99, 20.00, 45, 10, 0.07, 'Metal, Enamel', 'Gold/Silver', 'Adjustable', TRUE, TRUE, 'Personalized Initial Charm Bracelet - Shelluxe', 'Customizable charm bracelet with initial. Perfect personalized gift.'),
(6, 'Men\'s Leather Cuff Bracelet', 'mens-leather-cuff-bracelet', 'BR-MEN-001', 'Bold leather cuff for men', 'Rugged and stylish leather cuff bracelet designed specifically for men. Features a strong buckle closure and premium leather construction.', 44.99, 59.99, 22.00, 55, 12, 0.09, 'Genuine Leather', 'Brown/Black', 'One Size', TRUE, TRUE, 'Men\'s Leather Cuff Bracelet - Shelluxe', 'Bold and stylish leather cuff bracelet for men. Premium quality.'),
(7, 'Women\'s Delicate Chain Bracelet', 'womens-delicate-chain-bracelet', 'BR-WOM-001', 'Elegant chain bracelet for women', 'Delicate and feminine chain bracelet with intricate links. Perfect for layering with other bracelets or wearing alone. Hypoallergenic materials.', 54.99, 74.99, 28.00, 70, 15, 0.05, 'Metal Alloy', 'Rose Gold', '7 inches', TRUE, TRUE, 'Women\'s Delicate Chain Bracelet - Shelluxe', 'Elegant and delicate chain bracelet. Perfect for any occasion.');

-- =====================================================
-- PRODUCT IMAGES DATA
-- =====================================================

INSERT INTO product_images (product_id, image_url, image_alt, display_order, is_primary) VALUES
(1, '/assets/images/products/leather-wrap-1.jpg', 'Classic Leather Wrap Bracelet front view', 1, TRUE),
(1, '/assets/images/products/leather-wrap-2.jpg', 'Classic Leather Wrap Bracelet side view', 2, FALSE),
(1, '/assets/images/products/leather-wrap-3.jpg', 'Classic Leather Wrap Bracelet detail', 3, FALSE),
(2, '/assets/images/products/braided-leather-1.jpg', 'Braided Leather Bracelet front view', 1, TRUE),
(2, '/assets/images/products/braided-leather-2.jpg', 'Braided Leather Bracelet detail', 2, FALSE),
(3, '/assets/images/products/steel-link-1.jpg', 'Stainless Steel Link Bracelet', 1, TRUE),
(3, '/assets/images/products/steel-link-2.jpg', 'Stainless Steel Link Bracelet worn', 2, FALSE),
(4, '/assets/images/products/charm-bracelet-1.jpg', 'Silver Plated Charm Bracelet', 1, TRUE),
(5, '/assets/images/products/bead-set-1.jpg', 'Colorful Bead Bracelet Set', 1, TRUE),
(5, '/assets/images/products/bead-set-2.jpg', 'Colorful Bead Bracelet Set stacked', 2, FALSE),
(6, '/assets/images/products/minimalist-bead-1.jpg', 'Minimalist Bead Bracelet', 1, TRUE),
(7, '/assets/images/products/initial-charm-1.jpg', 'Personalized Initial Charm Bracelet', 1, TRUE),
(8, '/assets/images/products/mens-cuff-1.jpg', 'Men\'s Leather Cuff Bracelet', 1, TRUE),
(9, '/assets/images/products/womens-chain-1.jpg', 'Women\'s Delicate Chain Bracelet', 1, TRUE);

-- =====================================================
-- PRODUCT REVIEWS DATA
-- =====================================================

INSERT INTO product_reviews (product_id, user_id, rating, review_title, review_text, is_verified_purchase, is_approved, helpful_count) VALUES
(1, 1, 5, 'Excellent Quality!', 'This bracelet exceeded my expectations. The leather is soft and the craftsmanship is outstanding. Highly recommend!', TRUE, TRUE, 12),
(1, 2, 4, 'Great bracelet', 'Love the design and quality. Fits perfectly and looks great with casual outfits.', TRUE, TRUE, 8),
(2, 3, 5, 'Amazing braided design', 'The braided pattern is beautiful and the metal accents add a nice touch. Very satisfied with this purchase.', TRUE, TRUE, 15),
(3, 1, 4, 'Solid stainless steel', 'Good quality bracelet. The clasp is secure and it hasn\'t tarnished after months of wear.', TRUE, TRUE, 6),
(3, 4, 5, 'Perfect for daily wear', 'I wear this bracelet every day and it still looks brand new. Great value for money.', TRUE, TRUE, 10),
(5, 2, 5, 'Beautiful set!', 'The colors are vibrant and each bracelet is unique. Great for stacking or wearing individually.', TRUE, TRUE, 9),
(7, 3, 4, 'Nice personalized touch', 'The initial charm is well-made and the bracelet itself is comfortable to wear.', TRUE, TRUE, 5),
(8, 1, 5, 'Perfect for men', 'Finally found a bracelet that looks masculine and well-made. The leather quality is excellent.', TRUE, TRUE, 11);

-- =====================================================
-- SHIPPING METHODS DATA
-- =====================================================

INSERT INTO shipping_methods (method_name, description, base_cost, cost_per_kg, estimated_days_min, estimated_days_max, is_active) VALUES
('Standard Shipping', 'Regular postal service delivery', 5.99, 1.50, 5, 7, TRUE),
('Express Shipping', 'Fast delivery service', 12.99, 2.50, 2, 3, TRUE),
('Overnight Shipping', 'Next day delivery', 24.99, 3.00, 1, 1, TRUE),
('Free Shipping', 'Free standard shipping for orders over $50', 0.00, 0.00, 5, 7, TRUE);

-- =====================================================
-- PAYMENT METHODS DATA
-- =====================================================

INSERT INTO payment_methods (method_name, method_type, is_active, processing_fee_percentage) VALUES
('Credit Card', 'Credit Card', TRUE, 2.5),
('Debit Card', 'Debit Card', TRUE, 1.5),
('PayPal', 'PayPal', TRUE, 3.0),
('Bank Transfer', 'Bank Transfer', TRUE, 0.0),
('Cash on Delivery', 'Cash on Delivery', TRUE, 0.0);

-- =====================================================
-- ORDERS DATA
-- =====================================================

INSERT INTO orders (user_id, order_number, order_status, shipping_address_id, billing_address_id, shipping_method_id, payment_method_id, subtotal, shipping_cost, tax_amount, discount_amount, total_amount, payment_status, payment_transaction_id, created_at) VALUES
(1, 'ORD-20240115-000001', 'Delivered', 1, 1, 1, 1, 29.99, 5.99, 2.70, 0.00, 38.68, 'Paid', 'TXN-001-20240115', '2024-01-15 10:30:00'),
(2, 'ORD-20240116-000002', 'Shipped', 2, 2, 2, 2, 79.98, 12.99, 7.20, 0.00, 100.17, 'Paid', 'TXN-002-20240116', '2024-01-16 14:20:00'),
(3, 'ORD-20240117-000003', 'Processing', 3, 3, 1, 1, 49.99, 0.00, 4.50, 5.00, 49.49, 'Paid', 'TXN-003-20240117', '2024-01-17 09:15:00'),
(4, 'ORD-20240118-000004', 'Pending', 4, 4, 1, 3, 24.99, 5.99, 2.25, 0.00, 33.23, 'Pending', NULL, '2024-01-18 16:45:00');

-- =====================================================
-- ORDER ITEMS DATA
-- =====================================================

INSERT INTO order_items (order_id, product_id, product_name, product_sku, quantity, unit_price, total_price) VALUES
(1, 1, 'Classic Leather Wrap Bracelet', 'BR-LTH-001', 1, 29.99, 29.99),
(2, 3, 'Stainless Steel Link Bracelet', 'BR-MTL-001', 1, 49.99, 49.99),
(2, 5, 'Colorful Bead Bracelet Set', 'BR-BD-001', 1, 24.99, 24.99),
(3, 3, 'Stainless Steel Link Bracelet', 'BR-MTL-001', 1, 49.99, 49.99),
(4, 5, 'Colorful Bead Bracelet Set', 'BR-BD-001', 1, 24.99, 24.99);

-- =====================================================
-- CART ITEMS DATA
-- =====================================================

INSERT INTO cart_items (user_id, product_id, quantity) VALUES
(1, 2, 1),
(1, 7, 2),
(2, 4, 1),
(3, 8, 1),
(4, 6, 1),
(4, 9, 1);

-- =====================================================
-- WISHLIST ITEMS DATA
-- =====================================================

INSERT INTO wishlist_items (user_id, product_id) VALUES
(1, 3),
(1, 5),
(2, 7),
(2, 9),
(3, 1),
(4, 8);

-- =====================================================
-- END OF SEED DATA
-- =====================================================

