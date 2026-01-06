-- =====================================================
-- Shelluxe Database - Example Queries
-- SQL Intermediate Level - Query Examples
-- =====================================================
-- This file contains example queries demonstrating
-- SQL intermediate-level skills and common use cases
-- =====================================================

-- =====================================================
-- BASIC QUERIES
-- =====================================================

-- 1. Get all active products with their categories
SELECT 
    p.product_id,
    p.product_name,
    p.price,
    c.category_name,
    p.stock_quantity
FROM products p
INNER JOIN categories c ON p.category_id = c.category_id
WHERE p.is_active = TRUE
ORDER BY p.product_name;

-- 2. Get products with average ratings
SELECT 
    p.product_name,
    p.price,
    COALESCE(AVG(pr.rating), 0) AS average_rating,
    COUNT(pr.review_id) AS review_count
FROM products p
LEFT JOIN product_reviews pr ON p.product_id = pr.product_id AND pr.is_approved = TRUE
WHERE p.is_active = TRUE
GROUP BY p.product_id, p.product_name, p.price
HAVING average_rating >= 4.0
ORDER BY average_rating DESC, review_count DESC;

-- =====================================================
-- ADVANCED QUERIES
-- =====================================================

-- 3. Get user's complete order history with item details
SELECT 
    o.order_number,
    o.order_status,
    o.total_amount,
    o.created_at,
    COUNT(oi.order_item_id) AS item_count,
    GROUP_CONCAT(oi.product_name SEPARATOR ', ') AS products
FROM orders o
INNER JOIN order_items oi ON o.order_id = oi.order_id
WHERE o.user_id = 1
GROUP BY o.order_id, o.order_number, o.order_status, o.total_amount, o.created_at
ORDER BY o.created_at DESC;

-- 4. Get top-selling products
SELECT 
    p.product_name,
    p.sku,
    SUM(oi.quantity) AS total_sold,
    SUM(oi.total_price) AS total_revenue,
    COUNT(DISTINCT o.order_id) AS order_count
FROM products p
INNER JOIN order_items oi ON p.product_id = oi.product_id
INNER JOIN orders o ON oi.order_id = o.order_id
WHERE o.order_status != 'Cancelled'
GROUP BY p.product_id, p.product_name, p.sku
ORDER BY total_sold DESC
LIMIT 10;

-- 5. Get products with low stock
SELECT 
    product_name,
    sku,
    stock_quantity,
    low_stock_threshold,
    (stock_quantity - low_stock_threshold) AS stock_difference
FROM products
WHERE stock_quantity <= low_stock_threshold
    AND is_active = TRUE
ORDER BY stock_quantity ASC;

-- =====================================================
-- SUBQUERIES
-- =====================================================

-- 6. Get users who have placed orders but haven't reviewed
SELECT 
    u.user_id,
    u.username,
    u.email,
    COUNT(DISTINCT o.order_id) AS order_count
FROM users u
INNER JOIN orders o ON u.user_id = o.user_id
WHERE u.user_id NOT IN (
    SELECT DISTINCT user_id 
    FROM product_reviews
)
GROUP BY u.user_id, u.username, u.email
HAVING order_count > 0;

-- 7. Get products that are in wishlist but never purchased
SELECT 
    p.product_id,
    p.product_name,
    p.price,
    COUNT(DISTINCT wi.user_id) AS wishlist_count
FROM products p
INNER JOIN wishlist_items wi ON p.product_id = wi.product_id
WHERE p.product_id NOT IN (
    SELECT DISTINCT product_id 
    FROM order_items
)
GROUP BY p.product_id, p.product_name, p.price
ORDER BY wishlist_count DESC;

-- =====================================================
-- AGGREGATE FUNCTIONS WITH GROUP BY
-- =====================================================

-- 8. Get category sales summary
SELECT 
    c.category_name,
    COUNT(DISTINCT oi.product_id) AS products_sold,
    SUM(oi.quantity) AS total_quantity,
    SUM(oi.total_price) AS total_revenue,
    AVG(oi.unit_price) AS average_price
FROM categories c
INNER JOIN products p ON c.category_id = p.category_id
INNER JOIN order_items oi ON p.product_id = oi.product_id
INNER JOIN orders o ON oi.order_id = o.order_id
WHERE o.order_status != 'Cancelled'
GROUP BY c.category_id, c.category_name
ORDER BY total_revenue DESC;

-- 9. Get monthly sales report
SELECT 
    DATE_FORMAT(o.created_at, '%Y-%m') AS month,
    COUNT(DISTINCT o.order_id) AS total_orders,
    COUNT(DISTINCT o.user_id) AS unique_customers,
    SUM(o.total_amount) AS total_revenue,
    AVG(o.total_amount) AS average_order_value
FROM orders o
WHERE o.order_status != 'Cancelled'
GROUP BY DATE_FORMAT(o.created_at, '%Y-%m')
ORDER BY month DESC;

-- =====================================================
-- JOIN EXAMPLES
-- =====================================================

-- 10. Get complete product details with all related data
SELECT 
    p.product_id,
    p.product_name,
    p.price,
    p.stock_quantity,
    c.category_name,
    c.parent_category_id,
    GROUP_CONCAT(DISTINCT pi.image_url ORDER BY pi.display_order SEPARATOR ', ') AS images,
    COALESCE(AVG(pr.rating), 0) AS avg_rating,
    COUNT(DISTINCT pr.review_id) AS review_count,
    COUNT(DISTINCT ci.user_id) AS in_cart_count,
    COUNT(DISTINCT wi.user_id) AS wishlist_count
FROM products p
INNER JOIN categories c ON p.category_id = c.category_id
LEFT JOIN product_images pi ON p.product_id = pi.product_id
LEFT JOIN product_reviews pr ON p.product_id = pr.product_id AND pr.is_approved = TRUE
LEFT JOIN cart_items ci ON p.product_id = ci.product_id
LEFT JOIN wishlist_items wi ON p.product_id = wi.product_id
WHERE p.product_id = 1
GROUP BY p.product_id, p.product_name, p.price, p.stock_quantity, c.category_name, c.parent_category_id;

-- 11. Get user's cart with product details
SELECT 
    ci.cart_item_id,
    ci.quantity,
    p.product_name,
    p.price,
    p.stock_quantity,
    (ci.quantity * p.price) AS line_total,
    pi.image_url AS primary_image
FROM cart_items ci
INNER JOIN products p ON ci.product_id = p.product_id
LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = TRUE
WHERE ci.user_id = 1
ORDER BY ci.added_at DESC;

-- =====================================================
-- CASE STATEMENTS
-- =====================================================

-- 12. Categorize products by price range
SELECT 
    product_name,
    price,
    CASE 
        WHEN price < 25 THEN 'Budget'
        WHEN price BETWEEN 25 AND 50 THEN 'Mid-Range'
        WHEN price > 50 THEN 'Premium'
    END AS price_category,
    stock_quantity,
    CASE 
        WHEN stock_quantity = 0 THEN 'Out of Stock'
        WHEN stock_quantity <= low_stock_threshold THEN 'Low Stock'
        ELSE 'In Stock'
    END AS stock_status
FROM products
WHERE is_active = TRUE
ORDER BY price DESC;

-- 13. Get order status summary with counts
SELECT 
    order_status,
    COUNT(*) AS order_count,
    SUM(total_amount) AS total_revenue,
    AVG(total_amount) AS average_order_value,
    CASE 
        WHEN order_status = 'Delivered' THEN 'Completed'
        WHEN order_status IN ('Pending', 'Processing') THEN 'In Progress'
        WHEN order_status = 'Cancelled' THEN 'Cancelled'
        ELSE 'Other'
    END AS status_category
FROM orders
GROUP BY order_status
ORDER BY order_count DESC;

-- =====================================================
-- WINDOW FUNCTIONS (MySQL 8.0+)
-- =====================================================

-- 14. Get products ranked by sales within each category
SELECT 
    p.product_name,
    c.category_name,
    SUM(oi.quantity) AS total_sold,
    RANK() OVER (PARTITION BY c.category_id ORDER BY SUM(oi.quantity) DESC) AS rank_in_category
FROM products p
INNER JOIN categories c ON p.category_id = c.category_id
LEFT JOIN order_items oi ON p.product_id = oi.product_id
LEFT JOIN orders o ON oi.order_id = o.order_id AND o.order_status != 'Cancelled'
GROUP BY p.product_id, p.product_name, c.category_id, c.category_name
ORDER BY c.category_name, rank_in_category;

-- =====================================================
-- FULL-TEXT SEARCH
-- =====================================================

-- 15. Search products by keyword
SELECT 
    product_id,
    product_name,
    short_description,
    price,
    MATCH(product_name, short_description, full_description) AGAINST('leather' IN NATURAL LANGUAGE MODE) AS relevance_score
FROM products
WHERE MATCH(product_name, short_description, full_description) AGAINST('leather' IN NATURAL LANGUAGE MODE)
    AND is_active = TRUE
ORDER BY relevance_score DESC;

-- =====================================================
-- USING VIEWS
-- =====================================================

-- 16. Get featured products with ratings
SELECT 
    product_name,
    price,
    average_rating,
    review_count,
    stock_quantity
FROM v_product_summary
WHERE average_rating >= 4.0
    AND stock_quantity > 0
ORDER BY average_rating DESC, review_count DESC
LIMIT 10;

-- 17. Get pending orders summary
SELECT 
    order_number,
    customer_name,
    total_amount,
    item_count,
    created_at
FROM v_order_summary
WHERE order_status = 'Pending'
ORDER BY created_at ASC;

-- =====================================================
-- STORED PROCEDURE USAGE
-- =====================================================

-- 18. Get product details using stored procedure
CALL sp_get_product_details(1);

-- 19. Calculate order total
SET @order_total = 0;
CALL sp_calculate_order_total(1, @order_total);
SELECT @order_total AS order_total;

-- =====================================================
-- COMPLEX ANALYTICS QUERIES
-- =====================================================

-- 20. Customer lifetime value analysis
SELECT 
    u.user_id,
    u.username,
    u.email,
    COUNT(DISTINCT o.order_id) AS total_orders,
    SUM(o.total_amount) AS lifetime_value,
    AVG(o.total_amount) AS average_order_value,
    MIN(o.created_at) AS first_order_date,
    MAX(o.created_at) AS last_order_date,
    DATEDIFF(MAX(o.created_at), MIN(o.created_at)) AS customer_lifespan_days
FROM users u
INNER JOIN orders o ON u.user_id = o.user_id
WHERE o.order_status != 'Cancelled'
GROUP BY u.user_id, u.username, u.email
HAVING total_orders > 1
ORDER BY lifetime_value DESC;

-- 21. Product performance analysis
SELECT 
    p.product_id,
    p.product_name,
    p.price,
    p.stock_quantity,
    COUNT(DISTINCT oi.order_id) AS times_ordered,
    SUM(oi.quantity) AS total_quantity_sold,
    SUM(oi.total_price) AS total_revenue,
    COUNT(DISTINCT pr.review_id) AS review_count,
    COALESCE(AVG(pr.rating), 0) AS average_rating,
    COUNT(DISTINCT ci.user_id) AS in_cart_count,
    COUNT(DISTINCT wi.user_id) AS wishlist_count,
    (COUNT(DISTINCT wi.user_id) + COUNT(DISTINCT ci.user_id)) AS engagement_score
FROM products p
LEFT JOIN order_items oi ON p.product_id = oi.product_id
LEFT JOIN orders o ON oi.order_id = o.order_id AND o.order_status != 'Cancelled'
LEFT JOIN product_reviews pr ON p.product_id = pr.product_id AND pr.is_approved = TRUE
LEFT JOIN cart_items ci ON p.product_id = ci.product_id
LEFT JOIN wishlist_items wi ON p.product_id = wi.product_id
WHERE p.is_active = TRUE
GROUP BY p.product_id, p.product_name, p.price, p.stock_quantity
ORDER BY engagement_score DESC, total_revenue DESC;

-- =====================================================
-- DATA VALIDATION QUERIES
-- =====================================================

-- 22. Find orders with mismatched totals
SELECT 
    o.order_id,
    o.order_number,
    o.total_amount AS stored_total,
    SUM(oi.total_price) AS calculated_total,
    (o.total_amount - SUM(oi.total_price)) AS difference
FROM orders o
INNER JOIN order_items oi ON o.order_id = oi.order_id
GROUP BY o.order_id, o.order_number, o.total_amount
HAVING ABS(difference) > 0.01;

-- 23. Find products with no images
SELECT 
    p.product_id,
    p.product_name,
    p.sku
FROM products p
LEFT JOIN product_images pi ON p.product_id = pi.product_id
WHERE pi.image_id IS NULL
    AND p.is_active = TRUE;

-- =====================================================
-- END OF EXAMPLE QUERIES
-- =====================================================

